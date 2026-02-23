<?php

namespace Database\Factories;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Features\Leave\Models\LeaveApplication>
 */
class LeaveApplicationFactory extends Factory
{
    protected $model = LeaveApplication::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateFrom = fake()->dateTimeBetween('-30 days', 'now');
        $dateTo = fake()->dateTimeBetween($dateFrom, '+7 days');
        $employeeId = (string) fake()->randomNumber(5);
        $employeeName = fake()->name();

        return [
            'employee_id' => $employeeId,
            'employee_name' => $employeeName,
            'type' => fake()->randomElement(['Vacation Leave', 'Sick Leave', 'Emergency Leave']),
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'total_days' => fake()->randomFloat(1, 0.5, 5),
            'reason' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }

    public function pending(): static
    {
        return $this->state(['status' => 'pending']);
    }

    public function approved(): static
    {
        return $this->state(['status' => 'approved']);
    }
}
