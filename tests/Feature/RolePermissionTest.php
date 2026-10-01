<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        
        $this->admin = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $this->admin->assignRole('admin');
    }

    /** @test */
    public function only_admin_can_access_role_management()
    {
        $user = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $user->assignRole('user');

        $response = $this->actingAs($user)->get(route('roles.index'));
        $response->assertStatus(403);

        $response = $this->actingAs($this->admin)->get(route('roles.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function admin_can_clone_a_role()
    {
        $role = Role::findByName('cityManager');
        $permissionCount = $role->permissions()->count();

        $response = $this->actingAs($this->admin)->post(route('roles.clone', $role->id));
        
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $clonedRole = Role::where('name', 'like', 'cityManager_copy_%')->first();
        $this->assertNotNull($clonedRole);
        $this->assertEquals($permissionCount, $clonedRole->permissions()->count());
    }

    /** @test */
    public function admin_cannot_delete_system_roles()
    {
        $role = Role::findByName('admin');
        
        $response = $this->actingAs($this->admin)->delete(route('roles.destroy', $role->id));
        
        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('roles', ['name' => 'admin']);
    }

    /** @test */
    public function admin_can_view_users_of_a_role()
    {
        $role = Role::findByName('gymManager');
        
        $city = \App\Models\City::create(['name' => 'Test City']);
        $gym = \App\Models\Gym::create(['name' => 'Test Gym', 'city_id' => $city->id]);
        
        $user = User::factory()->create([
            'city_id' => $city->id,
            'gym_id' => $gym->id,
        ]);
        $user->assignRole('gymManager');

        $response = $this->actingAs($this->admin)->get(route('roles.users', $role->id));
        
        $response->assertStatus(200);
        $response->assertSee($user->name);
    }
}
