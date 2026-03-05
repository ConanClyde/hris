<?php

namespace App\Features\Pds\Models;

use App\Features\Employees\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdsRevisionRequest extends Model
{
    protected $table = 'pds_revision_requests';

    protected $fillable = [
        'employee_id',
        'pds_id',
        'status',
        'changes',
        'remarks',
        'reviewed_by_user_id',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'reviewed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function pds(): BelongsTo
    {
        return $this->belongsTo(Pds::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
}
