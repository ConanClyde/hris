<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\DTOs;

readonly class ChatResponseDTO
{
    public function __construct(
        public ?string $response = null,
        public ?array $meta = null,
        public ?string $error = null,
        public ?int $latencyMs = null
    ) {}

    /**
     * Create from array (e.g., from API response).
     */
    public static function fromArray(array $data): self
    {
        return new self(
            response: $data['response'] ?? null,
            meta: $data['meta'] ?? null,
            error: $data['error'] ?? null,
            latencyMs: $data['latency_ms'] ?? null
        );
    }

    /**
     * Convert to array for JSON response.
     */
    public function toArray(): array
    {
        $result = [];

        if ($this->response !== null) {
            $result['response'] = $this->response;
        }
        if ($this->meta !== null) {
            $result['meta'] = $this->meta;
        }
        if ($this->error !== null) {
            $result['error'] = $this->error;
        }
        if ($this->latencyMs !== null) {
            $result['latency_ms'] = $this->latencyMs;
        }

        return $result;
    }

    /**
     * Check if response has error.
     */
    public function hasError(): bool
    {
        return $this->error !== null;
    }

    /**
     * Create error response.
     */
    public static function error(string $message, ?int $latencyMs = null): self
    {
        return new self(error: $message, latencyMs: $latencyMs);
    }

    /**
     * Create success response.
     */
    public static function success(string $response, ?array $meta = null, ?int $latencyMs = null): self
    {
        return new self(response: $response, meta: $meta, latencyMs: $latencyMs);
    }
}
