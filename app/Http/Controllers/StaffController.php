<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Gym;
use App\Models\City;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|cityManager|gymManager']);
    }

    private function checkStaffAccess(User $staff)
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return;
        }

        // Prevent editing admins for non-admins
        if ($staff->hasRole('admin')) {
            abort(403);
        }

        if ($user->hasRole('cityManager')) {
            if ($staff->city_id !== $user->city_id) {
                abort(403);
            }
            return;
        }

        if ($user->hasRole('gymManager')) {
            if ($staff->gym_id !== $user->gym_id) {
                abort(403);
            }
            return;
        }

        abort(403);
    }

    /**
     * Display a listing of the staff members.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = User::whereHas('roles', function($q) {
            $q->where('name', '!=', 'user');
        })->with(['roles', 'gym', 'city']);

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('gym_id', $user->gym_id);
        }

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%");
            });
        }

        if ($request->filled('role')) {
            $query->role($request->get('role'));
        }

        $staff = $query->latest()->paginate(20);
        $roles = Role::where('name', '!=', 'user')->get();

        return view('staff.index', compact('staff', 'roles'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $user = auth()->user();
        $roles = Role::where('name', '!=', 'user');
        $gyms = Gym::orderBy('name');
        $cities = City::orderBy('name');

        if (!$user->hasRole('admin')) {
            $roles->where('name', '!=', 'admin');
        }

        if ($user->hasRole('cityManager')) {
            $cities->where('id', $user->city_id);
            $gyms->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $cities->where('id', $user->city_id);
            $gyms->where('id', $user->gym_id);
        }
        
        $roles = $roles->get();
        $gyms = $gyms->get();
        $cities = $cities->get();
        
        return view('staff.create', compact('roles', 'gyms', 'cities'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        $authUser = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'national_id' => 'nullable|numeric|unique:users,national_id',
            'phone' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'city_id' => 'nullable|required_if:role,cityManager|exists:cities,id',
            'gym_id' => 'nullable|required_if:role,gymManager|exists:gyms,id',
        ]);

        // RBAC validation for store
        if (!$authUser->hasRole('admin')) {
            if ($validated['role'] === 'admin') abort(403);
            
            if ($authUser->hasRole('cityManager')) {
                if ($request->city_id != $authUser->city_id) abort(403);
            } elseif ($authUser->hasRole('gymManager')) {
                if ($request->gym_id != $authUser->gym_id) abort(403);
            }
        }

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->national_id = $validated['national_id'] ?? null;
        $user->phone = $validated['phone'] ?? null;
        $user->city_id = $validated['city_id'] ?? null;
        $user->gym_id = $validated['gym_id'] ?? null;

        if ($request->hasFile('profile_image')) {
            $image = $request->file('profile_image');
            $name = time() . Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $user->profile_image = 'imgs/' . $name;
        } else {
            $user->profile_image = 'imgs/defaultImg.jpg';
        }

        $user->save();
        $user->assignRole($validated['role']);

        return redirect()->route('staff.index')->with('success', __('messages.staff_created'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        $this->checkStaffAccess($staff);
        
        // Ensure we are not editing a regular user
        if ($staff->hasRole('user')) {
            return redirect()->route('staff.index')->with('error', 'Cannot edit regular users here.');
        }

        $user = auth()->user();
        $roles = Role::where('name', '!=', 'user');
        $gyms = Gym::orderBy('name');
        $cities = City::orderBy('name');

        if (!$user->hasRole('admin')) {
            $roles->where('name', '!=', 'admin');
        }

        if ($user->hasRole('cityManager')) {
            $cities->where('id', $user->city_id);
            $gyms->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $cities->where('id', $user->city_id);
            $gyms->where('id', $user->gym_id);
        }

        $roles = $roles->get();
        $gyms = $gyms->get();
        $cities = $cities->get();
        $currentRole = $staff->roles->first()?->name;

        return view('staff.edit', compact('staff', 'roles', 'gyms', 'cities', 'currentRole'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, User $staff)
    {
        $this->checkStaffAccess($staff);
        $authUser = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($staff->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'national_id' => ['nullable', 'numeric', Rule::unique('users')->ignore($staff->id)],
            'phone' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png',
            'city_id' => 'nullable|required_if:role,cityManager|exists:cities,id',
            'gym_id' => 'nullable|required_if:role,gymManager|exists:gyms,id',
        ]);

        // RBAC validation for update
        if (!$authUser->hasRole('admin')) {
            if ($validated['role'] === 'admin') abort(403);
            
            if ($authUser->hasRole('cityManager')) {
                if ($request->city_id != $authUser->city_id) abort(403);
            } elseif ($authUser->hasRole('gymManager')) {
                if ($request->gym_id != $authUser->gym_id) abort(403);
            }
        }

        $staff->name = $validated['name'];
        $staff->email = $validated['email'];
        
        if ($request->filled('password')) {
            $staff->password = Hash::make($validated['password']);
        }
        
        $staff->national_id = $validated['national_id'] ?? null;
        $staff->phone = $validated['phone'] ?? null;
        $staff->city_id = $validated['city_id'] ?? null;
        $staff->gym_id = $validated['gym_id'] ?? null;

        if ($request->hasFile('profile_image')) {
            // Delete old image if not default
            if ($staff->profile_image && $staff->profile_image !== 'imgs/defaultImg.jpg') {
                File::delete(public_path($staff->profile_image));
            }
            
            $image = $request->file('profile_image');
            $name = time() . Str::random(30) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/imgs');
            $image->move($destinationPath, $name);
            $staff->profile_image = 'imgs/' . $name;
        }

        $staff->save();
        $staff->syncRoles([$validated['role']]);

        return redirect()->route('staff.index')->with('success', __('messages.staff_updated'));
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        $this->checkStaffAccess($staff);
        
        if ($staff->id === auth()->id()) {
            return redirect()->route('staff.index')->with('error', 'You cannot delete yourself.');
        }

        if ($staff->profile_image && $staff->profile_image !== 'imgs/defaultImg.jpg') {
            File::delete(public_path($staff->profile_image));
        }

        $staff->delete();

        return redirect()->route('staff.index')->with('success', __('messages.staff_deleted'));
    }
}
