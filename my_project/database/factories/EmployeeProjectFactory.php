<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeProject;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeProject>
 */
class EmployeeProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = EmployeeProject::class;
    public function definition(): array
    {
        return [
            'employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory(),
        ];
    }
}
