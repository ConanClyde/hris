<?php

namespace Database\Factories;

use App\Features\Notices\Models\Notice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Features\Notices\Models\Notice>
 */
class NoticeFactory extends Factory
{
    protected $model = Notice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'message' => fake()->paragraph(),
            'type' => fake()->randomElement(['info', 'success', 'warning', 'danger']),
            'is_active' => true,
            'expires_at' => fake()->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }
}
