<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\DTOs;

readonly class ChatMessageDTO
{
    public function __construct(
        public string $id,
        public string $role,
        public string $content,
        public \DateTimeInterface $timestamp,
        public ?array $sources = null,
        public ?array $meta = null
    ) {}

    /**
     * Create from array (e.g., from request or database).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: (string) ($data['id'] ?? uniqid()),
            role: (string) ($data['role'] ?? 'user'),
            content: (string) ($data['content'] ?? ''),
            timestamp: $data['timestamp'] ?? now(),
            sources: $data['sources'] ?? null,
            meta: $data['meta'] ?? null
        );
    }

    /**
     * Convert to array for serialization.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'role' => $this->role,
            'content' => $this->content,
            'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
            'sources' => $this->sources,
            'meta' => $this->meta,
        ];
    }
}
