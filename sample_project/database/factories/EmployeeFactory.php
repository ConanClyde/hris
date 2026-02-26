<?php

namespace Database\Factories;

use App\Features\Employees\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional()->firstName(),
            'last_name' => fake()->lastName(),
            'name_extension' => null,
            'email' => fake()->unique()->safeEmail(),
            'position' => fake()->jobTitle(),
            'classification' => fake()->randomElement(['Permanent', 'Casual', 'Contractual']),
            'date_hired' => fake()->date(),
            'division' => fake()->word(),
            'section' => fake()->word(),
            'status' => 'active',
        ];
    }
}
