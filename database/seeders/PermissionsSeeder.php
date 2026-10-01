<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #=======================================================================================#
        #			               Reset cached roles and permissions                          	#
        #=======================================================================================#
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \Illuminate\Support\Facades\DB::table('role_has_permissions')->truncate();
        // \Illuminate\Support\Facades\DB::table('model_has_roles')->truncate();
        \Illuminate\Support\Facades\DB::table('model_has_permissions')->truncate();
        \Illuminate\Support\Facades\DB::table('roles')->truncate();
        \Illuminate\Support\Facades\DB::table('permissions')->truncate();
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        #=======================================================================================#
        #			                         create permissions                              	#
        #=======================================================================================#
        // Base permissions
        $permissions = [
            'login', 'updateProfile', 'showTrainingSessions', 'showAttendanceHistory',
            'attendTrainingSession', 'showRemainingSessions', 'crudUser', 'banUsers',
            'assignCoach', 'buyPackageForUser', 'crudCoach', 'banCoach',
            'showPurchasesHistory', 'showRevenue', 'crudAttendance', 'crudTrainingSessions',
            'crudGym', 'banGymManager', 'crudGymManager', 'crudCity',
            'banCityManager', 'crudCityManager', 'crudTrainingPackage',
            'view_dashboard', 'view_revenue_total', 'view_revenue_city', 'view_revenue_gym',
            'view_payments', 'view_attendance_stats', 'view_all_cities', 'view_all_gyms',
            'view_all_coaches', 'view_all_users', 'manage_expenses'
        ];

        $permissionIds = [];
        foreach ($permissions as $name) {
            $permissionIds[$name] = \Illuminate\Support\Facades\DB::table('permissions')->insertGetId([
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        #=======================================================================================#
        #			                            create roles                                  	#
        #=======================================================================================#
        $roles = ['admin', 'cityManager', 'gymManager', 'coach', 'user'];
        $roleIds = [];
        foreach ($roles as $name) {
            $roleIds[$name] = \Illuminate\Support\Facades\DB::table('roles')->insertGetId([
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        #=======================================================================================#
        #			                      give admin permissions                             	#
        #=======================================================================================#
        $adminPermissions = $permissions; // Admin gets everything
        foreach ($adminPermissions as $name) {
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionIds[$name],
                'role_id' => $roleIds['admin'],
            ]);
        }

        #=======================================================================================#
        #			                    give city Manager permissions                           #
        #=======================================================================================#
        $cityManagerPermissions = [
            'login', 'updateProfile', 'showTrainingSessions', 'crudUser', 'banUsers',
            'assignCoach', 'buyPackageForUser', 'crudCoach', 'banCoach',
            'showPurchasesHistory', 'showRevenue', 'crudAttendance', 'crudTrainingSessions',
            'crudGym', 'banGymManager', 'crudGymManager',
            'view_dashboard', 'view_revenue_city', 'view_payments', 'view_attendance_stats',
            'view_all_gyms', 'view_all_coaches', 'view_all_users', 'manage_expenses'
        ];
        foreach ($cityManagerPermissions as $name) {
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionIds[$name],
                'role_id' => $roleIds['cityManager'],
            ]);
        }

        #=======================================================================================#
        #			                    give gym Manager permissions                            #
        #=======================================================================================#
        $gymManagerPermissions = [
            'login', 'updateProfile', 'showTrainingSessions', 'crudUser', 'banUsers',
            'assignCoach', 'buyPackageForUser', 'crudCoach', 'banCoach',
            'showPurchasesHistory', 'showRevenue', 'crudAttendance', 'crudTrainingSessions',
            'view_dashboard', 'view_revenue_gym', 'view_payments', 'view_attendance_stats',
            'view_all_coaches', 'view_all_users', 'manage_expenses'
        ];
        foreach ($gymManagerPermissions as $name) {
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionIds[$name],
                'role_id' => $roleIds['gymManager'],
            ]);
        }

        #=======================================================================================#
        #			                        give coach permissions                              #
        #=======================================================================================#
        $coachPermissions = [
            'login', 'updateProfile', 'showTrainingSessions', 'showAttendanceHistory',
            'view_dashboard', 'view_attendance_stats'
        ];
        foreach ($coachPermissions as $name) {
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionIds[$name],
                'role_id' => $roleIds['coach'],
            ]);
        }

        #=======================================================================================#
        #			                        give user permissions                               #
        #=======================================================================================#
        $userPermissions = [
            'login', 'updateProfile', 'showTrainingSessions', 'showAttendanceHistory',
            'attendTrainingSession', 'showRemainingSessions'
        ];
        foreach ($userPermissions as $name) {
            \Illuminate\Support\Facades\DB::table('role_has_permissions')->insert([
                'permission_id' => $permissionIds[$name],
                'role_id' => $roleIds['user'],
            ]);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
