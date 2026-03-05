<?php

namespace App\Features\Leave\Enums;

enum LeaveType: string
{
    case Vacation = 'Vacation Leave';
    case Sick = 'Sick Leave';
    case Wellness = 'Wellness Leave';
    case SpecialLeavePrivileges = 'Special Leave Privileges';
    case MandatoryForced = 'Mandatory/Forced Leave';
    case Maternity = 'Maternity Leave';
    case Paternity = 'Paternity Leave';
    case SoloParent = 'Solo Parent Leave';
    case Study = 'Study Leave';
    case LeaveWithoutPay = 'Leave Without Pay (LWOP)';

    public static function labels(): array
    {
        return array_column(self::cases(), 'value');
    }
}
