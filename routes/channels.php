<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('users.{userId}', function ($user, string $userId) {
    return (string) $user->id === $userId;
});

Broadcast::channel('role.{role}', function ($user, string $role) {
    return is_string($user->role ?? null) && $user->role === $role;
});

Broadcast::channel('leave.management', function ($user) {
    return ($user->role ?? '') === 'hr';
});

Broadcast::channel('training.management', function ($user) {
    return ($user->role ?? '') === 'hr';
});

Broadcast::channel('calendar.holidays', function ($user) {
    return in_array($user->role ?? '', ['admin', 'hr'], true);
});
