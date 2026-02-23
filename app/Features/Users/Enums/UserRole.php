<?php

namespace App\Features\Users\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Hr = 'hr';
    case Employee = 'employee';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Hr => 'HR',
            self::Employee => 'Employee',
        };
    }
}
