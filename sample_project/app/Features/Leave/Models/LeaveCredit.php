<?php

namespace App\Features\Leave\Models;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class LeaveCredit extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function adjustments(): HasMany
    {
        return $this->hasMany(LeaveAdjustment::class);
    }

    /**
     * Adjust the balance and log the transaction.
     *
     * @param  float  $amount  Positive to add, negative to deduct.
     * @param  string  $reason  Audit reason.
     * @param  int|null  $userId  User performing the action (optional).
     */
    public function adjust(float $amount, string $reason, ?int $userId = null): void
    {
        DB::transaction(function () use ($amount, $reason, $userId) {
            $this->increment('balance', $amount);

            $this->adjustments()->create([
                'amount' => $amount,
                'reason' => $reason,
                'created_by' => $userId,
            ]);
        });
    }
}
