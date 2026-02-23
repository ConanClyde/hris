<?php

namespace App\Features\Leave\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeaveStatusRequest extends FormRequest
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
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ];
    }
}
