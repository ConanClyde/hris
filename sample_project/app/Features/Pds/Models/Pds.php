<?php

namespace App\Features\Pds\Models;

use App\Features\Employees\Models\Employee;
use App\Features\Users\Models\User;
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
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

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
        return $this->hasOne(PdsFamily::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(PdsChild::class);
    }

    public function education(): HasMany
    {
        return $this->hasMany(PdsEducation::class);
    }

    public function cscEligibility(): HasMany
    {
        return $this->hasMany(PdsCscEligibility::class)->orderBy('sort_order');
    }

    public function workExperience(): HasMany
    {
        return $this->hasMany(PdsWorkExperience::class, 'pds_id')->orderBy('sort_order');
    }

    public function voluntaryWork(): HasMany
    {
        return $this->hasMany(PdsVoluntaryWork::class, 'pds_id')->orderBy('sort_order');
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(PdsTraining::class, 'pds_id')->orderBy('sort_order');
    }

    public function references(): HasMany
    {
        return $this->hasMany(PdsReferences::class, 'pds_id')->orderBy('sort_order');
    }

    public function backgroundInfo(): HasOne
    {
        return $this->hasOne(PdsBackgroundInfo::class, 'pds_id');
    }

    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}
