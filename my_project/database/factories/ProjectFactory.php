<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Project::class;
    public function definition(): array
    {
        $projectNames = [
            'HR Management System',
            'Inventory App',
            'Customer Portal',
            'E-Commerce Platform',
            'Marketing Dashboard',
            'Project Tracker',
            'Sales CRM',
            'Task Manager',
            'ERP System',
            'Finance Analytics'
        ];
        return [
            'name' => $this->faker->unique()->randomElement($projectNames),
        ];
    }
}
