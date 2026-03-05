<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Exceptions\ModelUnavailableException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ModelAvailabilityService
{
    /**
     * Get available Ollama models with caching.
     *
     * @throws ModelUnavailableException
     */
    public function getAvailableOllamaModels(): array
    {
        $baseUrl = rtrim((string) config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');
        $cacheSeconds = (int) config('services.ollama.tags_cache_seconds', 600);
        $cacheKey = 'ollama:tags:models:'.hash('sha256', $baseUrl);

        return Cache::remember($cacheKey, now()->addSeconds($cacheSeconds), function () use ($baseUrl): array {
            $tagsResponse = Http::timeout(5)->retry(1, 200, throw: false)->get($baseUrl.'/api/tags');
            if (! $tagsResponse->successful()) {
                Log::warning('Ollama tags endpoint unreachable', ['base_url' => $baseUrl]);

                return [];
            }
            $models = $tagsResponse->json('models') ?? [];
            $available = array_map(fn ($m) => (string) ($m['name'] ?? ''), is_array($models) ? $models : []);

            return array_values(array_filter(array_unique($available)));
        });
    }

    /**
     * Check if a specific model is available.
     */
    public function isModelAvailable(string $model): bool
    {
        $availableModels = $this->getAvailableOllamaModels();

        return in_array($model, $availableModels, true);
    }

    /**
     * Validate model availability or throw exception.
     *
     * @throws ModelUnavailableException
     */
    public function validateModelAvailability(string $model): void
    {
        if (! $this->isModelAvailable($model)) {
            throw new ModelUnavailableException(
                "Model '{$model}' is not available",
                context: ['requested_model' => $model]
            );
        }
    }

    /**
     * Check if model is a Google Gemini model.
     */
    public function isGoogleModel(string $model): bool
    {
        $defaultModel = (string) config('services.google_genai.model', 'gemini-3.1-flash-lite-preview');

        return $model === $defaultModel || str_starts_with($model, 'gemini-');
    }

    /**
     * Get Ollama chat URL.
     */
    public function getOllamaChatUrl(): string
    {
        $baseUrl = rtrim(config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');

        return $baseUrl.'/api/chat';
    }

    /**
     * Get Google GenAI generate content URL.
     */
    public function getGoogleGenaiUrl(string $model, string $apiKey): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$apiKey;
    }

    /**
     * Get Google GenAI stream content URL.
     */
    public function getGoogleGenaiStreamUrl(string $model, string $apiKey): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':streamGenerateContent?key='.$apiKey;
    }
}
