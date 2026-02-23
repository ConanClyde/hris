<?php

namespace App\Features\Training\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingStatusRequest extends FormRequest
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
