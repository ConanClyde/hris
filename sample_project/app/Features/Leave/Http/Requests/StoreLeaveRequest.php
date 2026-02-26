<?php

namespace App\Features\Leave\Http\Requests;

use App\Features\Leave\Enums\LeaveType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['sometimes', 'required', 'integer', 'exists:employees,id'],
            'leave_type' => ['required', 'string', Rule::in(LeaveType::labels())],
            'date_from' => ['required', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'total_days' => ['required', 'numeric', 'min:0.5'],
            'reason' => ['nullable', 'string'],
            'attachments.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
