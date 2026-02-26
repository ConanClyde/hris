<?php

namespace App\Features\Auth\Http\Controllers;

use App\Features\Auth\Events\ProfileUpdated;
use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['nullable', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'surname' => ['nullable', 'string', 'max:255'],
            'name_extension' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,webp', 'max:10240'],
        ]);

        $user = Auth::user();
        if ($user) {
            $name = trim(($validated['first_name'] ?? '').' '.($validated['surname'] ?? ''));
            if ($name !== '') {
                $user->update(['name' => $name]);
            }
            if (! empty($validated['email'])) {
                $user->update(['email' => $validated['email']]);
            }

            $employee = Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $employee->update([
                    'first_name' => $validated['first_name'] ?? $employee->first_name,
                    'middle_name' => $validated['middle_name'] ?? $employee->middle_name,
                    'last_name' => $validated['surname'] ?? $employee->last_name,
                    'name_extension' => $validated['name_extension'] ?? $employee->name_extension,
                    'email' => $validated['email'] ?? $employee->email,
                ]);
            }
        }

        if (isset($validated['first_name'])) {
            session(['first_name' => $validated['first_name']]);
        }
        if (isset($validated['middle_name'])) {
            session(['middle_name' => $validated['middle_name']]);
        }
        if (isset($validated['surname'])) {
            session(['surname' => $validated['surname']]);
        }
        if (isset($validated['name_extension'])) {
            session(['name_extension' => $validated['name_extension']]);
        }
        if (isset($validated['email'])) {
            session(['email' => $validated['email']]);
        }

        if ($request->boolean('remove_avatar')) {
            $oldAvatar = session('avatar');
            if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
            session()->forget('avatar');
        } elseif ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $oldAvatar = session('avatar');
            if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
                Storage::disk('public')->delete($oldAvatar);
            }
            $path = $file->store('avatars', 'public');
            session(['avatar' => $path]);
        }

        $userId = Auth::id();
        if ($userId) {
            Cache::forget('sidebar_profile_'.$userId);
            $this->broadcastProfileUpdated($userId);
        }

        $role = session('role');
        if (! $role) {
            $user = Auth::user();
            $role = $user ? $user->role : 'employee';
        }
        $profileRoute = $role === 'admin' ? 'admin.profile' : ($role === 'hr' ? 'hr.profile' : 'employee.profile');

        return redirect()->route($profileRoute)->with('success', 'Profile updated.');
    }

    private function broadcastProfileUpdated(int $userId): void
    {
        $user = Auth::user() ?? User::with('employee')->find($userId);
        $displayName = 'User';
        $email = session('email', '');
        $initial = 'U';
        $avatarUrl = null;

        if ($user) {
            $emp = $user->employee;
            if ($emp && trim($emp->first_name.' '.$emp->last_name)) {
                $displayName = trim($emp->first_name.' '.$emp->last_name);
            } elseif ($user->name) {
                $displayName = $user->name;
            } else {
                $displayName = (string) $userId;
            }
            $email = $email ?: $user->email;
            $initial = strtoupper(mb_substr($displayName, 0, 1)) ?: 'U';
            $avatarPath = session('avatar');
            $avatarUrl = $avatarPath ? asset('storage/'.$avatarPath) : null;
        }

        $profile = [
            'display_name' => $displayName,
            'email' => $email,
            'initial' => $initial,
            'avatar_url' => $avatarUrl,
        ];

        event(new ProfileUpdated($userId, $profile));
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return redirect()->route('admin.profile')->with('success', 'Password updated.');
    }

    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        $user = Auth::user();
        if (! $user) {
            return redirect()->route('login');
        }

        if (! Hash::check($validated['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'The password is incorrect.',
            ]);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('login')->with('success', 'Account deleted.');
    }
}
