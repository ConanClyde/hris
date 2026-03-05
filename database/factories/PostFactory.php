<?php

namespace Database\Factories;

use App\Features\Posts\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Features\Posts\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'body' => $this->faker->paragraphs(3, true),
            'created_by' => \App\Models\User::factory(),
            'role_scope' => $this->faker->randomElement(['all', 'hr', 'employee']),
            'is_pinned' => false,
            'is_published' => true,
        ];
    }
}
