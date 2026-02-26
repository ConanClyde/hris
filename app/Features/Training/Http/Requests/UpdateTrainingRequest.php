<?php

namespace App\Features\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id' => 'required|string',
            'title' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'time_from' => 'nullable',
            'time_to' => 'nullable',
            'hours' => 'nullable|numeric|min:0',
            'type' => 'nullable|string',
            'provider' => 'nullable|string',
            'category' => 'nullable|string',
            'fee' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
        ];
    }
}
