<?php

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;

uses()->group('api');

beforeEach(function () {
    $this->integrationKey = config('services.integration_api_key', 'test-integration-key');
    $this->headers = ['X-Integration-Key' => $this->integrationKey];
});

test('organizational structure api requires integration key', function () {
    $response = $this->getJson('/api/v1/organizational-structure');
    $response->assertStatus(401);
});

test('organizational structure api returns divisions with subdivisions and sections', function () {
    $division = Division::create(['name' => 'Test Division']);
    $subdivision = Subdivision::create([
        'division_id' => $division->id,
        'name' => 'Test Subdivision',
    ]);
    Section::create([
        'division_id' => $division->id,
        'subdivision_id' => $subdivision->id,
        'name' => 'Test Section',
    ]);
    Section::create([
        'division_id' => $division->id,
        'subdivision_id' => null,
        'name' => 'Direct Section',
    ]);

    $response = $this->getJson('/api/v1/organizational-structure', $this->headers);

    $response->assertStatus(200);

    $data = $response->json();
    expect($data)->toBeArray()->toHaveCount(1);
    expect($data[0]['name'])->toBe('Test Division');
    expect($data[0]['subdivisions'])->toHaveCount(1);
    expect($data[0]['subdivisions'][0]['sections'])->toHaveCount(1);
    expect($data[0]['sections'])->toHaveCount(2); // Direct sections + those under subdivisions
});
