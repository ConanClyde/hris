<?php

namespace App\Features\Notifications\Http\Controllers;

use App\Features\Notices\Models\Notice;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class NotificationController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $notices = Notice::latest()
            ->paginate(10);

        $page = str_starts_with((string) $request->route()?->getName(), 'hr.')
            ? 'HR/Notifications/Index'
            : 'Notifications/Index';

        return Inertia::render($page, [
            'notices' => $notices,
        ]);
    }
}
