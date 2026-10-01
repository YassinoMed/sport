<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ModeratorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin|cityManager|gymManager']);
    }

    private function checkModeratorAccess(User $moderator)
    { 
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return;
        }

        // Prevent editing admins for non-admins
        if ($moderator->hasRole('admin')) {
            abort(403);
        }

        if ($user->hasRole('cityManager')) {
            if ($moderator->city_id !== $user->city_id) {
                abort(403);
            }
            return;
        }

        if ($user->hasRole('gymManager')) {
            if ($moderator->gym_id !== $user->gym_id) {
                abort(403);
            }
            return;
        }

        abort(403);
    }

    public function index()
    {
        $user = auth()->user();
        $query = User::whereHas('roles', function($q) {
            $q->where('name', '!=', 'user');
        })->with('roles', 'city', 'gym');

        // RBAC Scope
        if ($user->hasRole('cityManager')) {
            $query->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $query->where('gym_id', $user->gym_id);
        }

        $moderators = $query->latest()->paginate(20);

        $rolesQuery = Role::where('name', '!=', 'user');
        $citiesQuery = City::query();
        $gymsQuery = Gym::query();

        if (!$user->hasRole('admin')) {
            $rolesQuery->where('name', '!=', 'admin');
        }

        if ($user->hasRole('cityManager')) {
            $citiesQuery->where('id', $user->city_id);
            $gymsQuery->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $citiesQuery->where('id', $user->city_id);
            $gymsQuery->where('id', $user->gym_id);
        }

        $roles = $rolesQuery->get();
        $cities = $citiesQuery->get();
        $gyms = $gymsQuery->get();

        return view('moderators.index', compact('moderators', 'roles', 'cities', 'gyms'));
    }

    public function create()
    {
        $user = auth()->user();
        $rolesQuery = Role::where('name', '!=', 'user');
        $citiesQuery = City::query();
        $gymsQuery = Gym::query();

        if (!$user->hasRole('admin')) {
            $rolesQuery->where('name', '!=', 'admin');
        }

        if ($user->hasRole('cityManager')) {
            $citiesQuery->where('id', $user->city_id);
            $gymsQuery->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $citiesQuery->where('id', $user->city_id);
            $gymsQuery->where('id', $user->gym_id);
        }

        $roles = $rolesQuery->get();
        $cities = $citiesQuery->get();
        $gyms = $gymsQuery->get();

        return view('moderators.create', compact('roles', 'cities', 'gyms'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'city_id' => 'nullable|exists:cities,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20|unique:users',
        ]);

        // RBAC validation for store
        if (!$authUser->hasRole('admin')) {
            if ($request->role === 'admin') abort(403);
            
            if ($authUser->hasRole('cityManager')) {
                if ($request->city_id != $authUser->city_id) abort(403);
            } elseif ($authUser->hasRole('gymManager')) {
                if ($request->gym_id != $authUser->gym_id) abort(403);
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'city_id' => $request->city_id,
            'gym_id' => $request->gym_id,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('moderators.index')->with('success', __('messages.moderator_created'));
    }

    public function edit(User $moderator)
    {
        $this->checkModeratorAccess($moderator);
        
        $user = auth()->user();
        $rolesQuery = Role::where('name', '!=', 'user');
        $citiesQuery = City::query();
        $gymsQuery = Gym::query();

        if (!$user->hasRole('admin')) {
            $rolesQuery->where('name', '!=', 'admin');
        }

        if ($user->hasRole('cityManager')) {
            $citiesQuery->where('id', $user->city_id);
            $gymsQuery->where('city_id', $user->city_id);
        } elseif ($user->hasRole('gymManager')) {
            $citiesQuery->where('id', $user->city_id);
            $gymsQuery->where('id', $user->gym_id);
        }

        $roles = $rolesQuery->get();
        $cities = $citiesQuery->get();
        $gyms = $gymsQuery->get();

        return view('moderators.edit', compact('moderator', 'roles', 'cities', 'gyms'));
    }

    public function update(Request $request, User $moderator)
    {
        $this->checkModeratorAccess($moderator);
        $authUser = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($moderator->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|exists:roles,name',
            'city_id' => 'nullable|exists:cities,id',
            'gym_id' => 'nullable|exists:gyms,id',
            'phone' => 'nullable|string|max:20',
            'national_id' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($moderator->id)],
        ]);

        // RBAC validation for update
        if (!$authUser->hasRole('admin')) {
            if ($request->role === 'admin') abort(403);
            
            if ($authUser->hasRole('cityManager')) {
                if ($request->city_id != $authUser->city_id) abort(403);
            } elseif ($authUser->hasRole('gymManager')) {
                if ($request->gym_id != $authUser->gym_id) abort(403);
            }
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'national_id' => $request->national_id,
            'city_id' => $request->city_id,
            'gym_id' => $request->gym_id,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $moderator->update($data);
        $moderator->syncRoles([$request->role]);

        return redirect()->route('moderators.index')->with('success', __('messages.moderator_updated'));
    }

    public function destroy(User $moderator)
    {
        $this->checkModeratorAccess($moderator);
        
        if ($moderator->id === auth()->id()) {
            return redirect()->route('moderators.index')->with('error', __('messages.cannot_delete_self'));
        }

        $moderator->delete();
        return redirect()->route('moderators.index')->with('success', __('messages.moderator_deleted'));
    }
}
