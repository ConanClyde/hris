<?php

namespace App\Features\Leave\Policies;

use App\Features\Leave\Models\LeaveApplication;
use App\Features\Users\Models\User;

class LeavePolicy
{
    /**
     * HR / Admin can view any leave application.
     * Employees can only view their own.
     */
    public function view(User $user, LeaveApplication $leave): bool
    {
        if (in_array($user->role, ['admin', 'hr'], true)) {
            return true;
        }

        return $leave->employee && $leave->employee->user_id === $user->id;
    }

    /**
     * HR / Admin can update any leave application.
     * Employees can only update their own pending applications.
     */
    public function update(User $user, LeaveApplication $leave): bool
    {
        if (in_array($user->role, ['admin', 'hr'], true)) {
            return true;
        }

        return $leave->employee
            && $leave->employee->user_id === $user->id
            && $leave->status === 'pending';
    }

    /**
     * HR / Admin can delete any leave application.
     * Employees can only delete their own pending applications.
     */
    public function delete(User $user, LeaveApplication $leave): bool
    {
        return $this->update($user, $leave);
    }
}
