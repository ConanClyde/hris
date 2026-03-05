<?php

namespace App\Features\AIChatbot\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AIChatbotEmbeddingService
{
    /**
     * Embed a single text.
     */
    public function embed(string $text): array
    {
        $result = $this->embedBatch([$text]);

        return $result[0] ?? [];
    }

    /**
     * Embed multiple texts in batch for better performance.
     *
     * @param  array<string>  $texts
     * @return array<array<float>>
     */
    public function embedBatch(array $texts): array
    {
        if ($texts === []) {
            return [];
        }

        $cacheSeconds = (int) config('ai_chatbot.embedding_cache_seconds', 600);
        $results = [];
        $textsToEmbed = [];

        // Check cache for each text
        foreach ($texts as $index => $text) {
            $normalized = trim($text);
            if ($normalized === '') {
                $results[$index] = [];

                continue;
            }

            $cacheKey = 'ai_chatbot:embedding:'.md5($normalized);
            $cached = Cache::get($cacheKey);

            if ($cached !== null && is_array($cached)) {
                $results[$index] = $cached;
            } else {
                $textsToEmbed[$index] = $normalized;
                $results[$index] = null; // Placeholder
            }
        }

        // Batch embed texts not in cache
        if ($textsToEmbed !== []) {
            $batchResults = $this->performBatchEmbedding($textsToEmbed);

            // Merge results and cache
            foreach ($batchResults as $index => $embedding) {
                $results[$index] = $embedding;

                if ($embedding !== []) {
                    $normalized = $textsToEmbed[$index];
                    $cacheKey = 'ai_chatbot:embedding:'.md5($normalized);
                    Cache::put($cacheKey, $embedding, now()->addSeconds($cacheSeconds));
                }
            }
        }

        // Return in original order
        ksort($results);

        return array_values($results);
    }

    /**
     * Perform actual batch embedding API call.
     *
     * @param  array<int, string>  $texts  Indexed by original position
     * @return array<int, array<float>> Indexed by original position
     */
    private function performBatchEmbedding(array $texts): array
    {
        $baseUrl = rtrim(config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');
        $model = config('services.ollama.embedding_model', 'nomic-embed-text');
        $results = [];

        // Ollama doesn't support true batching, so we process in parallel or sequentially
        // For now, process sequentially with individual API calls
        // TODO: Upgrade to Ollama's batch API when available
        foreach ($texts as $index => $text) {
            try {
                $response = Http::timeout(60)
                    ->retry(2, 200, throw: false)
                    ->withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($baseUrl.'/api/embeddings', [
                        'model' => $model,
                        'prompt' => $text,
                    ]);

                if (! $response->successful()) {
                    $results[$index] = [];

                    continue;
                }

                $data = $response->json();
                $embedding = $data['embedding'] ?? [];
                if (! is_array($embedding)) {
                    $results[$index] = [];

                    continue;
                }

                $results[$index] = array_map('floatval', $embedding);
            } catch (\Throwable $e) {
                $results[$index] = [];
            }
        }

        return $results;
    }
}
