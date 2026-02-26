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
    return $user->isAdminOrHr();
});

Broadcast::channel('training.management', function ($user) {
    return $user->isAdminOrHr();
});

Broadcast::channel('pds.management', function ($user) {
    return $user->isAdminOrHr();
});

Broadcast::channel('calendar.holidays', function ($user) {
    return $user->isAdminOrHr();
});

// Admin dashboard channel
Broadcast::channel('admin.dashboard', function ($user) {
    return $user->isAdmin();
});

// HR dashboard channel
Broadcast::channel('hr.dashboard', function ($user) {
    return $user->isAdminOrHr();
});

// Employee-wide channel
Broadcast::channel('employees', function ($user) {
    return auth()->check();
});

// Calendar updates channel
Broadcast::channel('calendar', function ($user) {
    return auth()->check();
});

// Avatar updates channel (public - for real-time avatar sync)
Broadcast::channel('avatar-updates', function ($user) {
    return true; // Public channel, anyone can listen
});
