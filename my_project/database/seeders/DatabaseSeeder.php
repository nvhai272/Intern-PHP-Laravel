<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Team;
//use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Tạo 5 team và mỗi team có 10 nhân viên
        $teams = Team::factory(5)->create();

        $teams->each(function ($team) {
            Employee::factory(10)->create(['team_id' => $team->id]);
        });

    }
}
