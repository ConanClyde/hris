<?php

namespace App\Features\Users\Models;

use App\Features\Employees\Models\Employee;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'user_id',
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'name_extension',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = [
        'full_name',
        'display_name',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the full name attribute.
     * Constructs full name from component fields.
     */
    protected function getFullNameAttribute(): string
    {
        $parts = [
            $this->first_name,
            $this->middle_name ? substr($this->middle_name, 0, 1).'.' : null,
            $this->last_name,
            $this->name_extension,
        ];

        return trim(implode(' ', array_filter($parts))) ?: $this->name ?: 'User';
    }

    /**
     * Get the display name attribute.
     * Returns the most appropriate name for display purposes.
     */
    protected function getDisplayNameAttribute(): string
    {
        // Prefer employee record name if available
        if ($this->employee) {
            $empName = trim($this->employee->first_name.' '.$this->employee->last_name);
            if ($empName) {
                return $empName;
            }
        }

        // Fall back to user component fields
        if ($this->first_name || $this->last_name) {
            return $this->full_name;
        }

        // Legacy fallback
        return $this->name ?: 'User';
    }

    /**
     * Set the name attribute (mutator for backward compatibility).
     * Automatically parses and sets component fields.
     */
    protected function setNameAttribute(string $value): void
    {
        $this->attributes['name'] = $value;

        // Parse and set component fields
        $components = $this->parseNameComponents($value);
        $this->attributes['first_name'] = $components['first_name'];
        $this->attributes['middle_name'] = $components['middle_name'];
        $this->attributes['last_name'] = $components['last_name'];
        $this->attributes['name_extension'] = $components['name_extension'];
    }

    /**
     * Parse a full name into components.
     */
    protected function parseNameComponents(string $name): array
    {
        $name = trim($name);
        $extension = null;
        $extensions = ['Jr.', 'Sr.', 'II', 'III', 'IV', 'V', 'Jr', 'Sr'];

        // Detect extension
        foreach ($extensions as $ext) {
            $pattern = '/[,\s]+'.preg_quote($ext, '/').'$/i';
            if (preg_match($pattern, $name)) {
                $extension = $ext;
                $name = preg_replace($pattern, '', $name);
                $name = trim($name);
                break;
            }
        }

        $parts = preg_split('/\s+/', $name);
        $parts = array_values(array_filter($parts));
        $count = count($parts);

        if ($count === 0) {
            return ['first_name' => null, 'middle_name' => null, 'last_name' => null, 'name_extension' => $extension];
        }

        if ($count === 1) {
            return ['first_name' => $parts[0], 'middle_name' => null, 'last_name' => null, 'name_extension' => $extension];
        }

        if ($count === 2) {
            return ['first_name' => $parts[0], 'middle_name' => null, 'last_name' => $parts[1], 'name_extension' => $extension];
        }

        // 3+ parts
        $firstName = $parts[0];
        $lastName = $parts[$count - 1];
        $middleName = null;

        $surnamePrefixes = ['de', 'del', 'dela', 'de los', 'de las', 'san', 'santa'];
        $secondToLast = strtolower($parts[$count - 2]);

        if (in_array($secondToLast, $surnamePrefixes)) {
            $lastName = $parts[$count - 2].' '.$parts[$count - 1];
            if ($count > 3) {
                $middleParts = array_slice($parts, 1, $count - 3);
                $middleName = implode(' ', $middleParts);
            }
        } else {
            $middleParts = array_slice($parts, 1, $count - 2);
            $middleName = implode(' ', $middleParts);
        }

        return [
            'first_name' => $firstName,
            'middle_name' => $middleName ?: null,
            'last_name' => $lastName,
            'name_extension' => $extension,
        ];
    }

    /**
     * Get the employee record associated with the user.
     * A user may have zero or one employee record (employees.user_id is nullable).
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }
}
