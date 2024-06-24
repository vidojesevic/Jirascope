<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            'name' => 'Frontend Team',
            'description' => 'A Frontend Team Lead oversees the development of responsive, user-friendly web applications'
        ]);

        DB::table('teams')->insert([
            'name' => 'Backend Team',
            'description' => 'Backend developers are specialists whose main responsibility is to develop web and desktop products on the server side.'
        ]);

        DB::table('teams')->insert([
            'name' => 'DevOps Team',
            'description' => 'A DevOps engineer designs, implements, and maintains tools and processes for continuous integration, delivery, and deployment of software.'
        ]);
    }
}
