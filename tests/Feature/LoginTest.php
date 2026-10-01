<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;

class LoginTest extends TestCase
{
    // use RefreshDatabase; // SUPPRIMÉ pour éviter de vider la base de données de production

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
    }

    /** @test */
    public function admin_can_login()
    {
        $admin = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $admin->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => '123456',
            'website_url' => '', // Empty honeypot
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($admin);
    }

    /** @test */
    public function user_cannot_login_to_admin_panel()
    {
        $user = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $user->assignRole('user');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => '123456',
            'website_url' => '', // Empty honeypot
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function city_manager_can_login()
    {
        $cityManager = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $cityManager->assignRole('cityManager');

        $response = $this->post('/login', [
            'email' => $cityManager->email,
            'password' => '123456',
            'website_url' => '',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($cityManager);
    }

    /** @test */
    public function gym_manager_can_login()
    {
        $gymManager = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $gymManager->assignRole('gymManager');

        $response = $this->post('/login', [
            'email' => $gymManager->email,
            'password' => '123456',
            'website_url' => '',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($gymManager);
    }

    /** @test */
    public function coach_can_login()
    {
        $coach = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $coach->assignRole('coach');

        $response = $this->post('/login', [
            'email' => $coach->email,
            'password' => '123456',
            'website_url' => '',
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($coach);
    }

    /** @test */
    public function bot_is_detected_by_honeypot()
    {
        $admin = User::factory()->create([
            'city_id' => null,
            'gym_id' => null,
        ]);
        $admin->assignRole('admin');

        $response = $this->post('/login', [
            'email' => $admin->email,
            'password' => '123456',
            'website_url' => 'http://malicious-site.com', // Filled honeypot
        ]);

        $response->assertStatus(422);
        $this->assertGuest();
    }
}
