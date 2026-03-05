<?php

declare(strict_types=1);

namespace App\Features\Employees\Services;

use App\Features\Employees\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class EmployeeBulkImportService
{
    /**
     * Import employees from CSV/Excel data.
     *
     * @param  array<array<string, mixed>>  $rows
     * @return array{success: int, failed: int, errors: array}
     */
    public function import(array $rows): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        DB::transaction(function () use ($rows, &$results) {
            foreach ($rows as $index => $row) {
                try {
                    $this->processRow($row);
                    $results['success']++;
                } catch (\Throwable $e) {
                    $results['failed']++;
                    $results['errors'][] = [
                        'row' => $index + 1,
                        'data' => $row,
                        'error' => $e->getMessage(),
                    ];
                    Log::error('Employee import failed', [
                        'row' => $index + 1,
                        'error' => $e->getMessage(),
                        'data' => $row,
                    ]);
                }
            }
        });

        return $results;
    }

    /**
     * Process a single import row.
     *
     * @param  array<string, mixed>  $row
     *
     * @throws \InvalidArgumentException
     */
    private function processRow(array $row): void
    {
        // Normalize keys (handle both CSV headers and array keys)
        $data = $this->normalizeRow($row);

        // Validate required fields
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'position' => 'nullable|string|max:100',
            'classification' => 'nullable|string|max:50',
            'date_hired' => 'nullable|date',
            'division_id' => 'nullable|exists:divisions,id',
            'subdivision_id' => 'nullable|exists:subdivisions,id',
            'section_id' => 'nullable|exists:sections,id',
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('Validation failed: '.$validator->errors()->first());
        }

        // Create user account
        $user = User::create([
            'name' => $data['first_name'].' '.$data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($this->generateTemporaryPassword()),
            'role' => 'employee',
            'is_active' => true,
        ]);

        // Create employee record
        Employee::create([
            'user_id' => $user->id,
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'] ?? null,
            'last_name' => $data['last_name'],
            'name_extension' => $data['name_extension'] ?? null,
            'email' => $data['email'],
            'position' => $data['position'] ?? null,
            'classification' => $data['classification'] ?? null,
            'date_hired' => $data['date_hired'] ?? now(),
            'division_id' => $data['division_id'] ?? null,
            'subdivision_id' => $data['subdivision_id'] ?? null,
            'section_id' => $data['section_id'] ?? null,
            'status' => 'active',
        ]);
    }

    /**
     * Normalize CSV row to consistent format.
     *
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function normalizeRow(array $row): array
    {
        $mapping = [
            'first_name' => ['first_name', 'firstname', 'first name', 'given_name', 'given name'],
            'middle_name' => ['middle_name', 'middlename', 'middle name', 'middle_initial'],
            'last_name' => ['last_name', 'lastname', 'last name', 'surname', 'family_name'],
            'name_extension' => ['name_extension', 'extension', 'suffix', 'jr_sr'],
            'email' => ['email', 'email_address', 'email address', 'e-mail'],
            'position' => ['position', 'job_title', 'job title', 'designation'],
            'classification' => ['classification', 'employment_type', 'employment type', 'job_type'],
            'date_hired' => ['date_hired', 'date hired', 'hire_date', 'hire date', 'start_date'],
            'division_id' => ['division_id', 'division', 'division id'],
            'subdivision_id' => ['subdivision_id', 'subdivision', 'subdivision id'],
            'section_id' => ['section_id', 'section', 'section id'],
        ];

        $normalized = [];
        $rowLower = array_change_key_case($row, CASE_LOWER);

        foreach ($mapping as $standard => $variants) {
            foreach ($variants as $variant) {
                if (isset($rowLower[strtolower($variant)])) {
                    $normalized[$standard] = $rowLower[strtolower($variant)];
                    break;
                }
            }
        }

        return $normalized;
    }

    /**
     * Generate temporary password for new users.
     */
    private function generateTemporaryPassword(): string
    {
        return bin2hex(random_bytes(8)); // 16 character random password
    }

    /**
     * Export employees to CSV format.
     *
     * @return array{headers: array<string>, rows: array<array>}
     */
    public function export(?array $employeeIds = null): array
    {
        $headers = [
            'employee_id',
            'first_name',
            'middle_name',
            'last_name',
            'name_extension',
            'email',
            'position',
            'classification',
            'date_hired',
            'division',
            'subdivision',
            'section',
            'status',
        ];

        $query = Employee::query()
            ->with(['division', 'subdivision', 'section']);

        if ($employeeIds !== null && ! empty($employeeIds)) {
            $query->whereIn('id', $employeeIds);
        }

        $rows = $query->get()->map(fn (Employee $employee) => [
            $employee->id,
            $employee->first_name,
            $employee->middle_name,
            $employee->last_name,
            $employee->name_extension,
            $employee->email,
            $employee->position,
            $employee->classification,
            $employee->date_hired?->format('Y-m-d'),
            $employee->division?->name,
            $employee->subdivision?->name,
            $employee->section?->name,
            $employee->status,
        ])->toArray();

        return [
            'headers' => $headers,
            'rows' => $rows,
        ];
    }

    /**
     * Get import template headers.
     *
     * @return array<string>
     */
    public function getImportTemplateHeaders(): array
    {
        return [
            'first_name',
            'middle_name',
            'last_name',
            'name_extension',
            'email',
            'position',
            'classification',
            'date_hired',
            'division_id',
            'subdivision_id',
            'section_id',
        ];
    }
}
