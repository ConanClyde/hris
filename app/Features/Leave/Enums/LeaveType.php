<?php

namespace App\Features\Leave\Enums;

enum LeaveType: string
{
    case Vacation = 'Vacation Leave';
    case Sick = 'Sick Leave';
    case Emergency = 'Emergency Leave';
    case Maternity = 'Maternity Leave';
    case Paternity = 'Paternity Leave';
    case Other = 'Other';

    public static function labels(): array
    {
        return array_column(self::cases(), 'value');
    }
}
