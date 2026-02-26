<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $ipAddress = null,
    ) {}

    public function build(): self
    {
        return $this->subject('New User Registration')
            ->view('emails.user-registered')
            ->with([
                'fullName' => $this->user->full_name,
                'email' => $this->user->email,
                'role' => $this->user->role,
                'registrationDate' => optional($this->user->created_at)->toDayDateTimeString(),
                'ipAddress' => $this->ipAddress ?? 'N/A',
            ]);
    }
}
