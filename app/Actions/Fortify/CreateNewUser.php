<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Events\UserRegistered;
use App\Features\Users\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $name = (string) ($input['name'] ?? '');
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $firstName = $parts[0] ?? $name;
        $lastName = $parts[1] ?? ($parts[0] ?? $name);

        $user = User::create([
            'name' => $name,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $input['email'],
            'password' => $input['password'],
            'role' => UserRole::Employee->value,
            'status' => 'pending',
            'is_active' => false,
            'must_change_password' => false,
        ]);

        $userWithRelations = $user->fresh(['employee']);

        broadcast(new UserRegistered($userWithRelations))->toOthers();

        return $userWithRelations;
    }
}
