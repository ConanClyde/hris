<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserIdentityUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public User $user) {}

    /**
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->user->id),
            new PrivateChannel('admin.dashboard'),
            new PrivateChannel('hr.dashboard'),
            new PrivateChannel('employees'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'user.identity.updated';
    }

    /**
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        $avatarUrl = $this->user->avatar
            ? asset('storage/'.$this->user->avatar).'?v='.$this->user->updated_at?->timestamp
            : null;

        return [
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->full_name,
                'first_name' => $this->user->first_name,
                'middle_name' => $this->user->middle_name,
                'last_name' => $this->user->last_name,
                'name_extension' => $this->user->name_extension,
                'email' => $this->user->email,
                'username' => $this->user->username,
                'role' => $this->user->role,
                'status' => $this->user->status,
                'is_active' => $this->user->is_active,
                'avatar' => $avatarUrl,
                'updated_at' => $this->user->updated_at?->toISOString(),
            ],
            'type' => 'identity_updated',
            'timestamp' => now()->toIso8601String(),
        ];
    }
}
