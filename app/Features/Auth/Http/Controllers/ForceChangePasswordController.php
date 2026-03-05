<?php

namespace App\Features\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class ForceChangePasswordController extends Controller
{
    public function show(Request $request): Response
    {
        return Inertia::render('auth/ForceChangePassword');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        $user->refresh();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('success', 'Password updated.');
        }

        if ($user->isHr()) {
            return redirect()->route('hr.dashboard')->with('success', 'Password updated.');
        }

        return redirect()->route('employee.dashboard')->with('success', 'Password updated.');
    }
}
