<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('role.{role}', function (User $user, string $role) {
    return is_string($user->role ?? null) && $user->role === $role;
});

Broadcast::channel('leave.management', function (User $user) {
    return $user->isAdminOrHr();
});

Broadcast::channel('training.management', function (User $user) {
    return $user->isAdminOrHr();
});

Broadcast::channel('pds.management', function (User $user) {
    return $user->isAdminOrHr();
});

Broadcast::channel('calendar.holidays', function (User $user) {
    return $user->isAdminOrHr();
});

// Admin dashboard channel
Broadcast::channel('admin.dashboard', function (User $user) {
    return $user->isAdmin();
});

// HR dashboard channel
Broadcast::channel('hr.dashboard', function (User $user) {
    return $user->isAdminOrHr();
});

// Employee-wide channel
Broadcast::channel('employees', function (User $user) {
    return true;
});

// Posts / announcements realtime channels
Broadcast::channel('posts.all', function (User $user) {
    return true;
});

Broadcast::channel('posts.hr', function (User $user) {
    return $user->isAdminOrHr();
});

Broadcast::channel('posts.employee', function (User $user) {
    return true;
});

// Avatar updates channel
Broadcast::channel('avatar-updates', function (User $user) {
    return true;
});
