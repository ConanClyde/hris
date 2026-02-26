<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRejectedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $approverName,
    ) {}

    public function build(): self
    {
        return $this->subject('Account Rejected')
            ->view('emails.user-rejected')
            ->with([
                'userName' => $this->user->first_name ?? $this->user->name,
                'fullName' => $this->user->full_name,
                'email' => $this->user->email,
                'role' => $this->user->role,
                'approverName' => $this->approverName,
            ]);
    }
}
