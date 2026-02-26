<?php

use App\Events\UserApproved;
use App\Events\UserRejected;
use App\Mail\UserApprovedMail;
use App\Mail\UserRejectedMail;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

test('admin can approve a pending user', function (): void {
    Event::fake([UserApproved::class]);
    Mail::fake();

    $admin = User::factory()->create([
        'role' => 'admin',
        'status' => 'approved',
        'is_active' => true,
    ]);
    $pending = User::factory()->create([
        'role' => 'employee',
        'status' => 'pending',
        'is_active' => false,
    ]);

    $this->actingAs($admin);

    $response = $this->patch(route('admin.users.approve', $pending->id));
    $response->assertRedirect();

    expect($pending->refresh()->status)->toBe('approved');
    expect($pending->is_active)->toBeTrue();

    Event::assertDispatched(UserApproved::class);
    Mail::assertQueued(UserApprovedMail::class, fn (UserApprovedMail $mail) => $mail->user->is($pending));
});

test('admin can reject a pending user', function (): void {
    Event::fake([UserRejected::class]);
    Mail::fake();

    $admin = User::factory()->create([
        'role' => 'admin',
        'status' => 'approved',
        'is_active' => true,
    ]);
    $pending = User::factory()->create([
        'role' => 'employee',
        'status' => 'pending',
        'is_active' => false,
    ]);

    $this->actingAs($admin);

    $response = $this->patch(route('admin.users.reject', $pending->id));
    $response->assertRedirect();

    expect($pending->refresh()->status)->toBe('rejected');
    expect($pending->is_active)->toBeFalse();

    Event::assertDispatched(UserRejected::class);
    Mail::assertQueued(UserRejectedMail::class, fn (UserRejectedMail $mail) => $mail->user->is($pending));
});

test('approve and reject only work for pending users', function (): void {
    Event::fake([UserApproved::class, UserRejected::class]);
    Mail::fake();

    $admin = User::factory()->create([
        'role' => 'admin',
        'status' => 'approved',
        'is_active' => true,
    ]);
    $approvedUser = User::factory()->create([
        'role' => 'employee',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $this->actingAs($admin);

    $this->patch(route('admin.users.approve', $approvedUser->id))
        ->assertRedirect()
        ->assertSessionHas('error');

    $this->patch(route('admin.users.reject', $approvedUser->id))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect($approvedUser->refresh()->status)->toBe('approved');

    Event::assertNotDispatched(UserApproved::class);
    Event::assertNotDispatched(UserRejected::class);
    Mail::assertNothingSent();
});

test('hr can approve a pending employee but cannot approve a pending admin', function (): void {
    Event::fake([UserApproved::class]);
    Mail::fake();

    $hr = User::factory()->create([
        'role' => 'hr',
        'status' => 'approved',
        'is_active' => true,
    ]);

    $pendingEmployee = User::factory()->create([
        'role' => 'employee',
        'status' => 'pending',
        'is_active' => false,
    ]);
    $pendingAdmin = User::factory()->create([
        'role' => 'admin',
        'status' => 'pending',
        'is_active' => false,
    ]);

    $this->actingAs($hr);

    $this->patch(route('hr.users.approve', $pendingEmployee->id))
        ->assertRedirect();

    expect($pendingEmployee->refresh()->status)->toBe('approved');
    expect($pendingEmployee->is_active)->toBeTrue();

    Mail::assertQueued(UserApprovedMail::class, fn (UserApprovedMail $mail) => $mail->user->is($pendingEmployee));

    Mail::fake();
    Event::fake([UserApproved::class]);

    $this->patch(route('hr.users.approve', $pendingAdmin->id))
        ->assertRedirect()
        ->assertSessionHas('error');

    expect($pendingAdmin->refresh()->status)->toBe('pending');
    expect($pendingAdmin->is_active)->toBeFalse();

    Event::assertNotDispatched(UserApproved::class);
    Mail::assertNothingSent();
});
