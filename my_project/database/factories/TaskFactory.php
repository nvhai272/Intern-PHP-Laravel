<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Task::class;
    public function definition(): array
    {
        $taskNames = [
            'Design UI',
            'Setup Database',
            'Implement Login',
            'Write Unit Tests',
            'Deploy to Server',
            'Fix Bugs',
            'Create API Endpoint',
            'Write Documentation',
            'Review Code',
            'Plan Sprint'
        ];
        return [
            'project_id' => Project::inRandomOrder()->first()?->id ?? Project::factory(),
            'name' => $this->faker->randomElement($taskNames),
            'task_status' => $this->faker->randomElement(['1', '2', '3']),
        ];
    }
}
