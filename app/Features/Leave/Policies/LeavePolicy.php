<?php

namespace App\Features\Leave\Policies;

use App\Features\Leave\Models\LeaveApplication;
use App\Models\User;

class LeavePolicy
{
    public function view(User $user, LeaveApplication $leave): bool
    {
        if ($user->isAdminOrHr()) {
            return true;
        }

        $employee = $user->employee;

        if (! $employee) {
            return false;
        }

        if ($leave->employee_fk !== null) {
            return (int) $leave->employee_fk === (int) $employee->id;
        }

        return (string) $leave->employee_id === (string) $employee->id;
    }

    public function update(User $user, LeaveApplication $leave): bool
    {
        if ($user->isAdminOrHr()) {
            return true;
        }

        $employee = $user->employee;

        if (! $employee) {
            return false;
        }

        $matchesEmployee = $leave->employee_fk !== null
            ? (int) $leave->employee_fk === (int) $employee->id
            : (string) $leave->employee_id === (string) $employee->id;

        return $matchesEmployee && $leave->status === 'pending';
    }

    public function delete(User $user, LeaveApplication $leave): bool
    {
        return $this->update($user, $leave);
    }
}
