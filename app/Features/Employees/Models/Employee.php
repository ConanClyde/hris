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

    public function badges(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Badge::class, 'employee_badges', 'employee_id', 'badge_id')
            ->withPivot('awarded_at')
            ->withTimestamps();
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
        $section = $this->resolveOrgUnitName('section');
        if ($section) {
            $parts[] = $section;
        }
        $subdivision = $this->resolveOrgUnitName('subdivision');
        if ($subdivision) {
            $parts[] = $subdivision;
        }
        $division = $this->resolveOrgUnitName('division');
        if ($division) {
            $parts[] = $division;
        }

        return implode(' → ', $parts) ?: $this->getAttribute('division') ?? 'Unassigned';
    }

    private function resolveOrgUnitName(string $relation): ?string
    {
        $related = $this->getRelation($relation);
        if (is_object($related) && property_exists($related, 'name')) {
            return $related->name;
        }

        $fallback = $this->getAttribute($relation);
        if (is_string($fallback) && $fallback !== '') {
            return $fallback;
        }

        return null;
    }

    /**
     * Calculate years of service from date hired.
     */
    public function getYearsOfServiceAttribute(): int
    {
        if (! $this->date_hired) {
            return 0;
        }

        return (int) $this->date_hired->diffInYears(now());
    }

    /**
     * Calculate months of service (remaining after years).
     */
    public function getMonthsOfServiceAttribute(): int
    {
        if (! $this->date_hired) {
            return 0;
        }

        $diff = $this->date_hired->diff(now());

        return (int) $diff->m;
    }

    /**
     * Get formatted service record string.
     */
    public function getServiceRecordAttribute(): string
    {
        $years = $this->years_of_service;
        $months = $this->months_of_service;

        if ($years === 0 && $months === 0) {
            return 'New hire';
        }

        $parts = [];
        if ($years > 0) {
            $parts[] = $years.' '.($years === 1 ? 'year' : 'years');
        }
        if ($months > 0) {
            $parts[] = $months.' '.($months === 1 ? 'month' : 'months');
        }

        return implode(', ', $parts);
    }
}
