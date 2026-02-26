<?php

namespace Tests\Feature;

use App\Features\Users\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerformanceBudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_me_responds_within_budget(): void
    {
        $user = User::factory()->create([
            'role' => 'admin',
        ]);

        $start = microtime(true);

        $response = $this->actingAs($user)->withSession([
            'user_id' => $user->id,
            'role' => 'admin',
        ])->get('/api/me', [
            'Accept' => 'application/json',
        ]);

        $elapsedMs = (microtime(true) - $start) * 1000;

        $response->assertOk();

        $budgetMs = 1000;
        $this->assertLessThan(
            $budgetMs,
            $elapsedMs,
            'Expected /api/me to respond within '.$budgetMs.'ms, got '.(int) $elapsedMs.'ms'
        );
    }
}
