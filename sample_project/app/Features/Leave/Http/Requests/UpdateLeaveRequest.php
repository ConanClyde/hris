<?php

namespace App\Features\Leave\Http\Requests;

use App\Features\Leave\Enums\LeaveStatus;
use App\Features\Leave\Enums\LeaveType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    public function rules(): array
    {
        return [
            'employee_id' => ['sometimes', 'required', 'integer', 'exists:employees,id'],
            'status' => ['sometimes', 'required', 'string', Rule::in(array_column(LeaveStatus::cases(), 'value'))],
            'leave_type' => ['required', 'string', Rule::in(LeaveType::labels())],
            'date_from' => ['required', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'total_days' => ['required', 'numeric', 'min:0.5'],
            'reason' => ['nullable', 'string'],
        ];
    }
}
