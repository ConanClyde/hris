<?php

namespace App\Features\Auth\Http\Controllers;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['user_id' => $credentials['user_id'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();

            $legacyDomain = config('auth.legacy_domain', '');
            $email = $user->email;
            if (! $email && $legacyDomain) {
                $email = $user->user_id.'@'.$legacyDomain;
            }

            // Store user data in session with separated name components
            session([
                'user_id' => $user->id,
                'role' => $user->role ?? 'employee',
                'email' => $email,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'surname' => $user->last_name,
                'name_extension' => $user->name_extension,
            ]);

            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'user_id' => ['The provided credentials are incorrect.'],
        ]);
    }

    public function register(Request $request, ActivityLogger $activityLogger)
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:Employee,HR,Admin'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'name_extension' => ['nullable', 'string', 'max:50'],
            'sex' => ['required', 'string', 'in:male,female'],
            'date_of_birth' => ['required', 'date', 'before:-18 years'],
            'date_hired' => ['required', 'date', 'before_or_equal:today'],
            'division' => ['required', 'exists:divisions,id'],
            'subdivision' => ['nullable', 'exists:subdivisions,id'],
            'unit_section' => ['required', 'exists:sections,id'],
            'position' => ['required', 'string', 'max:255'],
            'classification' => ['required', 'string', 'max:255'],
            'user_id' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Resolve organizational units
        $division = Division::find($validated['division']);
        $subdivisionId = $validated['subdivision'] ?? null;
        $subdivision = $subdivisionId ? Subdivision::find($subdivisionId) : null;
        $section = Section::find($validated['unit_section']);

        DB::transaction(function () use ($validated, $division, $subdivision, $section, $activityLogger) {
            $middleName = $validated['middle_name'] ?? null;
            $nameExtension = $validated['name_extension'] ?? null;

            // 1. Create User
            $user = User::create([
                'user_id' => $validated['user_id'],
                'name' => trim($validated['first_name'].' '.($middleName ? $middleName.' ' : '').$validated['surname']),
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'last_name' => $validated['surname'],
                'name_extension' => $nameExtension,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => strtolower($validated['role']),
                'is_active' => true,
            ]);

            // 2. Create Employee Record
            $employee = Employee::create([
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'last_name' => $validated['surname'],
                'name_extension' => $nameExtension,
                'email' => $validated['email'],
                'position' => $validated['position'],
                'classification' => $validated['classification'],
                'date_hired' => $validated['date_hired'],
                'division' => $division->name,
                'subdivision' => $subdivision?->name,
                'section' => $section->name,
                'division_id' => $division->id,
                'subdivision_id' => $subdivision?->id,
                'section_id' => $section->id,
                'status' => 'active',
            ]);

            // 3. Create PDS Record (Draft)
            $pds = Pds::create([
                'employee_id' => $employee->id,
                'status' => 'draft',
            ]);

            // 4. Create PDS Personal Record (store sex and dob)
            PdsPersonal::create([
                'pds_id' => $pds->id,
                'surname' => $validated['surname'],
                'first_name' => $validated['first_name'],
                'middle_name' => $middleName,
                'name_extension' => $nameExtension,
                'sex' => $validated['sex'],
                'dob' => $validated['date_of_birth'],
                'email' => $validated['email'],
            ]);

            Auth::login($user);

            $legacyDomain = config('auth.legacy_domain', '');
            $email = $user->email;
            if (! $email && $legacyDomain) {
                $email = $user->user_id.'@'.$legacyDomain;
            }

            // Set session data
            session([
                'user_id' => $user->id,
                'role' => $user->role ?? 'employee',
                'email' => $email,
                'first_name' => $user->first_name,
                'middle_name' => $user->middle_name,
                'surname' => $user->last_name,
                'name_extension' => $user->name_extension,
            ]);

            // 5. Audit Log
            $activityLogger->logCreate('User', $user->id, [
                'email' => $user->email,
                'role' => $user->role,
                'employee_id' => $employee->id,
            ]);
        });

        return redirect()->intended('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
