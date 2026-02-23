<?php

namespace App\Features\Notices\Policies;

use App\Features\Notices\Models\Notice;
use App\Models\User;

class NoticePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role ?? '', ['admin', 'hr'], true);
    }

    public function create(User $user): bool
    {
        return in_array($user->role ?? '', ['admin', 'hr'], true);
    }

    public function update(User $user, Notice $notice): bool
    {
        return in_array($user->role ?? '', ['admin', 'hr'], true);
    }

    public function delete(User $user, Notice $notice): bool
    {
        return in_array($user->role ?? '', ['admin', 'hr'], true);
    }
}
