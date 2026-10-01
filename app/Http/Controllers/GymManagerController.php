<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;



class GymManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'logs-out-banned-user', 'role:admin|cityManager|gymManager']);
    }

    private function checkGymManagerAccess($managerId)
    {
        $authUser = auth()->user();
        if (!$authUser) abort(401);

        $manager = User::role('gymManager')->findOrFail($managerId);

        if ($authUser->hasRole('admin')) {
            return $manager;
        }

        if ($authUser->hasRole('cityManager')) {
            if ($manager->city_id !== $authUser->city_id) {
                abort(403);
            }
            return $manager;
        }

        if ($authUser->hasRole('gymManager')) {
            if ((int) $authUser->id !== (int) $manager->id) {
                abort(403);
            }
            return $manager;
        }

        abort(403);
    }

    #=======================================================================================#
    #			                           Create Function                              	#
    #=======================================================================================#
    public function create()
    {
        $user = auth()->user();
        $cities = City::query();
        $gyms = Gym::query();

        if ($user->hasRole('cityManager')) {
            $cities->where('id', $user->city_id);
            $gyms->where('city_id', $user->city_id);
        }

        return view('gymManager.create', [
            'cities' => $cities->get(),
            'gyms' => $gyms->get()
        ]);
    }
    #=======================================================================================#
    #			                           Store Function                                	#
    #=======================================================================================#
    public function store(Request $request)
    {
        $authUser = auth()->user();
        
        $rules = [
            'name' => 'required',
            'phone' => 'required|string',
            'national_id' => 'digits_between:10,17|required|numeric|unique:users',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'city_id' => 'required|exists:cities,id',
            'gym_id' => 'required|exists:gyms,id',
        ];

        if ($authUser->hasRole('cityManager')) {
            if ($request->city_id != $authUser->city_id) {
                return back()->withErrors(['city_id' => 'Vous ne pouvez pas assigner un manager à une autre ville.'])->withInput();
            }
            // Ensure gym belongs to city
            $gym = Gym::find($request->gym_id);
            if (!$gym || $gym->city_id != $authUser->city_id) {
                return back()->withErrors(['gym_id' => 'Cette salle n\'appartient pas à votre ville.'])->withInput();
            }
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('profile_image') == null) {
            $imageName = 'imgs/defaultImg.jpg';
        } else {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'imgs/' . $name;
        }

        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->profile_image = $imageName;
        $user->national_id = $request->national_id;
        $user->city_id = $request->city_id;
        $user->gym_id = $request->gym_id;
        $user->assignRole('gymManager');
        $user->save();
        return redirect()->route('gymManager.list')->with('success', __('messages.gym_manager_created'));
    }

    #=======================================================================================#
    #			                           List Function                                	#
    #=======================================================================================#
    public function list(Request $request)
    {
        $user = auth()->user();
        $t0 = microtime(true);
        $query = User::role('gymManager')
            ->withoutBanned()
            ->with(['city', 'gym'])
            ->select(['id','name','email','phone','profile_image','national_id', 'city_id', 'gym_id'])
            ->orderBy('id','desc');

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('id', $user->id);
        }

        // Search
        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('national_id', 'like', "%$q%");
            });
        }

        $usersFromDB = $query->paginate(20);
        if ($usersFromDB->total() <= 0) {
            return view('empty');
        }
        Log::info('GymManagerController@list', [
            'count' => $usersFromDB->total(),
            'duration_ms' => (int) ((microtime(true) - $t0) * 1000),
            'page' => $usersFromDB->currentPage(),
        ]);
        return view("gymManager.list", ['users' => $usersFromDB]);
    }
    #=======================================================================================#
    #			                           Show Function                                	#
    #=======================================================================================#
    public function show($id)
    {
        $singleUser = $this->checkGymManagerAccess($id);
        return view("gymManager.show", ['singleUser' => $singleUser]);
    }
    #=======================================================================================#
    #			                           Edit Function                                	#
    #=======================================================================================#
    public function edit($id)
    {
        $singleUser = $this->checkGymManagerAccess($id);
        return view("gymManager.edit", ['singleUser' => $singleUser]);
    }

    #=======================================================================================#
    #			                           Update Function                                	#
    #=======================================================================================#
    public function update(Request $request, $id)
    {
        $user = $this->checkGymManagerAccess($id);
        $validated = $request->validate([
            'name' => 'required',
            'phone' => 'required|string',
            'national_id' => ['required', 'digits_between:10,17', 'numeric', Rule::unique('users')->ignore($user->id)],
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->national_id = $request->national_id;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'imgs/' . $name;
            if ($user->profile_image && $user->profile_image != 'imgs/defaultImg.jpg')
                File::delete(public_path($user->profile_image));
            $user->profile_image = $imageName;
        }
        $user->save();
        return redirect()->route('gymManager.list')->with('success', __('messages.gym_manager_updated'));
    }
    #=======================================================================================#
    #			                           Delete Function                                	#
    #=======================================================================================#
    public function deletegymManager($id)
    {
        $singleUser = $this->checkGymManagerAccess($id);
        $singleUser->delete();
        return response()->json(['success' => __('messages.gym_manager_deleted')]);
    }
}
