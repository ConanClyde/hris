<?php

namespace App\Features\Users\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminOrHr();
    }

    public function create(User $user): bool
    {
        return $user->isAdminOrHr();
    }

    public function update(User $authUser, User $target): bool
    {
        return $authUser->isAdmin() || $authUser->id === $target->id;
    }

    public function delete(User $authUser, User $target): bool
    {
        return $authUser->isAdmin() && $authUser->id !== $target->id;
    }
}
