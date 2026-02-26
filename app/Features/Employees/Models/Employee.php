<?php

namespace App\Features\Employees\Models;

use App\Models\User;
use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    use HasFactory;

    protected static function newFactory(): EmployeeFactory
    {
        return EmployeeFactory::new();
    }

    protected $fillable = [
        'user_id',
        'division_id',
        'subdivision_id',
        'section_id',
        'first_name',
        'middle_name',
        'last_name',
        'name_extension',
        'email',
        'position',
        'classification',
        'date_hired',
        'division',
        'subdivision',
        'section',
        'status',
    ];

    protected $casts = [
        'date_hired' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function subdivision(): BelongsTo
    {
        return $this->belongsTo(Subdivision::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function pds(): HasOne
    {
        return $this->hasOne(\App\Features\Pds\Models\Pds::class);
    }

    public function leaveCredits(): HasMany
    {
        return $this->hasMany(\App\Features\Leave\Models\LeaveCredit::class);
    }

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? ' '.$this->middle_name : '';
        $ext = $this->name_extension ? ' '.$this->name_extension : '';

        return trim($this->first_name.$middle.' '.$this->last_name.$ext);
    }

    public function getOrganizationalUnitAttribute(): string
    {
        $parts = [];
        if ($this->section?->name) {
            $parts[] = $this->section->name;
        }
        if ($this->subdivision?->name) {
            $parts[] = $this->subdivision->name;
        }
        if ($this->division?->name) {
            $parts[] = $this->division->name;
        }

        return implode(' → ', $parts) ?: $this->division ?? 'Unassigned';
    }
}
