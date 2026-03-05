<?php

use App\Events\LeaveApproved;
use App\Events\LeaveCancelled;
use App\Events\LeaveRejected;
use App\Events\LeaveSubmitted;
use App\Events\NotificationsUnreadCountUpdated;
use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

test('SystemNotification still broadcasts immediately', function () {
    expect(\App\Notifications\SystemNotification::class)->toImplement(ShouldBroadcastNow::class);
});

test('leave events broadcast to hr.dashboard and the employees user channel when employee has a user', function () {
    $user = User::factory()->create();
    $employee = Employee::factory()->create([
        'user_id' => $user->id,
        'first_name' => 'Jane',
        'last_name' => 'Doe',
    ]);

    $leave = LeaveApplication::factory()->create([
        'employee_id' => $employee->id,
        'employee_fk' => $employee->id,
        'employee_name' => $employee->full_name,
    ]);

    $events = [
        new LeaveSubmitted($leave),
        new LeaveApproved($leave, 'Manager'),
        new LeaveRejected($leave, 'Manager', 'Reason'),
        new LeaveCancelled($leave),
    ];

    foreach ($events as $event) {
        expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);

        $channels = collect($event->broadcastOn())->map(
            fn ($channel) => method_exists($channel, 'name') ? $channel->name : (string) $channel
        );

        expect($channels)->toContain('private-hr.dashboard');
        expect($channels)->toContain('private-App.Models.User.'.$user->id);
    }
});

test('leave events skip per-user channel when employee is not linked to a user', function () {
    $employee = Employee::factory()->create([
        'user_id' => null,
    ]);

    $leave = LeaveApplication::factory()->create([
        'employee_id' => $employee->id,
        'employee_fk' => $employee->id,
        'employee_name' => $employee->full_name,
    ]);

    $events = [
        new LeaveSubmitted($leave),
        new LeaveApproved($leave, 'Manager'),
        new LeaveRejected($leave, 'Manager', 'Reason'),
        new LeaveCancelled($leave),
    ];

    foreach ($events as $event) {
        $channels = collect($event->broadcastOn())->map(
            fn ($channel) => method_exists($channel, 'name') ? $channel->name : (string) $channel
        );

        expect($channels)->toContain('private-hr.dashboard');
        expect($channels->filter(fn ($name) => str_contains($name, 'App.Models.User.')))->toHaveCount(0);
    }
});

test('NotificationsUnreadCountUpdated broadcasts on user private channel with count payload', function () {
    $event = new NotificationsUnreadCountUpdated(10, 5);

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);

    $channels = collect($event->broadcastOn())->map(
        fn ($channel) => method_exists($channel, 'name') ? $channel->name : (string) $channel
    );

    expect($channels)->toContain('private-App.Models.User.10');
    expect($event->broadcastAs())->toBe('notifications.unread.updated');
    expect($event->broadcastWith())->toBe(['count' => 5]);
});
