<?php

namespace App\Features\Notifications\Http\Controllers;

use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function index()
    {
        $notices = Notice::active()
            ->latest()
            ->paginate(10);

        return view('features.notifications.admin.index', compact('notices'));
    }
}
