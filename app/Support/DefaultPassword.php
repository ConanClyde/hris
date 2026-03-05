<?php

namespace App\Support;

class DefaultPassword
{
    public static function forCurrentYear(): string
    {
        return 'iloveTRC'.date('Y');
    }
}
