<?php

namespace App\Features\Notices\Enums;

enum NoticeType: string
{
    case Info = 'info';
    case Success = 'success';
    case Warning = 'warning';
    case Danger = 'danger';
}
