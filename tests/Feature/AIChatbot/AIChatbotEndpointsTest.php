<?php

declare(strict_types=1);

use App\Features\Users\Enums\UserRole;
use App\Models\User;

it('requires authentication for ai chatbot chat endpoint', function (): void {
    $this->postJson(route('ai-chatbot.chat'), ['message' => 'Hello'])
        ->assertStatus(401);
});

it('allows authenticated user to call ai chatbot chat endpoint', function (): void {
    $user = User::factory()->create(['role' => UserRole::Employee->value]);

    $this->actingAs($user)
        ->postJson(route('ai-chatbot.chat'), ['message' => 'Hello'])
        ->assertStatus(200)
        ->assertJsonStructure(['response', 'meta']);
});
