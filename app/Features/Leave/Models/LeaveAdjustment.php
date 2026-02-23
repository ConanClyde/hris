<?php

namespace App\Features\Leave\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveAdjustment extends Model
{
    protected $fillable = [
        'leave_credit_id',
        'amount',
        'reason',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function credit(): BelongsTo
    {
        return $this->belongsTo(LeaveCredit::class, 'leave_credit_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
