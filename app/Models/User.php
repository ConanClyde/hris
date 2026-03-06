<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Features\Users\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'must_change_password',
        'role',
        'is_active',
        'status',
        'first_name',
        'middle_name',
        'last_name',
        'name_extension',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function employee(): HasOne
    {
        return $this->hasOne(\App\Features\Employees\Models\Employee::class);
    }

    /**
     * Notices that have been read by this user.
     */
    public function readNotices(): BelongsToMany
    {
        return $this->belongsToMany(\App\Features\Notices\Models\Notice::class, 'notice_reads')
            ->withPivot('read_at');
    }

    public function getFullNameAttribute(): string
    {
        $middle = $this->middle_name ? ' '.$this->middle_name : '';
        $ext = $this->name_extension ? ' '.$this->name_extension : '';

        return trim($this->first_name.$middle.' '.$this->last_name.$ext) ?: $this->name;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin->value;
    }

    public function isHr(): bool
    {
        return $this->role === UserRole::Hr->value;
    }

    public function isEmployee(): bool
    {
        return $this->role === UserRole::Employee->value;
    }

    public function isAdminOrHr(): bool
    {
        return in_array($this->role, [UserRole::Admin->value, UserRole::Hr->value], true);
    }

    public function getRoleEnum(): ?UserRole
    {
        return UserRole::tryFrom($this->role ?? '');
    }
}
