<?php

namespace App\Features\Pds\Models;

use App\Features\Employees\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pds extends Model
{
    protected $table = 'pds';

    protected $fillable = [
        'employee_id',
        'status',
        'submitted_at',
        'reviewed_by_user_id',
        'reviewed_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_user_id');
    }

    public function personal(): HasOne
    {
        return $this->hasOne(PdsPersonal::class, 'pds_id');
    }

    public function family(): HasOne
    {
        return $this->hasOne(PdsFamily::class, 'pds_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(PdsChild::class, 'pds_id')->orderBy('id');
    }

    public function education(): HasMany
    {
        return $this->hasMany(PdsEducation::class, 'pds_id')->orderBy('id');
    }

    public function cscEligibility(): HasMany
    {
        return $this->hasMany(PdsCscEligibility::class, 'pds_id')->orderBy('sort_order');
    }

    public function workExperience(): HasMany
    {
        return $this->hasMany(PdsWorkExperience::class, 'pds_id')->orderBy('sort_order');
    }

    public function voluntaryWork(): HasMany
    {
        return $this->hasMany(PdsVoluntaryWork::class, 'pds_id')->orderBy('sort_order');
    }

    public function training(): HasMany
    {
        return $this->hasMany(PdsTraining::class, 'pds_id')->orderBy('sort_order');
    }

    public function otherInfo(): HasMany
    {
        return $this->hasMany(PdsOtherInfo::class, 'pds_id')->orderBy('sort_order');
    }

    public function references(): HasMany
    {
        return $this->hasMany(PdsReference::class, 'pds_id')->orderBy('sort_order');
    }

    public function backgroundInfo(): HasOne
    {
        return $this->hasOne(PdsBackgroundInfo::class, 'pds_id');
    }

    public function scopeForEmployee(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeWithStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
