<?php

namespace App\Features\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function revokeSession(Request $request)
    {
        $validated = $request->validate([
            'session_id' => ['required', 'string'],
        ]);

        $revoked = session('revoked_sessions', []);
        $revoked[] = $validated['session_id'];
        session(['revoked_sessions' => array_values(array_unique($revoked))]);

        return back()->with('success', 'Session revoked.');
    }
}
