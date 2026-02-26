<?php

namespace App\Features\Auth\Actions;

use App\Features\Employees\Models\Division;
use App\Features\Employees\Models\Employee;
use App\Features\Employees\Models\Section;
use App\Features\Employees\Models\Subdivision;
use App\Features\Pds\Models\Pds;
use App\Features\Pds\Models\PdsPersonal;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterNewUser
{
    /**
     * Create a new user with Employee, Pds, and PdsPersonal records.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): User
    {
        $input = $this->prepareInput($input);
        $validated = $this->validate($input);
        $isEmployee = $validated['role'] === UserRole::Employee->value;

        return DB::transaction(function () use ($validated, $isEmployee) {
            $name = trim(
                $validated['first_name'].
                ' '.($validated['middle_name'] ?? '').
                ' '.$validated['surname'].
                ($validated['name_extension'] ?? '')
            );

            $user = User::create([
                'name' => $name,
                'username' => Str::lower($validated['username']),
                'email' => Str::lower($validated['email']),
                'password' => $validated['password'],
                'role' => $validated['role'],
                'is_active' => false,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['surname'],
                'name_extension' => $validated['name_extension'] ?? null,
            ]);

            $employeeData = [
                'user_id' => $user->id,
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['surname'],
                'name_extension' => $validated['name_extension'] ?? null,
                'email' => $user->email,
                'status' => 'active',
            ];

            if ($isEmployee) {
                $division = isset($validated['division_id'])
                    ? Division::find($validated['division_id'])
                    : null;
                $subdivision = isset($validated['subdivision_id'])
                    ? Subdivision::find($validated['subdivision_id'])
                    : null;
                $section = isset($validated['section_id'])
                    ? Section::find($validated['section_id'])
                    : null;

                $employeeData = array_merge($employeeData, [
                    'division_id' => $validated['division_id'] ?? null,
                    'subdivision_id' => $validated['subdivision_id'] ?? null,
                    'section_id' => $validated['section_id'] ?? null,
                    'position' => $validated['position'] ?? null,
                    'classification' => $validated['classification'] ?? null,
                    'date_hired' => $validated['date_hired'] ?? null,
                    'division' => $division?->name,
                    'subdivision' => $subdivision?->name,
                    'section' => $section?->name,
                ]);
            }

            $employee = Employee::create($employeeData);

            $pds = Pds::create([
                'employee_id' => $employee->id,
                'status' => 'draft',
            ]);

            $pdsPersonalData = [
                'pds_id' => $pds->id,
                'surname' => $validated['surname'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'name_extension' => $validated['name_extension'] ?? null,
            ];

            if ($isEmployee) {
                $pdsPersonalData['dob'] = $validated['date_of_birth'] ?? null;
                $pdsPersonalData['sex'] = $validated['sex'] ?? null;
            }

            PdsPersonal::create($pdsPersonalData);

            return $user;
        });
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function prepareInput(array $input): array
    {
        foreach (['division_id', 'subdivision_id', 'section_id'] as $key) {
            if (isset($input[$key]) && $input[$key] === '') {
                $input[$key] = null;
            }
        }

        return $input;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function validate(array $input): array
    {
        $isEmployee = ($input['role'] ?? '') === UserRole::Employee->value;

        $rules = [
            'role' => ['required', 'string', 'in:employee,hr,admin'],
            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'name_extension' => ['nullable', 'string', 'max:50'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::default()],
        ];

        if ($isEmployee) {
            $rules = array_merge($rules, [
                'sex' => ['nullable', 'string', 'in:male,female'],
                'date_of_birth' => ['nullable', 'date'],
                'date_hired' => ['nullable', 'date'],
                'division_id' => ['nullable', 'integer', 'exists:divisions,id'],
                'subdivision_id' => ['nullable', 'integer', 'exists:subdivisions,id'],
                'section_id' => ['nullable', 'integer', 'exists:sections,id'],
                'position' => ['nullable', 'string', 'max:255'],
                'classification' => ['nullable', 'string', 'max:255'],
            ]);
        }

        return Validator::make($input, $rules)->validate();
    }
}
