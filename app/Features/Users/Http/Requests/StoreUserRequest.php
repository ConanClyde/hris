<?php

namespace App\Features\Users\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'name_extension' => 'nullable|string|max:10',
            'email' => 'required|email|unique:users,email',
            'username' => 'nullable|string|unique:users,username',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => 'required|in:admin,hr,employee',
            'is_active' => 'boolean',
        ];
    }
}
