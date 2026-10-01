<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear permission cache
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Create new dashboard permissions
        $permissions = [
            'view_dashboard' => 'Voir le tableau de bord',
            'view_revenue_total' => 'Voir le revenu total',
            'view_revenue_city' => 'Voir le revenu de la ville',
            'view_revenue_gym' => 'Voir le revenu de la salle',
            'view_payments' => 'Voir les paiements',
            'view_attendance_stats' => 'Voir les statistiques de présence',
            'view_all_cities' => 'Voir toutes les villes',
            'view_all_gyms' => 'Voir toutes les salles',
            'view_all_coaches' => 'Voir tous les coachs',
            'view_all_users' => 'Voir tous les utilisateurs',
            'manage_expenses' => 'Gérer les dépenses',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        // Assign permissions to roles
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->givePermissionTo([
                'view_dashboard',
                'view_revenue_total',
                'view_revenue_city',
                'view_revenue_gym',
                'view_payments',
                'view_attendance_stats',
                'view_all_cities',
                'view_all_gyms',
                'view_all_coaches',
                'view_all_users',
                'manage_expenses',
            ]);
        }

        $cityManagerRole = Role::where('name', 'cityManager')->first();
        if ($cityManagerRole) {
            $cityManagerRole->givePermissionTo([
                'view_dashboard',
                'view_revenue_city',
                'view_payments',
                'view_attendance_stats',
                'view_all_gyms',
                'view_all_coaches',
                'view_all_users',
                'manage_expenses',
            ]);
        }

        $gymManagerRole = Role::where('name', 'gymManager')->first();
        if ($gymManagerRole) {
            $gymManagerRole->givePermissionTo([
                'view_dashboard',
                'view_revenue_gym',
                'view_payments',
                'view_attendance_stats',
                'view_all_coaches',
                'view_all_users',
                'manage_expenses',
            ]);
        }

        $coachRole = Role::where('name', 'coach')->first();
        if ($coachRole) {
            $coachRole->givePermissionTo([
                'view_dashboard',
                'view_attendance_stats',
            ]);
        }

        // Clear cache again after updates
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view_dashboard',
            'view_revenue_total',
            'view_revenue_city',
            'view_revenue_gym',
            'view_payments',
            'view_attendance_stats',
            'view_all_cities',
            'view_all_gyms',
            'view_all_coaches',
            'view_all_users',
            'manage_expenses',
        ];

        foreach ($permissions as $name) {
            $permission = Permission::where('name', $name)->first();
            if ($permission) {
                $permission->delete();
            }
        }

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
