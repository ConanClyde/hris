<?php

use App\Models\User;
use App\Support\BadgeMetrics;

test('badge metrics no longer include notices_unread', function () {
    $user = User::factory()->create(['role' => 'admin']);

    $counts = BadgeMetrics::forUser($user);

    expect($counts)->not->toHaveKey('notices_unread');
});

test('active notices endpoint is backwards compatible and returns an empty list', function () {
    config()->set('services.integration_api_key', 'test-key');

    $this->withHeader('X-Integration-Key', 'test-key')
        ->getJson('/api/v1/notices/active')
        ->assertOk()
        ->assertExactJson([]);
});
