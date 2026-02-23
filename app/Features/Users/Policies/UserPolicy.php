<?php

namespace App\Features\Users\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role ?? '', ['admin', 'hr'], true);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $authUser, User $target): bool
    {
        return $authUser->role === 'admin' || $authUser->id === $target->id;
    }

    public function delete(User $authUser, User $target): bool
    {
        return $authUser->role === 'admin' && $authUser->id !== $target->id;
    }
}
