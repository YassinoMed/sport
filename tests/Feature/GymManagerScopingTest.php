<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\City;
use App\Models\Gym;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GymManagerScopingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $suffix = uniqid('', true);
        
        // Setup roles and permissions
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        
        // Create cities
        $this->city1 = City::create(['name' => 'City 1']);
        $this->city2 = City::create(['name' => 'City 2']);
        
        // Create gyms
        $this->gym1 = Gym::create(['name' => 'Gym 1', 'city_id' => $this->city1->id]);
        $this->gym2 = Gym::create(['name' => 'Gym 2', 'city_id' => $this->city2->id]);
        
        // Create an Admin
        $this->admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => "admin+{$suffix}@test.com",
            'city_id' => null,
            'gym_id' => null,
        ]);
        $this->admin->assignRole('admin');
        
        // Create City Manager for City 1
        $this->cityManager = User::factory()->create([
            'name' => 'City Manager',
            'email' => "citymanager+{$suffix}@test.com",
            'city_id' => $this->city1->id,
            'gym_id' => null,
        ]);
        $this->cityManager->assignRole('cityManager');
        
        // Create Gym Manager for Gym 1 (in City 1)
        $this->gymManager1 = User::factory()->create([
            'name' => 'Gym Manager 1',
            'email' => "gymmanager1+{$suffix}@test.com",
            'city_id' => $this->city1->id,
            'gym_id' => $this->gym1->id,
        ]);
        $this->gymManager1->assignRole('gymManager');
        
        // Create Gym Manager for Gym 2 (in City 2)
        $this->gymManager2 = User::factory()->create([
            'name' => 'Gym Manager 2',
            'email' => "gymmanager2+{$suffix}@test.com",
            'city_id' => $this->city2->id,
            'gym_id' => $this->gym2->id,
        ]);
        $this->gymManager2->assignRole('gymManager');
    }

    /** @test */
    public function admin_can_see_all_gym_managers()
    {
        $response = $this->actingAs($this->admin)->get(route('gymManager.list'));
        
        $response->assertStatus(200);
        $response->assertSee($this->gymManager1->name);
        $response->assertSee($this->gymManager2->name);
    }

    /** @test */
    public function city_manager_can_only_see_gym_managers_in_their_city()
    {
        $response = $this->actingAs($this->cityManager)->get(route('gymManager.list'));
        
        $response->assertStatus(200);
        $response->assertSee($this->gymManager1->name);
        $response->assertDontSee($this->gymManager2->name);
    }

    /** @test */
    public function gym_manager_can_only_see_themselves()
    {
        $response = $this->actingAs($this->gymManager1)->get(route('gymManager.list'));
        
        $response->assertStatus(200);
        $response->assertSee($this->gymManager1->name);
        $response->assertDontSee($this->gymManager2->name);
    }

    /** @test */
    public function gym_manager_cannot_edit_another_gym_manager()
    {
        $response = $this->actingAs($this->gymManager1)->get(route('gymManager.edit', $this->gymManager2->id));
        
        $response->assertStatus(403);
    }

    /** @test */
    public function city_manager_cannot_edit_gym_manager_from_another_city()
    {
        $response = $this->actingAs($this->cityManager)->get(route('gymManager.edit', $this->gymManager2->id));
        
        $response->assertStatus(403);
    }
}
