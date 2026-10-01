<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GymsManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $gymsManagers = User::factory(40)->create();

        // give them role => gymManager
        foreach ($gymsManagers as $gymManager) {
            $gymManager->assignRole('gymManager');
        }
    }
}
