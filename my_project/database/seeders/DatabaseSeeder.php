<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeProject;
use App\Models\EmployeeTask;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
//use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\TeamProject;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo team & employee
        Team::factory(5)->create()->each(function ($team) {
            Employee::factory(10)->create(['team_id' => $team->id]);
        });

        // Tạo project & task
        Project::factory(10)->create()->each(function ($project) {
            Task::factory(5)->create(['project_id' => $project->id]);
        });

        // Gán employee vào project (N - N)
        for ($i = 0; $i < 50; $i++) {
            EmployeeProject::factory()->create();
        }

        // Gán employee vào task (N - N)
        for ($i = 0; $i < 50; $i++) {
            EmployeeTask::factory()->create();
        }

        // Gán team vào project (N - N)
        for ($i = 0; $i < 20; $i++) {
            TeamProject::factory()->create();
        }

    }
}
