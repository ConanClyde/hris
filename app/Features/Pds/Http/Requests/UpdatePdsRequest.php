<?php

namespace App\Features\Pds\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePdsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'personal' => 'required|array',
            'personal.first_name' => 'required|string|max:100',
            'personal.last_name' => 'required|string|max:100',
            'personal.middle_name' => 'nullable|string|max:100',
            'personal.name_extension' => 'nullable|string|max:10',
            'personal.date_of_birth' => 'required|date',
            'personal.place_of_birth' => 'required|string|max:255',
            'personal.sex' => 'required|string|in:male,female',
            'personal.civil_status' => 'required|string|max:50',
            'personal.citizenship' => 'required|string|max:100',
            'personal.height' => 'nullable|numeric',
            'personal.weight' => 'nullable|numeric',
            'personal.blood_type' => 'nullable|string|max:5',
            'personal.gsis_id' => 'nullable|string|max:50',
            'personal.pagibig_id' => 'nullable|string|max:50',
            'personal.philhealth_id' => 'nullable|string|max:50',
            'personal.sss_id' => 'nullable|string|max:50',
            'personal.tin' => 'nullable|string|max:50',
            'personal.residential_address' => 'nullable|string|max:255',
            'personal.residential_zip' => 'nullable|string|max:10',
            'personal.permanent_address' => 'nullable|string|max:255',
            'personal.permanent_zip' => 'nullable|string|max:10',
            'personal.telephone' => 'nullable|string|max:20',
            'personal.mobile' => 'nullable|string|max:20',
            'personal.email' => 'required|email|max:255',
        ];
    }
}
