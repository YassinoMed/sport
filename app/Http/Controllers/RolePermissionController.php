<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    /**
     * Display roles and permissions management page
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::orderBy('name')->get();
        
        // Group permissions by category
        $groupedPermissions = $this->groupPermissions($permissions);
        
        return view('roles.index', compact('roles', 'permissions', 'groupedPermissions'));
    }

    /**
     * Display users for a specific role
     */
    public function showUsers(Role $role)
    {
        $users = \App\Models\User::role($role->name)->with('city', 'gym')->paginate(20);
        return view('roles.show-users', compact('role', 'users'));
    }

    /**
     * Show form to create a new role
     */
    public function createRole()
    {
        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        
        return view('roles.create-role', compact('permissions', 'groupedPermissions'));
    }

    /**
     * Store a new role
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
            
            if ($request->has('permissions')) {
                $permissions = Permission::whereIn('id', $request->permissions)->get();
                $role->syncPermissions($permissions);
            }
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('messages.role_created'));
    }

    /**
     * Show form to edit a role
     */
    public function editRole(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $groupedPermissions = $this->groupPermissions($permissions);
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        
        return view('roles.edit-role', compact('role', 'permissions', 'groupedPermissions', 'rolePermissions'));
    }

    /**
     * Update a role
     */
    public function updateRole(Request $request, Role $role)
    {
        // Prevent editing admin role name
        $rules = [
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
        
        if ($role->name !== 'admin') {
            $rules['name'] = ['required', 'string', 'max:255', 'unique:roles,name,' . $role->id];
        }

        $request->validate($rules);

        DB::transaction(function () use ($request, $role) {
            if ($role->name !== 'admin' && $request->has('name')) {
                $role->update(['name' => $request->name]);
            }
            
            $permissions = $request->has('permissions') 
                ? Permission::whereIn('id', $request->permissions)->get() 
                : collect();
            $role->syncPermissions($permissions);
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('messages.role_updated'));
    }

    /**
     * Clone a role
     */
    public function cloneRole(Role $role)
    {
        $newRoleName = $role->name . '_copy_' . time();
        
        DB::transaction(function () use ($role, $newRoleName) {
            $newRole = Role::create([
                'name' => $newRoleName,
                'guard_name' => $role->guard_name
            ]);
            
            $permissions = $role->permissions()->pluck('name')->toArray();
            $newRole->givePermissionTo($permissions);
        });

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('messages.role_cloned'));
    }

    /**
     * Delete a role
     */
    public function destroyRole(Role $role)
    {
        // Prevent deleting system roles
        $protectedRoles = ['admin', 'cityManager', 'gymManager', 'coach', 'user'];
        
        if (in_array($role->name, $protectedRoles)) {
            return redirect()->route('roles.index')->with('error', __('messages.role_protected'));
        }

        // Check if role has users
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')->with('error', __('messages.role_has_users'));
        }

        $role->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('messages.role_deleted'));
    }

    /**
     * Show form to create a new permission
     */
    public function createPermission()
    {
        return view('roles.create-permission');
    }

    /**
     * Store a new permission
     */
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions,name'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('messages.permission_created'));
    }

    /**
     * Delete a permission
     */
    public function destroyPermission(Permission $permission)
    {
        // Check if permission is used by any role
        if ($permission->roles()->count() > 0) {
            return redirect()->route('roles.index')->with('error', __('messages.permission_used'));
        }

        $permission->delete();
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()->route('roles.index')->with('success', __('messages.permission_deleted'));
    }

    /**
     * Group permissions by category
     */
    private function groupPermissions($permissions)
    {
        $groups = [
            'messages.role_dashboard' => [],
            'messages.role_revenues_payments' => [],
            'messages.role_users' => [],
            'messages.role_management' => [],
            'messages.role_attendance' => [],
            'messages.role_others' => [],
        ];

        foreach ($permissions as $permission) {
            $name = $permission->name;
            
            if (str_starts_with($name, 'view_dashboard') || str_starts_with($name, 'view_all')) {
                $groups['messages.role_dashboard'][] = $permission;
            } elseif (str_contains($name, 'revenue') || str_contains($name, 'payment') || str_contains($name, 'expense') || str_contains($name, 'Purchases')) {
                $groups['messages.role_revenues_payments'][] = $permission;
            } elseif (str_contains($name, 'User') || str_contains($name, 'user') || str_contains($name, 'ban') || str_contains($name, 'Coach') || str_contains($name, 'Manager')) {
                $groups['messages.role_users'][] = $permission;
            } elseif (str_contains($name, 'crud') || str_contains($name, 'Gym') || str_contains($name, 'City') || str_contains($name, 'Training') || str_contains($name, 'Package')) {
                $groups['messages.role_management'][] = $permission;
            } elseif (str_contains($name, 'attendance') || str_contains($name, 'Attendance') || str_contains($name, 'Session')) {
                $groups['messages.role_attendance'][] = $permission;
            } else {
                $groups['messages.role_others'][] = $permission;
            }
        }

        // Remove empty groups
        return array_filter($groups, fn($group) => !empty($group));
    }

    /**
     * Get human-readable permission name
     */
    public static function getPermissionLabel($name)
    {
        $key = 'messages.perm_' . $name;
        $label = __($key);
        
        // If translation doesn't exist, return the formatted name
        if ($label === $key) {
            return ucfirst(str_replace('_', ' ', $name));
        }

        return $label;
    }
}
