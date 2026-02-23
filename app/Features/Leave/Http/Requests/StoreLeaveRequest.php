<?php

namespace App\Features\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'string', 'max:255'],
            'employee_name' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'string', 'max:255'],
            'date_from' => ['required', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'total_days' => ['required', 'numeric', 'min:0.5'],
            'reason' => ['nullable', 'string'],
        ];
    }
}
