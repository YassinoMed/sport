<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CoachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coaches = User::factory(60)->create();

        // give them role => coach
        foreach ($coaches as $coach) {
            $coach->assignRole('coach');
        }
    }
}
