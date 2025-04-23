<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Team;
use App\Models\TeamProject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TeamProject>
 */
class TeamProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = TeamProject::class;
    public function definition(): array
    {
        return [
            'team_id' => Team::inRandomOrder()->first()->id ?? Team::factory(),
            'project_id' => Project::inRandomOrder()->first()->id ?? Project::factory(),
        ];
    }
}
