<?php

declare(strict_types=1);

namespace App\Features\Pds\Services;

use Illuminate\Support\Facades\Validator;

class PdsValidationService
{
    /**
     * Validation rules for PDS Personal Information.
     *
     * @return array<string, string>
     */
    public static function personalInfoRules(): array
    {
        return [
            'surname' => 'required|string|max:50|regex:/^[a-zA-Z\s\-\'.]+$/',
            'first_name' => 'required|string|max:50|regex:/^[a-zA-Z\s\-\'.]+$/',
            'middle_name' => 'nullable|string|max:50|regex:/^[a-zA-Z\s\-\'.]+$/',
            'name_extension' => 'nullable|string|max:10|in:Jr.,Sr.,II,III,IV,V',
            'dob' => 'required|date|before_or_equal:'.now()->subYears(18)->format('Y-m-d'),
            'place_of_birth' => 'required|string|max:100',
            'sex' => 'required|string|in:Male,Female',
            'civil_status' => 'required|string|in:Single,Married,Widowed,Separated,Annulled',
            'height' => 'nullable|numeric|min:0|max:300',
            'weight' => 'nullable|numeric|min:0|max:500',
            'blood_type' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'citizenship_type' => 'required|string|in:Filipino,Dual Citizenship,Foreign',
            'phone' => 'nullable|string|regex:/^[0-9\-\(\)\s]+$/|max:20',
            'mobile' => 'required|string|regex:/^09[0-9]{9}$/|max:11',
            'email' => 'required|email|max:100',
            'gsis' => 'nullable|string|regex:/^[0-9]{11}$/',
            'pag_ibig' => 'nullable|string|regex:/^[0-9]{12}$/',
            'philhealth' => 'nullable|string|regex:/^[0-9]{12}$/',
            'sss' => 'nullable|string|regex:/^[0-9]{10}$/',
            'tin' => 'nullable|string|regex:/^[0-9]{9,12}$/',
        ];
    }

    /**
     * Validate PDS Personal Information data.
     *
     * @param  array<string, mixed>  $data
     * @return array{valid: bool, errors: array<string, array<string>>}
     */
    public function validatePersonalInfo(array $data): array
    {
        $validator = Validator::make($data, self::personalInfoRules());

        return [
            'valid' => ! $validator->fails(),
            'errors' => $validator->errors()->toArray(),
        ];
    }

    /**
     * Validation rules for PDS Education entries.
     *
     * @return array<string, string>
     */
    public static function educationRules(): array
    {
        return [
            'level' => 'required|string|in:Elementary,Secondary,Vocational/Trade,Course,College,Graduate Studies',
            'school_name' => 'required|string|max:150',
            'course' => 'nullable|string|max:150|required_if:level,College,Graduate Studies',
            'date_from' => 'nullable|date|before_or_equal:date_to',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'highest_level' => 'nullable|string|max:100',
            'year_graduated' => 'nullable|integer|min:1900|max:'.now()->year,
            'honors_received' => 'nullable|string|max:200',
        ];
    }

    /**
     * Validate PDS Education data.
     *
     * @param  array<string, mixed>  $data
     * @return array{valid: bool, errors: array<string, array<string>>}
     */
    public function validateEducation(array $data): array
    {
        $validator = Validator::make($data, self::educationRules());

        return [
            'valid' => ! $validator->fails(),
            'errors' => $validator->errors()->toArray(),
        ];
    }

    /**
     * Validation rules for PDS Work Experience entries.
     *
     * @return array<string, string>
     */
    public static function workExperienceRules(): array
    {
        return [
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'position' => 'required|string|max:150',
            'department' => 'required|string|max:150',
            'monthly_salary' => 'nullable|numeric|min:0',
            'salary_grade' => 'nullable|string|max:10',
            'appointment_status' => 'required|string|in:Permanent,Temporary,Contractual,Casual,Emergency,Job Order',
            'government_service' => 'required|boolean',
        ];
    }

    /**
     * Validate PDS Work Experience data.
     *
     * @param  array<string, mixed>  $data
     * @return array{valid: bool, errors: array<string, array<string>>}
     */
    public function validateWorkExperience(array $data): array
    {
        $validator = Validator::make($data, self::workExperienceRules());

        return [
            'valid' => ! $validator->fails(),
            'errors' => $validator->errors()->toArray(),
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'mobile.regex' => 'The mobile number must be a valid Philippine mobile number (e.g., 09171234567).',
            'dob.before_or_equal' => 'The employee must be at least 18 years old.',
            'gsis.regex' => 'The GSIS ID must be exactly 11 digits.',
            'pag_ibig.regex' => 'The Pag-IBIG ID must be exactly 12 digits.',
            'philhealth.regex' => 'The PhilHealth ID must be exactly 12 digits.',
            'sss.regex' => 'The SSS number must be exactly 10 digits.',
            'tin.regex' => 'The TIN must be between 9 and 12 digits.',
            'name_extension.in' => 'The name extension must be one of: Jr., Sr., II, III, IV, V.',
            'blood_type.in' => 'Please select a valid blood type (A+, A-, B+, B-, AB+, AB-, O+, O-).',
        ];
    }
}
