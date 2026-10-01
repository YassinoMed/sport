<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $citiesManagers = User::factory(24)->create();

        // give them role => cityManager
        foreach ($citiesManagers as $cityManager) {
            $cityManager->assignRole('cityManager');
        }
    }
}
