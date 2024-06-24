<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Milan',
            'surname' => 'Petrovic',
            'email' => 'milan@example.test',
            'password' => bcrypt('password123'),
            'role_id' => 2,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);

        DB::table('users')->insert([
            'name' => 'Janko',
            'surname' => 'Popovic',
            'email' => 'janko@example.test',
            'password' => bcrypt('password123'),
            'role_id' => 1,
            'created_at' => now(),
            'email_verified_at' => now()
        ]);
    }
}
