<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

use Illuminate\Support\Facades\Cache;

class AIChatbotCacheService
{
    private const DEFAULT_FACTUAL_TTL = 3600; // 1 hour for factual/policy queries

    private const DEFAULT_USER_SPECIFIC_TTL = 1800; // 30 minutes for user-specific

    private const SLOW_QUERY_THRESHOLD = 2000; // ms

    /**
     * Get cached response if available.
     */
    public function get(string $key): ?array
    {
        return Cache::get($key);
    }

    /**
     * Store response in cache with appropriate TTL based on query type.
     */
    public function put(string $key, array $data, ?int $ttl = null): void
    {
        $ttl ??= $this->determineTtl($data);
        Cache::put($key, $data, $ttl);
    }

    /**
     * Determine TTL based on query characteristics.
     */
    private function determineTtl(array $data): int
    {
        // Factual/policy queries get longer cache
        if ($this->isFactualQuery($data)) {
            return self::DEFAULT_FACTUAL_TTL;
        }

        // User-specific responses get shorter cache
        return self::DEFAULT_USER_SPECIFIC_TTL;
    }

    /**
     * Check if query is factual/policy-based.
     */
    private function isFactualQuery(array $data): bool
    {
        $response = $data['response'] ?? '';
        $sources = $data['meta']['sources'] ?? [];

        // If sources are policy documents, likely factual
        if (! empty($sources)) {
            foreach ($sources as $source) {
                $sourceName = $source['source'] ?? '';
                if (str_contains($sourceName, 'policy') ||
                    str_contains($sourceName, 'rule') ||
                    str_contains($sourceName, 'guide')) {
                    return true;
                }
            }
        }

        // Check for FAQ-like patterns
        $factualPatterns = [
            'what is', 'how to', 'how do', 'how can', 'how many',
            'when is', 'where is', 'who is', 'policy', 'leave',
            'holiday', 'benefit', 'salary', 'attendance',
        ];

        $normalized = strtolower($response);
        foreach ($factualPatterns as $pattern) {
            if (str_contains($normalized, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate cache key from query parameters.
     */
    public function generateKey(int $userId, string $queryHash, string $model, ?string $conversationId = null): string
    {
        $key = "ai_chatbot:response:{$userId}:{$queryHash}:{$model}";
        if ($conversationId) {
            $key .= ":{$conversationId}";
        }

        return $key;
    }

    /**
     * Invalidate cache for a specific user.
     */
    public function invalidateForUser(int $userId): void
    {
        // Note: This would require cache tagging which isn't available in all drivers
        // For now, individual keys will expire naturally
    }

    /**
     * Get cache statistics for monitoring.
     */
    public function getStats(): array
    {
        // This is a placeholder - real implementation would track hits/misses
        return [
            'enabled' => config('ai_chatbot.enable_response_cache', true),
            'default_factual_ttl' => self::DEFAULT_FACTUAL_TTL,
            'default_user_ttl' => self::DEFAULT_USER_SPECIFIC_TTL,
        ];
    }
}
