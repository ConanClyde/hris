<?php

use App\Features\Leave\Models\LeaveApplication;

uses()->group('api');

beforeEach(function () {
    $this->integrationKey = config('services.integration_api_key', 'test-integration-key');
    $this->headers = ['X-Integration-Key' => $this->integrationKey];
});

test('leave api index requires integration key', function () {
    $response = $this->getJson('/api/v1/leave-applications');
    $response->assertStatus(401);
});

test('leave api index returns paginated leave applications', function () {
    LeaveApplication::factory(3)->create();

    $response = $this->getJson('/api/v1/leave-applications', $this->headers);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'current_page',
            'per_page',
            'total',
        ]);
    expect($response->json('data'))->toHaveCount(3);
});

test('leave api store creates leave application', function () {
    $payload = [
        'employee_id' => 'EMP001',
        'employee_name' => 'John Doe',
        'type' => 'Vacation Leave',
        'date_from' => now()->addDays(1)->toDateString(),
        'date_to' => now()->addDays(3)->toDateString(),
        'total_days' => 2.5,
        'reason' => 'Family vacation',
    ];

    $response = $this->postJson('/api/v1/leave-applications', $payload, $this->headers);

    $response->assertStatus(201)
        ->assertJsonPath('employee_id', 'EMP001')
        ->assertJsonPath('employee_name', 'John Doe')
        ->assertJsonPath('type', 'Vacation Leave')
        ->assertJsonPath('status', 'pending');

    $this->assertDatabaseHas('leave_applications', [
        'employee_id' => 'EMP001',
        'type' => 'Vacation Leave',
    ]);
});

test('leave api update status changes status', function () {
    $leave = LeaveApplication::factory()->pending()->create();

    $response = $this->putJson("/api/v1/leave-applications/{$leave->id}/status", [
        'status' => 'approved',
    ], $this->headers);

    $response->assertStatus(200)
        ->assertJsonFragment(['status' => 'approved']);

    expect($leave->fresh()->status)->toBe('approved');
});
