<?php

namespace Database\Factories;

use App\Features\Employees\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Features\Employees\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional()->firstName(),
            'last_name' => fake()->lastName(),
            'name_extension' => null,
            'email' => fake()->unique()->safeEmail(),
            'position' => fake()->jobTitle(),
            'classification' => fake()->randomElement(['Permanent', 'Casual', 'Contractual']),
            'date_hired' => fake()->date(),
            'division' => fake()->optional()->word(),
            'subdivision' => fake()->optional()->word(),
            'section' => fake()->optional()->word(),
            'status' => 'active',
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
            'first_name' => $user->first_name ?? fake()->firstName(),
            'last_name' => $user->last_name ?? fake()->lastName(),
            'email' => $user->email,
        ]);
    }
}
