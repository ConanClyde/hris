<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Models\AIChatbotConversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConversationService
{
    /**
     * Get or create conversation for user.
     */
    public function getOrCreateConversation(User $user, ?string $conversationId): ?AIChatbotConversation
    {
        if ($conversationId) {
            $conversation = AIChatbotConversation::forUser($user->id)
                ->whereIn('status', ['active', 'archived'])
                ->find($conversationId);

            if ($conversation && $conversation->status === 'archived') {
                $conversation->restore();
            }

            return $conversation;
        }

        // Create new conversation
        return AIChatbotConversation::create([
            'user_id' => $user->id,
            'title' => 'New Chat',
            'status' => 'active',
            'metadata' => [],
        ]);
    }

    /**
     * Save message to conversation.
     */
    public function saveMessage(
        AIChatbotConversation $conversation,
        string $role,
        string $content,
        ?array $sources = null,
        ?string $toolUsed = null,
        ?array $toolData = null
    ): void {
        $lastSequence = $conversation->messages()->max('sequence_number') ?? 0;

        $message = $conversation->messages()->create([
            'role' => $role,
            'sequence_number' => $lastSequence + 1,
            'content' => $content,
            'sources' => $sources,
            'tool_used' => $toolUsed,
            'tool_data' => $toolData,
        ]);

        $conversation->updateLastMessage();

        // Auto-generate title from first user message
        if ($conversation->title === 'New Chat' && $role === 'user') {
            $conversation->generateTitle($content);
        }
    }

    /**
     * Get current authenticated user or return null.
     */
    public function getCurrentUser(): ?User
    {
        return Auth::user();
    }

    /**
     * Validate user is authenticated.
     */
    public function requireAuth(): User
    {
        $user = $this->getCurrentUser();
        if (! $user) {
            abort(401, 'Unauthorized');
        }

        return $user;
    }
}
