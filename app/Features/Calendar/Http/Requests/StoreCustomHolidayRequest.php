<?php

namespace App\Features\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomHolidayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_recurring' => 'boolean',
        ];
    }
}
