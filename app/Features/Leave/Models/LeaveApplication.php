<?php

namespace App\Features\Leave\Models;

use App\Features\Employees\Models\Employee;
use App\Features\Leave\Enums\LeaveStatus;
use Database\Factories\LeaveApplicationFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveApplication extends Model
{
    use HasFactory;

    protected static function newFactory(): LeaveApplicationFactory
    {
        return LeaveApplicationFactory::new();
    }

    protected $fillable = [
        'employee_id',
        'employee_fk',
        'employee_name',
        'type',
        'date_from',
        'date_to',
        'total_days',
        'reason',
        'status',
        'attachments',
        'legacy_attachment',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'total_days' => 'float',
        'attachments' => 'array',
        'legacy_attachment' => 'array',
        'employee_fk' => 'integer',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_fk');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', LeaveStatus::Pending->value);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', LeaveStatus::Approved->value);
    }

    public function scopeForEmployee(Builder $query, string $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeForEmployeePk(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_fk', $employeeId);
    }
}
