<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\DTOs;

readonly class RetrievalResultDTO
{
    public function __construct(
        public array $snippets,
        public array $meta
    ) {}

    /**
     * Create from array.
     */
    public static function fromArray(array $data): self
    {
        return new self(
            snippets: $data['snippets'] ?? [],
            meta: $data['meta'] ?? []
        );
    }

    /**
     * Convert to array.
     */
    public function toArray(): array
    {
        return [
            'snippets' => $this->snippets,
            'meta' => $this->meta,
        ];
    }

    /**
     * Get max confidence score.
     */
    public function getMaxConfidence(): float
    {
        return $this->meta['max_confidence'] ?? 0.0;
    }

    /**
     * Check if retrieval has low confidence.
     */
    public function isLowConfidence(float $threshold = 0.35): bool
    {
        return $this->getMaxConfidence() < $threshold;
    }

    /**
     * Create empty result.
     */
    public static function empty(): self
    {
        return new self(
            snippets: [],
            meta: [
                'doc_count' => 0,
                'term_count' => 0,
                'max_confidence' => 0.0,
                'retrieval' => 'none',
            ]
        );
    }
}
