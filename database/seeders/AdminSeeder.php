<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        #=======================================================================================#
        #			                   To add admin from seeders                               	#
        #=======================================================================================#
        $admin = User::firstOrCreate([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('123456')
        ]);
        $admin->assignRole('admin');

        $admona = User::firstOrCreate([
            'name' => 'admona',
            'email' => 'admona@admin.com',
            'gender' => 'female',
            'password' => bcrypt('123456')
        ]);
        $admona->assignRole('admin');
    }
}
