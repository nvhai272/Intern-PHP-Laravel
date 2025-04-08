<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'team_id' => Team::inRandomOrder()->first()?->id,
            'email' => $this->faker->unique()->safeEmail,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'password' => bcrypt('password123'),
            'gender' => $this->faker->randomElement(['1', '2']),
            'birthday' => $this->faker->date(),
            'address' => $this->faker->address,
            'avatar' => $this->faker->imageUrl(128, 128, 'people'),
            'salary' => $this->faker->numberBetween(500, 5000),
            'position' => $this->faker->randomElement(['1', '2', '3', '4', '5']),
            'status' => '1',
            'type_of_work' => $this->faker->randomElement(['1', '2', '3', '4']),
            'ins_id' =>  1,
            'upd_id' => null,
            'del_flag' => '0',
            'ins_datetime' => now(),
            'upd_datetime' => null,
        ];
    }
}
