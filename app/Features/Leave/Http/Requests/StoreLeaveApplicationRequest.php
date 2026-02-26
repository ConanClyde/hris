<?php

namespace App\Features\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeaveApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'total_days' => 'required|numeric|min:0.5',
            'reason' => 'nullable|string',
        ];
    }
}
