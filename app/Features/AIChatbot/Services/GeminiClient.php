<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GeminiClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model,
        private readonly float $temperature,
        private readonly int $maxOutputTokens,
    ) {}

    public static function fromConfig(?string $modelOverride = null): self
    {
        $apiKey = (string) config('services.google_genai.api_key');
        $model = $modelOverride !== null && trim($modelOverride) !== ''
            ? trim($modelOverride)
            : (string) config('services.google_genai.model', 'gemini-3.1-flash-lite-preview');

        return new self(
            apiKey: $apiKey,
            model: $model,
            temperature: (float) config('services.google_genai.temperature', 0.7),
            maxOutputTokens: (int) config('services.google_genai.max_output_tokens', 1024),
        );
    }

    public function isConfigured(): bool
    {
        if ($this->apiKey !== '') {
            return true;
        }

        return app()->environment('testing');
    }

    public function generateContent(array $payload): Response
    {
        return $this->request()
            ->timeout(90)
            ->retry(1, 200, throw: false)
            ->post($this->generateUrl(), $payload);
    }

    public function streamGenerateContent(array $payload): Response
    {
        return $this->request()
            ->timeout(90)
            ->retry(1, 200, throw: false)
            ->post($this->streamUrl(), $payload);
    }

    public function generationConfig(): array
    {
        return [
            'temperature' => $this->temperature,
            'maxOutputTokens' => $this->maxOutputTokens,
        ];
    }

    private function request(): PendingRequest
    {
        return Http::withHeaders([
            'Content-Type' => 'application/json',
        ]);
    }

    private function generateUrl(): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'.$this->model.
            ':generateContent?key='.$this->apiKey;
    }

    private function streamUrl(): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'.$this->model.
            ':streamGenerateContent?alt=sse&key='.$this->apiKey;
    }
}
