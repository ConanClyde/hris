<?php

use App\Events\AvatarUpdated;
use App\Events\LeaveApproved;
use App\Events\LeaveCancelled;
use App\Events\LeaveRejected;
use App\Events\LeaveSubmitted;
use App\Events\NotificationsUnreadCountUpdated;
use App\Events\PostCommentCreated;
use App\Events\PostCreated;
use App\Events\PostReactionUpdated;
use App\Events\UserIdentityUpdated;
use App\Features\Calendar\Events\CustomHolidayCreated;
use App\Features\Calendar\Events\CustomHolidayDeleted;
use App\Features\Calendar\Events\CustomHolidayUpdated;
use App\Features\Employees\Models\Employee;
use App\Features\Leave\Models\LeaveApplication;
use App\Features\Pds\Events\PdsStatusUpdated;
use App\Features\Training\Events\TrainingStatusUpdated;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

test('SystemNotification uses broadcast channel', function () {
    expect(\App\Notifications\SystemNotification::class)->toImplement(ShouldBroadcast::class);
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
        expect($event)->toBeInstanceOf(ShouldBroadcast::class);

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

    expect($event)->toBeInstanceOf(ShouldBroadcast::class);

    $channels = collect($event->broadcastOn())->map(
        fn ($channel) => method_exists($channel, 'name') ? $channel->name : (string) $channel
    );

    expect($channels)->toContain('private-App.Models.User.10');
    expect($event->broadcastAs())->toBe('notifications.unread.updated');
    expect($event->broadcastWith())->toBe(['count' => 5]);
});

test('UserIdentityUpdated broadcasts to user, dashboards, and employees channels with minimal payload', function () {
    $user = User::factory()->create();

    $event = new UserIdentityUpdated($user);

    expect($event)->toBeInstanceOf(ShouldBroadcast::class);

    $channels = collect($event->broadcastOn())->map(
        fn ($channel) => method_exists($channel, 'name') ? $channel->name : (string) $channel
    );

    expect($channels)->toContain('private-App.Models.User.'.$user->id);
    expect($channels)->toContain('private-admin.dashboard');
    expect($channels)->toContain('private-hr.dashboard');
    expect($channels)->toContain('private-employees');

    $payload = $event->broadcastWith();

    expect($payload)->toHaveKeys(['user', 'type', 'timestamp']);
    expect($payload['user'])->toHaveKeys([
        'id',
        'name',
        'email',
        'username',
        'role',
        'status',
        'avatar',
    ]);
});

test('Post events broadcast to scoped posts channels with expected payload', function () {
    $user = User::factory()->create();
    $post = \App\Features\Posts\Models\Post::factory()->create([
        'role_scope' => 'hr',
    ]);
    $comment = \App\Features\Posts\Models\PostComment::create([
        'post_id' => $post->id,
        'user_id' => $user->id,
        'body' => 'Test comment',
    ]);

    $created = new PostCreated($post);
    $reaction = new PostReactionUpdated($post, 5, 1, 'like');
    $commentEvent = new PostCommentCreated($post, $comment, 3);

    $createdChannels = collect($created->broadcastOn())->map(fn ($c) => $c->name);
    expect($createdChannels)->toContain('private-posts.hr');

    $reactionChannels = collect($reaction->broadcastOn())->map(fn ($c) => $c->name);
    expect($reactionChannels)->toContain('private-posts.all');
    expect($reactionChannels)->toContain('private-posts.hr');

    $commentChannels = collect($commentEvent->broadcastOn())->map(fn ($c) => $c->name);
    expect($commentChannels)->toContain('private-posts.all');
    expect($commentChannels)->toContain('private-posts.hr');

    expect($created->broadcastAs())->toBe('post.created');
    expect($reaction->broadcastAs())->toBe('post.reaction.updated');
    expect($commentEvent->broadcastAs())->toBe('post.comment.created');
});

test('AvatarUpdated broadcasts avatar changes on avatar-updates channel', function () {
    $user = User::factory()->create();

    $event = new AvatarUpdated($user, 'avatars/example.png', 'updated');

    $channels = collect($event->broadcastOn())->map(fn ($c) => $c->name);
    expect($channels)->toContain('private-avatar-updates');

    $payload = $event->broadcastWith();
    expect($payload['user_id'])->toBe($user->id);
    expect($payload['avatar'])->toBe('avatars/example.png');
    expect($payload['action'])->toBe('updated');
});

test('holiday events broadcast to calendar and dashboards', function () {
    $holiday = \App\Features\Calendar\Models\CustomHoliday::create([
        'title' => 'Test Holiday',
        'date' => now(),
        'category' => 'regular',
        'description' => null,
        'is_recurring' => false,
    ]);

    $created = new CustomHolidayCreated($holiday);
    $customUpdated = new CustomHolidayUpdated($holiday);
    $deleted = new CustomHolidayDeleted($holiday->id);

    foreach ([$created, $customUpdated] as $event) {
        $channels = collect($event->broadcastOn())->map(fn ($c) => $c->name);
        expect($channels)->toContain('private-calendar.holidays');
    }

    $deleteChannels = collect($deleted->broadcastOn())->map(fn ($c) => $c->name);
    expect($deleteChannels)->toContain('private-calendar.holidays');
});

test('PdsStatusUpdated and TrainingStatusUpdated broadcast management and role channels', function () {
    $pdsEvent = new PdsStatusUpdated(
        id: 1,
        employeeId: 2,
        employeeName: 'Jane Doe',
        status: 'pending',
    );

    $trainingEvent = new TrainingStatusUpdated(
        id: 1,
        employeeId: 'E-1',
        employeeName: 'Jane Doe',
        status: 'assigned',
        title: 'Orientation',
        dateFrom: now()->toDateString(),
        hours: 8.0,
    );

    $pdsChannels = collect($pdsEvent->broadcastOn())->map(fn ($c) => $c->name);
    expect($pdsChannels)->toContain('private-role.hr');
    expect($pdsChannels)->toContain('private-role.employee');
    expect($pdsChannels)->toContain('private-pds.management');

    $trainingChannels = collect($trainingEvent->broadcastOn())->map(fn ($c) => $c->name);
    expect($trainingChannels)->toContain('private-role.hr');
    expect($trainingChannels)->toContain('private-role.employee');
    expect($trainingChannels)->toContain('private-training.management');
});
