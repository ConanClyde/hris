<?php

namespace App\Features\Leave\Models;

use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeaveAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'leave_credit_id',
        'amount',
        'reason',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function credit(): BelongsTo
    {
        return $this->belongsTo(LeaveCredit::class, 'leave_credit_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
