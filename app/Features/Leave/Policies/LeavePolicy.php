<?php

namespace App\Features\Leave\Policies;

use App\Features\Leave\Models\LeaveApplication;
use App\Models\User;

class LeavePolicy
{
    public function view(User $user, LeaveApplication $leave): bool
    {
        if (in_array($user->role ?? '', ['admin', 'hr'], true)) {
            return true;
        }

        $employee = $user->employee;

        return $employee && (string) $leave->employee_id === (string) $employee->id;
    }

    public function update(User $user, LeaveApplication $leave): bool
    {
        if (in_array($user->role ?? '', ['admin', 'hr'], true)) {
            return true;
        }

        $employee = $user->employee;

        return $employee
            && (string) $leave->employee_id === (string) $employee->id
            && $leave->status === 'pending';
    }

    public function delete(User $user, LeaveApplication $leave): bool
    {
        return $this->update($user, $leave);
    }
}
