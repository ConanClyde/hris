<?php

namespace App\Features\Auth\Http\Controllers;

use App\Events\AvatarUpdated;
use App\Events\UserIdentityUpdated;
use App\Features\ActivityLogs\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    public function show(Request $request): Response
    {
        $user = Auth::user();
        $routeName = $request->route()->getName() ?? '';

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'username' => $user->username,
            'first_name' => $user->first_name,
            'middle_name' => $user->middle_name,
            'last_name' => $user->last_name,
            'name_extension' => $user->name_extension,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
            'created_at' => $user->created_at?->toISOString(),
            'avatar' => $user->avatar
                ? asset('storage/'.$user->avatar).'?v='.$user->updated_at?->timestamp
                : null,
        ];

        $activityLogs = ActivityLog::query()
            ->where('actor_user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(15)
            ->get()
            ->map(fn (ActivityLog $log) => [
                'id' => $log->id,
                'action' => $log->action,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'created_at' => $log->created_at?->toISOString(),
            ]);

        $userData['activity_logs'] = $activityLogs;

        if (str_starts_with($routeName, 'admin.')) {
            return Inertia::render('Admin/Profile/Index', ['user' => $userData]);
        }
        if (str_starts_with($routeName, 'hr.')) {
            return Inertia::render('HR/Profile/Index', ['user' => $userData]);
        }

        return Inertia::render('Employee/Profile/Index', ['user' => $userData]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'name_extension' => 'nullable|string|max:50',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'avatar' => 'nullable|file|mimetypes:image/jpeg,image/png,image/gif,image/webp|max:2048',
            'remove_avatar' => 'nullable|boolean',
        ]);

        $firstName = $validated['first_name'] ?? '';
        $lastName = $validated['last_name'] ?? '';
        $name = trim($firstName.' '.$lastName) ?: $user->name;

        $user->update([
            'first_name' => $validated['first_name'] ?? null,
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'] ?? null,
            'name_extension' => $validated['name_extension'] ?? null,
            'email' => $validated['email'],
            'name' => $name,
        ]);

        broadcast(new UserIdentityUpdated($user->refresh()))->toOthers();

        if (! empty($validated['remove_avatar']) && $user->avatar) {
            Storage::disk('public')->delete($user->avatar);
            $user->update(['avatar' => null]);
            AvatarUpdated::dispatch($user, null, 'removed');
        }

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
            $avatarUrl = asset('storage/'.$path).'?v='.$user->updated_at?->timestamp;
            AvatarUpdated::dispatch($user, $avatarUrl, 'updated');
        }

        $employee = $user->employee;
        if ($employee) {
            $employee->update([
                'first_name' => $validated['first_name'] ?? $employee->first_name,
                'middle_name' => $validated['middle_name'] ?? $employee->middle_name,
                'last_name' => $validated['last_name'] ?? $employee->last_name,
                'name_extension' => $validated['name_extension'] ?? $employee->name_extension,
                'email' => $validated['email'],
            ]);
        }

        return redirect()->back()->with('success', 'Profile updated.');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (! Hash::check($validated['current_password'], $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->back()->with('success', 'Password changed.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::user();

        if (! Hash::check($request->password, $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Incorrect password.']);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
