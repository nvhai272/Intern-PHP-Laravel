<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\EmployeeTask;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmployeeTask>
 */
class EmployeeTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = EmployeeTask::class;
    public function definition(): array
    {
        return [
            'employee_id' => Employee::inRandomOrder()->first()->id ?? Employee::factory(),
            'task_id' => Task::inRandomOrder()->first()->id ?? Task::factory(),
        ];
    }
}
