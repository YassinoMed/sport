<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class CityManagerController extends Controller
{

    #=======================================================================================#
    #			                           Create Function                              	#
    #=======================================================================================#
    public function create()
    {
        return view('cityManager.create', [
            'users' => User::all(),
        ]);
    }
    #=======================================================================================#
    #			                           Store Function                                	#
    #=======================================================================================#
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|max:20',
            'password' => 'required |min:6',
            'email' => 'required|string|unique:users,email,',
            'national_id' => 'digits_between:10,17|required|numeric|unique:users',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg',
        ]);

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
        $user->email = $request->email;
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->profile_image = $imageName;
        $user->national_id = $request->national_id;
        $user->assignRole('cityManager');
        $user->save();

        return redirect()->route('cityManager.list')->with('success', __('messages.city_manager_created'));
    }


    #=======================================================================================#
    #			                           List Function                                	#
    #=======================================================================================#
    public function list()
    {
        $t0 = microtime(true);
        $usersFromDB =  User::role('cityManager')->withoutBanned()->select(['id','name','email','profile_image','national_id'])->orderBy('id','desc')->paginate(20);
        if ($usersFromDB->total() <= 0) {
            return view('empty');
        }
        Log::info('CityManagerController@list', [
            'count' => $usersFromDB->total(),
            'duration_ms' => (int) ((microtime(true) - $t0) * 1000),
            'page' => $usersFromDB->currentPage(),
        ]);
        return view("cityManager.list", ['users' => $usersFromDB]);
    }
    #=======================================================================================#
    #			                           Show Function                                	#
    #=======================================================================================#
    public function show($id)
    {
        $singleUser = User::findorfail($id);
        return view("cityManager.show", ['singleUser' => $singleUser]);
    }
    #=======================================================================================#
    #			                           Edit Function                                	#
    #=======================================================================================#
    public function edit($id)
    {

        $singleUser = User::find($id);
        return view("cityManager.edit", ['singleUser' => $singleUser]);
    }



    #=======================================================================================#
    #			                           Update Function                                	#
    #=======================================================================================#
    public function update(Request $request, $id)
    {

        $user = User::find($id);
        $validated = $request->validate([
            'name' => 'required|max:20',
            'password' => 'required |min:6',
            'email' => 'required|string|unique:users,email,' . $user->id,
            'profile_image' => 'nullable|image|mimes:jpg,jpeg',
            'national_id' => 'digits_between:10,17|numeric|unique:users,national_id,' . $user->id,
        ]);


        $user->name = $request->name;
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->email = $request->email;
        $user->national_id = $request->national_id;


        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . \Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $imageName = 'imgs/' . $name;
            if (isset($user->profile_image))
                File::delete(public_path('imgs/' . $user->profile_image));
            $user->profile_image = $imageName;
        }
        $user->save();
        return redirect()->route('cityManager.list')->with('success', __('messages.city_manager_updated'));
    }

    #=======================================================================================#
    #			                           Delete Function                                	#
    #=======================================================================================#
    public function deletecityManager($id)
    {

        $singleUser = User::findorfail($id);
        $singleUser->delete();
        return response()->json(['success' => __('messages.city_manager_deleted')]);
    }
}
