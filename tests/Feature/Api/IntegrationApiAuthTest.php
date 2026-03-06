<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;

it('rejects requests without valid integration key', function (): void {
    Config::set('services.integration_api_key', 'secret-key');

    $this->getJson('/api/v1/leave-applications')
        ->assertStatus(401);
});

it('accepts requests with valid integration key', function (): void {
    Config::set('services.integration_api_key', 'secret-key');

    $this->withHeader('X-Integration-Key', 'secret-key')
        ->getJson('/api/v1/leave-applications')
        ->assertStatus(200);
});
