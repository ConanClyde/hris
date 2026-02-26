<?php

namespace App\Features\Notices\Policies;

use App\Features\Notices\Models\Notice;
use App\Models\User;

class NoticePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminOrHr();
    }

    public function create(User $user): bool
    {
        return $user->isAdminOrHr();
    }

    public function update(User $user, Notice $notice): bool
    {
        return $user->isAdminOrHr();
    }

    public function delete(User $user, Notice $notice): bool
    {
        return $user->isAdminOrHr();
    }
}
