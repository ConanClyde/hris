<?php

declare(strict_types=1);

namespace App\Features\AIChatbot\Http\Controllers;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\AIChatbot\Models\AIChatbotConversation;
use App\Features\AIChatbot\Models\AIChatbotMessage;
use App\Features\AIChatbot\Models\AIChatbotMetric;
use App\Features\AIChatbot\Services\AIChatbotAnalysisService;
use App\Features\AIChatbot\Services\AIChatbotContextService;
use App\Features\AIChatbot\Services\AIChatbotSuggestionService;
use App\Features\AIChatbot\Services\AIChatbotToolService;
use App\Features\AIChatbot\Services\AIChatbotUserProfileService;
use App\Features\AIChatbot\Services\AIResponseValidatorService;
use App\Features\Calendar\Http\GoogleCalendarService;
use App\Features\Calendar\Models\CustomHoliday;
use App\Features\Notices\Models\Notice;
use App\Features\Users\Enums\UserRole;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AIChatbotController extends Controller
{
    use GoogleCalendarService;

    private ?string $userPreferencesSummary = null;

    public function __construct(
        private AIChatbotContextService $contextService,
        private AIChatbotSuggestionService $suggestionService,
        private AIChatbotToolService $toolService,
        private AIChatbotAnalysisService $analysisService,
        private AIChatbotUserProfileService $profileService,
        private AIResponseValidatorService $validatorService
    ) {}

    /**
     * Resolve context for chat. Uses analysis pipeline when enabled and analysis succeeds; falls back to getContext otherwise.
     *
     * @return array{role?: string, stats?: array, employee_list?: string, employee_data?: array, policy_snippets?: array, retrieval_meta?: array, generated_at?: string}|array{permission_denied: true, message: string}
     */
    private function resolveContextForChat(\App\Models\User $user, string $query): array
    {
        if (! config('ai_chatbot.enable_analysis_pipeline', true)) {
            return $this->contextService->getContext($user, $query)->toArray();
        }

        $userRole = (string) $user->role;
        $analysis = $this->analysisService->analyzePrompt($query, $userRole);

        if ($analysis === null) {
            return $this->contextService->getContext($user, $query)->toArray();
        }

        $resolved = $this->contextService->resolveFromAnalysis($analysis, $user, $query);

        return $resolved instanceof \App\Features\AIChatbot\DTOs\ContextDTO
            ? $resolved->toArray()
            : $resolved;
    }

    private function getOllamaChatUrl(): string
    {
        $baseUrl = rtrim(config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');

        return $baseUrl.'/api/chat';
    }

    private function isGoogleModel(string $model): bool
    {
        $defaultModel = (string) config('services.google_genai.model', 'gemini-3.1-flash-lite-preview');

        return $model === $defaultModel || str_starts_with($model, 'gemini-');
    }

    private function getGoogleGenaiUrl(string $model, string $apiKey): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':generateContent?key='.$apiKey;
    }

    private function getGoogleGenaiStreamUrl(string $model, string $apiKey): string
    {
        return 'https://generativelanguage.googleapis.com/v1beta/models/'.$model.':streamGenerateContent?alt=sse&key='.$apiKey;
    }

    private function getAvailableOllamaModels(): array
    {
        $baseUrl = rtrim((string) config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');
        $cacheSeconds = (int) config('services.ollama.tags_cache_seconds', 600);
        $cacheKey = 'ollama:tags:models:'.hash('sha256', $baseUrl);

        return Cache::remember($cacheKey, now()->addSeconds($cacheSeconds), function () use ($baseUrl): array {
            $tagsResponse = Http::timeout(5)->retry(1, 200, throw: false)->get($baseUrl.'/api/tags');
            if (! $tagsResponse->successful()) {
                return [];
            }
            $models = $tagsResponse->json('models') ?? [];
            $available = array_map(fn ($m) => (string) ($m['name'] ?? ''), is_array($models) ? $models : []);

            return array_values(array_filter(array_unique($available)));
        });
    }

    private function runOllamaModel(string $model, array $messages, int $timeoutSeconds = 45): array
    {
        $start = microtime(true);
        try {
            $chatUrl = $this->getOllamaChatUrl();
            $response = Http::timeout($timeoutSeconds)->retry(1, 200, throw: false)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($chatUrl, [
                'model' => $model,
                'messages' => $messages,
                'stream' => false,
                'options' => [
                    'temperature' => config('services.ollama.temperature'),
                    'num_ctx' => config('services.ollama.context_size'),
                    'num_predict' => -1,
                ],
            ]);

            $latencyMs = (int) round((microtime(true) - $start) * 1000);
            if ($response->successful()) {
                $data = $response->json();
                $text = $data['message']['content'] ?? null;

                return [
                    'response' => is_string($text) ? $text : null,
                    'error' => is_string($text) ? null : 'Empty response',
                    'latency_ms' => $latencyMs,
                ];
            }

            return [
                'response' => null,
                'error' => 'HTTP '.$response->status(),
                'latency_ms' => $latencyMs,
            ];
        } catch (\Throwable $e) {
            return [
                'response' => null,
                'error' => $e->getMessage(),
                'latency_ms' => (int) round((microtime(true) - $start) * 1000),
            ];
        }
    }

    public function chat(Request $request): JsonResponse
    {
        @set_time_limit(180);
        @ini_set('max_execution_time', '180');

        $validated = $request->validate([
            'message' => 'required|string|max:4000',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|in:user,assistant',
            'history.*.content' => 'required_with:history|string|max:4000',
            'model' => 'nullable|string|max:80',
            'conversation_id' => 'nullable|string|uuid',
        ]);

        $userMessage = strip_tags((string) $validated['message']);
        $history = $validated['history'] ?? [];
        if (! is_array($history)) {
            $history = [];
        }

        // Sanitize history items
        $history = array_map(function ($item) {
            return [
                'role' => $item['role'] ?? 'user',
                'content' => strip_tags((string) ($item['content'] ?? '')),
            ];
        }, $history);

        $history = array_slice($history, -20);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get or create conversation
        $conversationId = $request->input('conversation_id');
        $conversation = $this->getOrCreateConversation($user, $conversationId);
        if (! $conversation) {
            return response()->json(['error' => 'Conversation not found'], 404);
        }

        // Save user message to conversation
        $this->saveMessage($conversation, 'user', $userMessage);

        $normalizedMessage = $this->suggestionService->normalizeQuestion((string) $userMessage);
        if ($normalizedMessage === '') {
            $normalizedMessage = trim((string) $userMessage);
        }

        $recentIntents = $this->suggestionService->recentIntents($user);

        // Update user profile preferences based on language and intents
        $instruction = $this->detectLanguageInstruction($userMessage);
        $this->profileService->updateLanguage($user, $instruction);
        $this->userPreferencesSummary = $this->profileService->buildPreferencesSummary($user);

        // Check for suggestion match first (return immediately if matched)
        $matchedSuggestion = $this->suggestionService->matchSuggestion($user, $normalizedMessage);
        if ($matchedSuggestion && isset($matchedSuggestion['id'])) {
            // Skip suggestions for compound questions to force tool usage for complete answers
            if ($this->suggestionService->isCompoundQuestion($normalizedMessage) || $this->shouldPreferToolsForStatsQuestion($normalizedMessage)) {
                Log::info('Compound question detected, skipping suggestion to use tools', [
                    'suggestion_id' => $matchedSuggestion['id'],
                    'query_hash' => hash('sha256', $normalizedMessage),
                ]);
            } else {
                $recentIntents = $this->suggestionService->rememberIntent($user, (string) $matchedSuggestion['id']);
                $answer = $this->suggestionService->answerForUser($user, $matchedSuggestion['id'], $userMessage);
                $source = $this->suggestionService->sourceForSuggestion($matchedSuggestion['id']);
                if ($source) {
                    $this->profileService->recordTopicsFromSources($user, [$source['source']]);
                }
                $sources = $source ? [[
                    'source' => $source['source'],
                    'url' => route('ai-chatbot.policy', ['filename' => $source['source']]),
                    'confidence' => 1.0,
                ]] : [];

                $response = [
                    'response' => $answer,
                    'meta' => [
                        'source' => 'suggestion',
                        'suggestion_id' => $matchedSuggestion['id'],
                        'match' => $matchedSuggestion['match'] ?? null,
                        'sources' => $sources,
                        'followups' => $this->suggestionService->followUpSuggestions($user, $matchedSuggestion['id'], $recentIntents),
                    ],
                ];

                $this->saveMessage($conversation, 'assistant', $answer);

                return response()->json($response);
            }
        }

        $startTime = microtime(true);
        $context = $this->resolveContextForChat($user, (string) $normalizedMessage);
        $contextMs = (int) round((microtime(true) - $startTime) * 1000);

        if (isset($context['permission_denied']) && $context['permission_denied']) {
            $this->saveMessage($conversation, 'assistant', (string) ($context['message'] ?? 'Access denied.'));

            return response()->json([
                'response' => (string) ($context['message'] ?? 'This question requires elevated access. Please contact your HR administrator.'),
                'meta' => [
                    'source' => 'permission_denied',
                    'sources' => [],
                    'followups' => [],
                ],
            ]);
        }

        $policySnippetSources = array_column($context['policy_snippets'] ?? [], 'source');
        if ($policySnippetSources !== []) {
            $this->profileService->recordTopicsFromSources($user, $policySnippetSources);
        }

        $queryHash = hash('sha256', $normalizedMessage);
        $modelOverride = trim((string) $request->input('model', ''));
        $modelToUse = $modelOverride !== '' ? $modelOverride : (string) config('services.ollama.model');
        if (! $this->isGoogleModel($modelToUse)) {
            $history = $this->compressHistory($history);
        }
        $cacheKey = 'ai_chatbot:response:'.$user->id.':'.$queryHash.':'.$modelToUse;
        $cacheEnabled = (bool) config('ai_chatbot.enable_response_cache', true);

        if ($this->isGoogleModel($modelToUse)) {
            $response = $this->chatWithGemini(
                $history,
                $userMessage,
                (string) ($context['role'] ?? $user->role),
                $context['stats'] ?? [],
                (string) ($context['employee_list'] ?? ''),
                $context['employee_data'] ?? [],
                $context['policy_snippets'] ?? [],
                $context['retrieval_meta'] ?? [],
                $contextMs,
                $cacheKey,
                $cacheEnabled,
                $modelToUse,
                (int) $user->id,
                (string) $user->role,
                $user,
                $queryHash,
                $recentIntents,
                $conversation
            );
        } else {
            $tagsStart = microtime(true);
            $availableModels = $this->getAvailableOllamaModels();
            $tagsMs = (int) round((microtime(true) - $tagsStart) * 1000);
            if ($availableModels === []) {
                return response()->json([
                    'error' => 'Unable to reach Ollama tags endpoint.',
                ], 503);
            }
            if (! in_array($modelToUse, $availableModels, true)) {
                return response()->json([
                    'error' => 'Selected model is not available in Ollama.',
                    'details' => [
                        'available' => $availableModels,
                        'requested' => $modelToUse,
                    ],
                ], 422);
            }

            $response = $this->chatWithOllama(
                $history,
                $userMessage,
                (string) ($context['role'] ?? $user->role),
                $context['stats'] ?? [],
                (string) ($context['employee_list'] ?? ''),
                $context['employee_data'] ?? [],
                $context['policy_snippets'] ?? [],
                $context['retrieval_meta'] ?? [],
                $contextMs,
                $tagsMs,
                $cacheKey,
                $cacheEnabled,
                $modelToUse,
                (int) $user->id,
                (string) $user->role,
                $user,
                $queryHash,
                $recentIntents,
                $conversation
            );
        }

        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $data = $response->getData(true);
            if (isset($data['response']) && is_string($data['response']) && $data['response'] !== '') {
                // Validate response accuracy against context data
                $validationResult = $this->validatorService->validateResponse($data['response'], $context['stats'] ?? []);

                if (! $validationResult['valid']) {
                    Log::warning('AI response validation failed', [
                        'user_id' => $user->id,
                        'issues' => $validationResult['issues'],
                        'original_response' => mb_substr($data['response'], 0, 200),
                    ]);

                    // If validation failed, append correction to the response
                    if ($validationResult['corrected_response']) {
                        $data['response'] .= "\n\n".$validationResult['corrected_response'];
                        $data['meta']['validation_corrected'] = true;
                        $data['meta']['validation_issues'] = $validationResult['issues'];
                    }
                }

                $this->saveMessage($conversation, 'assistant', $data['response']);

                // Log detailed metrics for analysis
                Log::info('AI chatbot response validated', [
                    'user_id' => $user->id,
                    'query_hash' => $queryHash,
                    'validation_passed' => $validationResult['valid'],
                    'response_length' => strlen($data['response']),
                ]);
            }
        }

        return $response;
    }

    public function chatStream(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        @set_time_limit(0);
        @ini_set('max_execution_time', '0');

        $validated = $request->validate([
            'message' => 'required|string|max:4000',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|in:user,assistant',
            'history.*.content' => 'required_with:history|string|max:4000',
            'model' => 'nullable|string|max:80',
            'conversation_id' => 'nullable|string|uuid',
        ]);

        $userMessage = strip_tags((string) $validated['message']);
        $history = $validated['history'] ?? [];
        if (! is_array($history)) {
            $history = [];
        }

        // Sanitize history items
        $history = array_map(function ($item) {
            return [
                'role' => $item['role'] ?? 'user',
                'content' => strip_tags((string) ($item['content'] ?? '')),
            ];
        }, $history);

        $history = array_slice($history, -20);

        $user = Auth::user();
        if (! $user) {
            return response('', 401);
        }

        $conversationId = $request->input('conversation_id');
        $conversation = $this->getOrCreateConversation($user, $conversationId);
        if (! $conversation) {
            return response('', 404);
        }

        $this->saveMessage($conversation, 'user', $userMessage);

        $normalizedMessage = $this->suggestionService->normalizeQuestion($userMessage);
        if ($normalizedMessage === '') {
            $normalizedMessage = trim($userMessage);
        }
        $queryHash = hash('sha256', $normalizedMessage);

        // Update user profile preferences based on language and intents
        $instruction = $this->detectLanguageInstruction($userMessage);
        $this->profileService->updateLanguage($user, $instruction);
        $this->userPreferencesSummary = $this->profileService->buildPreferencesSummary($user);

        $recentIntents = $this->suggestionService->recentIntents($user);

        // Check for suggestion match first (return SSE that mimics stream format)
        $matchedSuggestion = $this->suggestionService->matchSuggestion($user, $normalizedMessage);
        if ($matchedSuggestion && isset($matchedSuggestion['id'])) {
            // Skip suggestions for compound questions to force tool usage for complete answers
            if ($this->suggestionService->isCompoundQuestion($normalizedMessage) || $this->shouldPreferToolsForStatsQuestion($normalizedMessage)) {
                Log::info('Compound question detected in stream, skipping suggestion to use tools', [
                    'suggestion_id' => $matchedSuggestion['id'],
                    'query_hash' => hash('sha256', $normalizedMessage),
                ]);
            } else {
                $recentIntents = $this->suggestionService->rememberIntent($user, (string) $matchedSuggestion['id']);
                $answer = $this->suggestionService->answerForUser($user, $matchedSuggestion['id'], $userMessage);
                $source = $this->suggestionService->sourceForSuggestion($matchedSuggestion['id']);
                if ($source) {
                    $this->profileService->recordTopicsFromSources($user, [$source['source']]);
                }
                $sources = $source ? [[
                    'source' => $source['source'],
                    'url' => route('ai-chatbot.policy', ['filename' => $source['source']]),
                    'confidence' => 1.0,
                ]] : [];

                $this->saveMessage($conversation, 'assistant', $answer);

                $followups = $this->suggestionService->followUpSuggestions($user, $matchedSuggestion['id'], $recentIntents);
                $meta = [
                    'sources' => $sources,
                    'retrieval' => [],
                    'query_hash' => $queryHash,
                    'low_confidence' => false,
                    'followups' => $followups,
                    'source' => 'suggestion',
                    'suggestion_id' => $matchedSuggestion['id'],
                    'match' => $matchedSuggestion['match'] ?? null,
                ];

                return response()->stream(function () use ($answer, $meta) {
                    echo "event: meta\n";
                    echo 'data: '.json_encode(['query_hash' => $meta['query_hash']])."\n\n";
                    @ob_flush();
                    @flush();
                    echo "event: delta\n";
                    echo 'data: '.json_encode(['delta' => $answer])."\n\n";
                    @ob_flush();
                    @flush();
                    echo "event: end\n";
                    echo 'data: '.json_encode(['response' => $answer, 'meta' => $meta])."\n\n";
                    @ob_flush();
                    @flush();
                }, 200, $this->sseHeaders());
            }
        }

        $startTime = microtime(true);
        $context = $this->resolveContextForChat($user, $normalizedMessage);
        $contextMs = (int) round((microtime(true) - $startTime) * 1000);

        if (isset($context['permission_denied']) && $context['permission_denied']) {
            $msg = (string) ($context['message'] ?? 'Access denied.');
            $this->saveMessage($conversation, 'assistant', $msg);

            return response()->stream(function () use ($msg, $queryHash) {
                echo "event: meta\n";
                echo 'data: '.json_encode(['query_hash' => $queryHash])."\n\n";
                @ob_flush();
                @flush();
                echo "event: delta\n";
                echo 'data: '.json_encode(['delta' => $msg])."\n\n";
                @ob_flush();
                @flush();
                echo "event: end\n";
                echo 'data: '.json_encode([
                    'response' => $msg,
                    'meta' => [
                        'sources' => [],
                        'retrieval' => [],
                        'query_hash' => $queryHash,
                        'low_confidence' => false,
                        'followups' => [],
                        'source' => 'permission_denied',
                    ],
                ])."\n\n";
                @ob_flush();
                @flush();
            }, 200, $this->sseHeaders());
        }

        $modelOverride = trim((string) $request->input('model', ''));
        $modelToUse = $modelOverride !== '' ? $modelOverride : (string) config('services.ollama.model');
        if (! $this->isGoogleModel($modelToUse)) {
            $history = $this->compressHistory($history);
        }

        $userRole = (string) ($context['role'] ?? $user->role);
        $systemStats = $context['stats'] ?? [];
        $employeeList = (string) ($context['employee_list'] ?? '');
        $employeeData = $context['employee_data'] ?? [];
        $policySnippets = $context['policy_snippets'] ?? [];
        $retrievalMeta = $context['retrieval_meta'] ?? [];

        $policySnippetSources = array_column($policySnippets, 'source');
        if ($policySnippetSources !== []) {
            $this->profileService->recordTopicsFromSources($user, $policySnippetSources);
        }

        $cacheEnabled = (bool) config('ai_chatbot.enable_response_cache', true);
        $cacheKey = 'ai_chatbot:response:'.$user->id.':'.$queryHash.':'.$modelToUse;

        if ($this->isGoogleModel($modelToUse)) {
            return $this->chatStreamWithGemini(
                $modelToUse,
                $history,
                $userMessage,
                $userRole,
                $systemStats,
                $employeeList,
                $employeeData,
                $policySnippets,
                $retrievalMeta,
                $contextMs,
                $cacheEnabled,
                $cacheKey,
                $user,
                $queryHash,
                $conversation,
                $recentIntents
            );
        }

        $tagsStart = microtime(true);
        $availableModels = $this->getAvailableOllamaModels();
        $tagsMs = (int) round((microtime(true) - $tagsStart) * 1000);
        if ($availableModels === []) {
            return response()->stream(function () {
                echo "event: error\n";
                echo 'data: '.json_encode(['error' => 'Unable to reach Ollama tags endpoint.'])."\n\n";
                @ob_flush();
                @flush();
            }, 503, $this->sseHeaders());
        }
        if (! in_array($modelToUse, $availableModels, true)) {
            return response()->stream(function () use ($availableModels, $modelToUse) {
                echo "event: error\n";
                echo 'data: '.json_encode([
                    'error' => 'Selected model is not available in Ollama.',
                    'details' => [
                        'available' => $availableModels,
                        'requested' => $modelToUse,
                    ],
                ])."\n\n";
                @ob_flush();
                @flush();
            }, 422, $this->sseHeaders());
        }

        $timeoutSeconds = (int) config('services.ollama.chat_timeout_seconds', 60);
        $maxPredict = (int) config('services.ollama.max_predict', 512);
        $maxConcurrent = (int) config('services.ollama.max_concurrent', 2);
        $lockWaitSeconds = (int) config('services.ollama.queue_wait_seconds', 20);
        $concurrencyKey = 'ollama:concurrency';
        $lock = null;

        if ($maxConcurrent > 0) {
            $lock = Cache::lock($concurrencyKey, $timeoutSeconds + 10);
            try {
                $lock->block($lockWaitSeconds);
            } catch (LockTimeoutException) {
                return response()->stream(function () {
                    echo "event: error\n";
                    echo 'data: '.json_encode(['error' => 'AI server is busy. Please try again in a moment.'])."\n\n";
                    @ob_flush();
                    @flush();
                }, 429, $this->sseHeaders());
            }
        }

        $llmStart = microtime(true);
        $messages = $this->buildMessages($history, $userMessage, $userRole, $systemStats, $employeeList, $employeeData, $policySnippets, $conversation);
        $chatUrl = $this->getOllamaChatUrl();

        return response()->stream(function () use (
            $chatUrl,
            $modelToUse,
            $messages,
            $timeoutSeconds,
            $maxPredict,
            $llmStart,
            $contextMs,
            $tagsMs,
            $cacheEnabled,
            $cacheKey,
            $policySnippets,
            $retrievalMeta,
            $user,
            $queryHash,
            $conversation,
            $lock
        ) {
            $fullText = '';

            try {
                echo "event: meta\n";
                echo 'data: '.json_encode([
                    'model' => $modelToUse,
                    'query_hash' => $queryHash,
                ])."\n\n";
                @ob_flush();
                @flush();

                $response = Http::timeout($timeoutSeconds)->retry(0, 0, throw: false)->withHeaders([
                    'Content-Type' => 'application/json',
                ])->withOptions([
                    'stream' => true,
                ])->post($chatUrl, [
                    'model' => $modelToUse,
                    'messages' => $messages,
                    'stream' => true,
                    'options' => [
                        'temperature' => (float) config('services.ollama.temperature', 0.8),
                        'num_ctx' => (int) config('services.ollama.context_size', 4096),
                        'num_predict' => $maxPredict,
                    ],
                ]);

                if (! $response->successful()) {
                    echo "event: error\n";
                    echo 'data: '.json_encode([
                        'error' => 'Failed to get response from local AI. Please ensure Ollama is running and reachable at '.config('services.ollama.base_url', 'http://127.0.0.1:11434'),
                        'details' => [
                            'status' => $response->status(),
                            'body_snippet' => mb_substr($response->body(), 0, 1000),
                        ],
                    ])."\n\n";
                    @ob_flush();
                    @flush();

                    return;
                }

                $body = $response->toPsrResponse()->getBody();
                $buffer = '';
                $streamStart = microtime(true);
                $firstTokenMs = null;
                while (! $body->eof()) {
                    $chunk = $body->read(8192);
                    if ($chunk === '') {
                        usleep(10_000);

                        continue;
                    }
                    $buffer .= $chunk;

                    while (($pos = strpos($buffer, "\n")) !== false) {
                        $line = substr($buffer, 0, $pos);
                        $buffer = substr($buffer, $pos + 1);
                        $line = trim($line);
                        if ($line === '') {
                            continue;
                        }
                        if (! str_starts_with($line, 'data:')) {
                            continue;
                        }
                        $payload = trim(substr($line, strlen('data:')));
                        if ($payload === '[DONE]') {
                            break 2;
                        }

                        $json = json_decode($payload, true);
                        if (! is_array($json)) {
                            continue;
                        }

                        $delta = $json['message']['content'] ?? '';
                        if (is_string($delta) && $delta !== '') {
                            if ($firstTokenMs === null) {
                                $firstTokenMs = (int) round((microtime(true) - $streamStart) * 1000);
                                $slowTtfbWarnMs = (int) config('ai_chatbot.slow_ttfb_ms_warn', 1500);
                                if ($slowTtfbWarnMs > 0 && $firstTokenMs >= $slowTtfbWarnMs) {
                                    Log::warning('AI chatbot slow first token (stream)', [
                                        'user_id' => $user->id,
                                        'model' => $modelToUse,
                                        'ttfb_ms' => $firstTokenMs,
                                    ]);
                                }
                            }
                            $fullText .= $delta;
                            echo "event: delta\n";
                            echo 'data: '.json_encode(['delta' => $delta])."\n\n";
                            @ob_flush();
                            @flush();
                        }
                    }
                }

                $ollamaMs = (int) round((microtime(true) - $llmStart) * 1000);
                $totalMs = $ollamaMs + $contextMs + $tagsMs;

                if ($fullText !== '') {
                    if ($cacheEnabled) {
                        Cache::put($cacheKey, [
                            'response' => $fullText,
                            'meta' => [
                                'sources' => $policySnippets,
                                'retrieval' => $retrievalMeta,
                            ],
                        ], now()->addMinutes(10));
                    }

                    $this->saveMessage($conversation, 'assistant', $fullText);

                    Log::info('AI chatbot latency (stream)', [
                        'user_id' => $user->id,
                        'model' => $modelToUse,
                        'tags_ms' => $tagsMs,
                        'context_ms' => $contextMs,
                        'ollama_ms' => $ollamaMs,
                        'total_ms' => $totalMs,
                        'cache_enabled' => $cacheEnabled,
                    ]);
                    $slowTotalWarnMs = (int) config('ai_chatbot.slow_total_ms_warn', 8000);
                    if ($slowTotalWarnMs > 0 && $totalMs >= $slowTotalWarnMs) {
                        Log::warning('AI chatbot slow request (stream)', [
                            'user_id' => $user->id,
                            'model' => $modelToUse,
                            'tags_ms' => $tagsMs,
                            'context_ms' => $contextMs,
                            'ollama_ms' => $ollamaMs,
                            'total_ms' => $totalMs,
                        ]);
                    }
                    $this->recordMetric((int) $user->id, (string) $user->role, $queryHash, $contextMs, $ollamaMs, $totalMs, $policySnippets, $retrievalMeta, false, null);

                    $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                    $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                    $followups = $this->suggestionService->followUpSuggestions($user, null, $this->suggestionService->recentIntents($user));

                    echo "event: end\n";
                    echo 'data: '.json_encode([
                        'response' => $fullText,
                        'meta' => [
                            'sources' => $policySnippets,
                            'retrieval' => $retrievalMeta,
                            'query_hash' => $queryHash,
                            'low_confidence' => $lowConfidence,
                            'followups' => $followups,
                        ],
                    ])."\n\n";
                    @ob_flush();
                    @flush();
                } else {
                    echo "event: error\n";
                    echo 'data: '.json_encode(['error' => 'Empty response'])."\n\n";
                    @ob_flush();
                    @flush();
                }
            } catch (\Throwable $e) {
                echo "event: error\n";
                echo 'data: '.json_encode(['error' => 'Local AI service unavailable: '.$e->getMessage()])."\n\n";
                @ob_flush();
                @flush();
            } finally {
                if ($lock) {
                    try {
                        $lock->release();
                    } catch (\Throwable $e) {
                        // ignore
                    }
                }
            }
        }, 200, $this->sseHeaders());
    }

    private function chatStreamWithGemini(
        string $model,
        array $history,
        string $userMessage,
        string $userRole,
        array $systemStats,
        string $employeeList,
        array $employeeData,
        array $policySnippets,
        array $retrievalMeta,
        int $contextMs,
        bool $cacheEnabled,
        string $cacheKey,
        \App\Models\User $user,
        string $queryHash,
        AIChatbotConversation $conversation,
        array $recentIntents
    ): \Symfony\Component\HttpFoundation\Response {
        $apiKey = (string) config('services.google_genai.api_key');
        if ($apiKey === '') {
            return response()->stream(function () {
                echo "event: error\n";
                echo 'data: '.json_encode(['error' => 'Google GenAI API key is not configured.'])."\n\n";
                @ob_flush();
                @flush();
            }, 500, $this->sseHeaders());
        }

        $llmStart = microtime(true);
        $temperature = (float) config('services.google_genai.temperature', 0.7);
        $maxOutputTokens = (int) config('services.google_genai.max_output_tokens', 1024);
        $messages = $this->buildMessages($history, $userMessage, $userRole, $systemStats, $employeeList, $employeeData, $policySnippets, $conversation);
        $systemInstruction = (string) ($messages[0]['content'] ?? 'You are a helpful HR Assistant for an IT company\'s HRIS.');
        $contents = [];
        foreach (array_slice($messages, 1) as $msg) {
            $msgRole = $msg['role'] === 'assistant' ? 'model' : $msg['role'];
            if (! in_array($msgRole, ['user', 'model'], true)) {
                $msgRole = 'user';
            }
            $contents[] = [
                'role' => $msgRole,
                'parts' => [
                    ['text' => (string) $msg['content']],
                ],
            ];
        }

        return response()->stream(function () use (
            $apiKey,
            $model,
            $systemInstruction,
            $contents,
            $temperature,
            $maxOutputTokens,
            $llmStart,
            $contextMs,
            $cacheEnabled,
            $cacheKey,
            $policySnippets,
            $retrievalMeta,
            $user,
            $queryHash,
            $userMessage,
            $conversation,
            $recentIntents,
            $history,
            $userRole,
            $systemStats,
            $employeeList,
            $employeeData
        ) {
            $fullText = '';
            $streamStart = microtime(true);
            $firstTokenMs = null;
            $fallbackModel = (string) config('services.ollama.model');

            $tryOllamaFallback = function (string $reason) use (
                $fallbackModel,
                $history,
                $userMessage,
                $userRole,
                $systemStats,
                $employeeList,
                $employeeData,
                $policySnippets,
                $retrievalMeta,
                $contextMs,
                $cacheEnabled,
                $cacheKey,
                $user,
                $queryHash,
                $recentIntents,
                $conversation
            ): bool {
                if ($fallbackModel === '') {
                    return false;
                }

                $tagsStart = microtime(true);
                $availableModels = $this->getAvailableOllamaModels();
                $tagsMs = (int) round((microtime(true) - $tagsStart) * 1000);
                if ($availableModels === [] || ! in_array($fallbackModel, $availableModels, true)) {
                    return false;
                }

                $fallbackHistory = $this->compressHistory($history);
                $response = $this->chatWithOllama(
                    $fallbackHistory,
                    $userMessage,
                    $userRole,
                    $systemStats,
                    $employeeList,
                    $employeeData,
                    $policySnippets,
                    $retrievalMeta,
                    $contextMs,
                    $tagsMs,
                    $cacheKey,
                    $cacheEnabled,
                    $fallbackModel,
                    (int) $user->id,
                    (string) $user->role,
                    $user,
                    $queryHash,
                    $recentIntents
                );

                $payload = $response->getData(true);
                if (! is_array($payload) || ! isset($payload['response']) || ! is_string($payload['response'])) {
                    return false;
                }

                $this->saveMessage($conversation, 'assistant', $payload['response']);

                echo "event: end\n";
                echo 'data: '.json_encode([
                    'response' => $payload['response'],
                    'meta' => $payload['meta'] ?? [
                        'sources' => $policySnippets,
                        'retrieval' => $retrievalMeta,
                        'query_hash' => $queryHash,
                        'low_confidence' => false,
                        'followups' => [],
                    ],
                    'fallback' => $reason,
                ])."\n\n";
                @ob_flush();
                @flush();

                return true;
            };

            try {
                echo "event: meta\n";
                echo 'data: '.json_encode([
                    'model' => $model,
                    'query_hash' => $queryHash,
                ])."\n\n";
                @ob_flush();
                @flush();

                $streamLoopLimit = 3;
                $streamLoops = 0;
                $isToolCall = false;

                do {
                    $streamLoops++;
                    $isToolCall = false;

                    $response = Http::timeout(90)->retry(0, 0, throw: false)->withOptions([
                        'stream' => true,
                    ])->post($this->getGoogleGenaiStreamUrl($model, $apiKey), [
                        'systemInstruction' => [
                            'parts' => [
                                ['text' => $systemInstruction],
                            ],
                        ],
                        'contents' => $contents,
                        'tools' => $this->toolService->getTools($user),
                        'generationConfig' => [
                            'temperature' => $temperature,
                            'maxOutputTokens' => $maxOutputTokens,
                        ],
                    ]);

                    if (! $response->successful()) {
                        if ($tryOllamaFallback('gemini_http_error')) {
                            return;
                        }

                        echo "event: error\n";
                        echo 'data: '.json_encode([
                            'error' => 'Failed to get response from Google AI.',
                            'details' => [
                                'status' => $response->status(),
                                'body_snippet' => mb_substr($response->body(), 0, 1000),
                            ],
                        ])."\n\n";
                        @ob_flush();
                        @flush();

                        return;
                    }

                    $body = $response->toPsrResponse()->getBody();
                    $buffer = '';
                    while (! $body->eof()) {
                        $chunk = $body->read(8192);
                        if ($chunk === '') {
                            usleep(10_000);

                            continue;
                        }
                        $buffer .= $chunk;

                        while (($pos = strpos($buffer, "\n")) !== false) {
                            $line = substr($buffer, 0, $pos);
                            $buffer = substr($buffer, $pos + 1);
                            $line = trim($line);
                            if ($line === '') {
                                continue;
                            }
                            if (str_starts_with($line, 'data:')) {
                                $line = trim(substr($line, strlen('data:')));
                            }

                            $json = json_decode($line, true);
                            if (! is_array($json)) {
                                continue;
                            }
                            if (isset($json['error'])) {
                                if ($tryOllamaFallback('gemini_stream_error')) {
                                    return;
                                }

                                echo "event: error\n";
                                echo 'data: '.json_encode(['error' => 'Google AI error.'])."\n\n";
                                @ob_flush();
                                @flush();

                                return;
                            }

                            $parts = $json['candidates'][0]['content']['parts'] ?? [];

                            // Check for Function Call
                            if (isset($parts[0]['functionCall'])) {
                                $isToolCall = true;
                                $functionCall = $parts[0]['functionCall'];
                                $functionName = $functionCall['name'];
                                $arguments = $functionCall['args'] ?? [];

                                $toolResult = $this->toolService->handleToolCall($user, $functionName, $arguments);

                                $contents[] = [
                                    'role' => 'model',
                                    'parts' => [['functionCall' => $functionCall]],
                                ];
                                $contents[] = [
                                    'role' => 'user',
                                    'parts' => [[
                                        'functionResponse' => [
                                            'name' => $functionName,
                                            'response' => [
                                                'name' => $functionName,
                                                'content' => $toolResult,
                                            ],
                                        ],
                                    ]],
                                ];

                                // Break out of the inner string-parsing loop and the buffer-reading loop
                                break 2;
                            }

                            $delta = $parts[0]['text'] ?? '';
                            if (is_string($delta) && $delta !== '') {
                                if ($firstTokenMs === null) {
                                    $firstTokenMs = (int) round((microtime(true) - $streamStart) * 1000);
                                    $slowTtfbWarnMs = (int) config('ai_chatbot.slow_ttfb_ms_warn', 1500);
                                    if ($slowTtfbWarnMs > 0 && $firstTokenMs >= $slowTtfbWarnMs) {
                                        Log::warning('AI chatbot slow first token (gemini stream)', [
                                            'user_id' => $user->id,
                                            'model' => $model,
                                            'ttfb_ms' => $firstTokenMs,
                                        ]);
                                    }
                                }
                                $fullText .= $delta;
                                echo "event: delta\n";
                                echo 'data: '.json_encode(['delta' => $delta])."\n\n";
                                @ob_flush();
                                @flush();
                            }
                        }
                    }
                } while ($isToolCall && $streamLoops < $streamLoopLimit);

                $llmMs = (int) round((microtime(true) - $llmStart) * 1000);
                $totalMs = $llmMs + $contextMs;

                if ($fullText !== '') {
                    if ($cacheEnabled) {
                        Cache::put($cacheKey, [
                            'response' => $fullText,
                            'meta' => [
                                'sources' => $policySnippets,
                                'retrieval' => $retrievalMeta,
                            ],
                        ], now()->addMinutes(10));
                    }

                    $this->saveMessage($conversation, 'assistant', $fullText);
                    $this->recordMetric((int) $user->id, (string) $user->role, $queryHash, $contextMs, $llmMs, $totalMs, $policySnippets, $retrievalMeta, false, null);

                    Log::info('AI chatbot latency (gemini stream)', [
                        'user_id' => $user->id,
                        'model' => $model,
                        'context_ms' => $contextMs,
                        'llm_ms' => $llmMs,
                        'total_ms' => $totalMs,
                        'cache_enabled' => $cacheEnabled,
                        'ttfb_ms' => $firstTokenMs,
                    ]);
                    $slowTotalWarnMs = (int) config('ai_chatbot.slow_total_ms_warn', 8000);
                    if ($slowTotalWarnMs > 0 && $totalMs >= $slowTotalWarnMs) {
                        Log::warning('AI chatbot slow request (gemini stream)', [
                            'user_id' => $user->id,
                            'model' => $model,
                            'context_ms' => $contextMs,
                            'llm_ms' => $llmMs,
                            'total_ms' => $totalMs,
                            'ttfb_ms' => $firstTokenMs,
                        ]);
                    }
                    app(ActivityLogger::class)->log(
                        action: 'ai_chatbot.query',
                        actorUserId: $user->id,
                        role: (string) $user->role,
                        subjectType: 'ai_chatbot',
                        subjectId: null,
                        metadata: [
                            'source' => 'gemini',
                            'query_hash' => $queryHash,
                            'cache_hit' => false,
                            'error_type' => null,
                        ],
                    );

                    $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                    $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                    $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);

                    echo "event: end\n";
                    echo 'data: '.json_encode([
                        'response' => $fullText,
                        'meta' => [
                            'sources' => $policySnippets,
                            'retrieval' => $retrievalMeta,
                            'query_hash' => $queryHash,
                            'low_confidence' => $lowConfidence,
                            'followups' => $followups,
                        ],
                    ])."\n\n";
                    @ob_flush();
                    @flush();
                } else {
                    if ($tryOllamaFallback('gemini_empty_response')) {
                        return;
                    }

                    echo "event: error\n";
                    echo 'data: '.json_encode(['error' => 'Empty response'])."\n\n";
                    @ob_flush();
                    @flush();
                }
            } catch (\Throwable $e) {
                if ($tryOllamaFallback('gemini_exception')) {
                    return;
                }

                echo "event: error\n";
                echo 'data: '.json_encode(['error' => 'Google AI service unavailable: '.$e->getMessage()])."\n\n";
                @ob_flush();
                @flush();
            }
        }, 200, $this->sseHeaders());
    }

    private function sseHeaders(): array
    {
        return [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'X-Accel-Buffering' => 'no',
            'Connection' => 'keep-alive',
        ];
    }

    public function context(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'query' => 'nullable|string|max:4000',
            ]);

            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $query = (string) $request->input('query', '');
            $context = $this->contextService->getContext($user, $query)->toArray();

            Log::info('AI chatbot context generated', [
                'user_id' => $user->id,
                'role' => $context['role'] ?? null,
            ]);

            return response()->json($context);
        } catch (\Throwable $e) {
            Log::error('AI chatbot context error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to load AI context.',
            ], 500);
        }
    }

    private function chatWithGemini(
        array $history,
        string $userMessage,
        string $userRole,
        array $systemStats,
        string $employeeList,
        array $employeeData,
        array $policySnippets,
        array $retrievalMeta,
        int $contextMs,
        string $cacheKey,
        bool $cacheEnabled,
        string $model,
        int $userId,
        string $role,
        \App\Models\User $user,
        string $queryHash,
        array $recentIntents,
        ?AIChatbotConversation $conversation = null
    ): JsonResponse {
        $genaiStart = microtime(true);
        $apiKey = (string) config('services.google_genai.api_key');
        if ($apiKey === '') {
            return response()->json([
                'error' => 'Google GenAI API key is not configured.',
            ], 500);
        }

        try {
            $messages = $this->buildMessages($history, $userMessage, $userRole, $systemStats, $employeeList, $employeeData, $policySnippets, $conversation);
            $systemInstruction = (string) ($messages[0]['content'] ?? 'You are a helpful HR Assistant for an IT company\'s HRIS.');
            $contents = [];
            foreach (array_slice($messages, 1) as $msg) {
                $msgRole = $msg['role'] === 'assistant' ? 'model' : $msg['role'];
                if (! in_array($msgRole, ['user', 'model'], true)) {
                    $msgRole = 'user';
                }
                $contents[] = [
                    'role' => $msgRole,
                    'parts' => [
                        ['text' => (string) $msg['content']],
                    ],
                ];
            }

            $temperature = (float) config('services.google_genai.temperature', 0.7);
            $maxOutputTokens = (int) config('services.google_genai.max_output_tokens', 1024);
            $payload = [
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemInstruction],
                    ],
                ],
                'contents' => $contents,
                'tools' => $this->toolService->getTools($user),
                'generationConfig' => [
                    'temperature' => $temperature,
                    'maxOutputTokens' => $maxOutputTokens,
                ],
            ];
            $response = Http::timeout(90)->retry(1, 200, throw: false)->post(
                $this->getGoogleGenaiUrl($model, $apiKey),
                $payload
            );

            if ($response->successful()) {
                $data = $response->json();

                // Handle Function Call
                $parts = $data['candidates'][0]['content']['parts'] ?? [];
                if (isset($parts[0]['functionCall'])) {
                    $functionCall = $parts[0]['functionCall'];
                    $functionName = $functionCall['name'];
                    $arguments = $functionCall['args'] ?? [];

                    $toolResult = $this->toolService->handleToolCall($user, $functionName, $arguments);

                    // Append the model's tool call request
                    $contents[] = [
                        'role' => 'model',
                        'parts' => [
                            ['functionCall' => $functionCall],
                        ],
                    ];

                    // Append the backend's tool response
                    $contents[] = [
                        'role' => 'user',
                        'parts' => [
                            [
                                'functionResponse' => [
                                    'name' => $functionName,
                                    'response' => [
                                        'name' => $functionName,
                                        'content' => $toolResult,
                                    ],
                                ],
                            ],
                        ],
                    ];

                    $payload['contents'] = $contents;
                    $response = Http::timeout(90)->retry(1, 200, throw: false)->post(
                        $this->getGoogleGenaiUrl($model, $apiKey),
                        $payload
                    );

                    if ($response->successful()) {
                        $data = $response->json();
                    }
                }

                $aiResponse = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                if ($aiResponse) {
                    if ($cacheEnabled) {
                        Cache::put($cacheKey, [
                            'response' => $aiResponse,
                            'meta' => [
                                'sources' => $policySnippets,
                                'retrieval' => $retrievalMeta,
                            ],
                        ], now()->addMinutes(10));
                    }

                    $llmMs = (int) round((microtime(true) - $genaiStart) * 1000);
                    $totalMs = $llmMs + $contextMs;
                    $this->recordMetric($userId, $role, $queryHash, $contextMs, $llmMs, $totalMs, $policySnippets, $retrievalMeta, false, null);
                    app(ActivityLogger::class)->log(
                        action: 'ai_chatbot.query',
                        actorUserId: $userId,
                        role: $role,
                        subjectType: 'ai_chatbot',
                        subjectId: null,
                        metadata: [
                            'source' => 'gemini',
                            'query_hash' => $queryHash,
                            'cache_hit' => false,
                            'error_type' => null,
                        ],
                    );

                    $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                    $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                    $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);
                    $responseText = $aiResponse;
                    if ($lowConfidence) {
                        $responseText .= $this->clarificationPrompt($userMessage);
                    }
                    $responseText = $this->avoidParroting($userMessage, $responseText);

                    return response()->json([
                        'response' => $responseText,
                        'meta' => [
                            'sources' => $policySnippets,
                            'retrieval' => $retrievalMeta,
                            'query_hash' => $queryHash,
                            'low_confidence' => $lowConfidence,
                            'followups' => $followups,
                        ],
                    ]);
                }
            }

            $body = $response->body();
            $bodySnippet = mb_substr($body, 0, 1000);
            Log::error('Gemini API error', [
                'model' => $model,
                'status' => $response->status(),
                'body_snippet' => $bodySnippet,
            ]);

            $cached = $cacheEnabled ? Cache::get($cacheKey) : null;
            if (is_array($cached) && isset($cached['response'])) {
                $llmMs = (int) round((microtime(true) - $genaiStart) * 1000);
                $totalMs = $llmMs + $contextMs;
                $this->recordMetric($userId, $role, $queryHash, $contextMs, $llmMs, $totalMs, $policySnippets, $retrievalMeta, true, 'gemini_error');
                app(ActivityLogger::class)->log(
                    action: 'ai_chatbot.query',
                    actorUserId: $userId,
                    role: $role,
                    subjectType: 'ai_chatbot',
                    subjectId: null,
                    metadata: [
                        'source' => 'gemini',
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'error_type' => 'gemini_error',
                    ],
                );

                $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);

                return response()->json([
                    'response' => (string) $cached['response'],
                    'meta' => [
                        'sources' => $policySnippets,
                        'retrieval' => $retrievalMeta,
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'low_confidence' => $lowConfidence,
                        'followups' => $followups,
                    ],
                ]);
            }

            $llmMs = (int) round((microtime(true) - $genaiStart) * 1000);
            $totalMs = $llmMs + $contextMs;
            $this->recordMetric($userId, $role, $queryHash, $contextMs, $llmMs, $totalMs, $policySnippets, $retrievalMeta, false, 'gemini_error');
            app(ActivityLogger::class)->log(
                action: 'ai_chatbot.query',
                actorUserId: $userId,
                role: $role,
                subjectType: 'ai_chatbot',
                subjectId: null,
                metadata: [
                    'source' => 'gemini',
                    'query_hash' => $queryHash,
                    'cache_hit' => false,
                    'error_type' => 'gemini_error',
                ],
            );

            return response()->json([
                'error' => 'Failed to get response from Google AI.',
                'details' => [
                    'status' => $response->status(),
                    'body_snippet' => $bodySnippet,
                ],
            ], 500);
        } catch (\Throwable $e) {
            Log::error('Gemini error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            $cached = $cacheEnabled ? Cache::get($cacheKey) : null;
            if (is_array($cached) && isset($cached['response'])) {
                $llmMs = (int) round((microtime(true) - $genaiStart) * 1000);
                $totalMs = $llmMs + $contextMs;
                $this->recordMetric($userId, $role, $queryHash, $contextMs, $llmMs, $totalMs, $policySnippets, $retrievalMeta, true, 'gemini_exception');
                app(ActivityLogger::class)->log(
                    action: 'ai_chatbot.query',
                    actorUserId: $userId,
                    role: $role,
                    subjectType: 'ai_chatbot',
                    subjectId: null,
                    metadata: [
                        'source' => 'gemini',
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'error_type' => 'gemini_exception',
                    ],
                );

                $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);

                return response()->json([
                    'response' => (string) $cached['response'],
                    'meta' => [
                        'sources' => $policySnippets,
                        'retrieval' => $retrievalMeta,
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'low_confidence' => $lowConfidence,
                        'followups' => $followups,
                    ],
                ]);
            }

            $llmMs = (int) round((microtime(true) - $genaiStart) * 1000);
            $totalMs = $llmMs + $contextMs;
            $this->recordMetric($userId, $role, $queryHash, $contextMs, $llmMs, $totalMs, $policySnippets, $retrievalMeta, false, 'gemini_exception');
            app(ActivityLogger::class)->log(
                action: 'ai_chatbot.query',
                actorUserId: $userId,
                role: $role,
                subjectType: 'ai_chatbot',
                subjectId: null,
                metadata: [
                    'source' => 'gemini',
                    'query_hash' => $queryHash,
                    'cache_hit' => false,
                    'error_type' => 'gemini_exception',
                ],
            );

            return response()->json([
                'error' => 'Google AI service unavailable: '.$e->getMessage(),
            ], 500);
        }
    }

    private function chatWithOllama(
        array $history,
        string $userMessage,
        string $userRole,
        array $systemStats,
        string $employeeList,
        array $employeeData,
        array $policySnippets,
        array $retrievalMeta,
        int $contextMs,
        int $tagsMs,
        string $cacheKey,
        bool $cacheEnabled,
        string $model,
        int $userId,
        string $role,
        \App\Models\User $user,
        string $queryHash,
        array $recentIntents,
        ?AIChatbotConversation $conversation = null
    ): JsonResponse {
        $llmStart = microtime(true);
        $lock = null;
        $maxConcurrent = (int) config('services.ollama.max_concurrent', 2);
        $timeoutSeconds = (int) config('services.ollama.chat_timeout_seconds', 60);
        $maxPredict = (int) config('services.ollama.max_predict', 512);
        $lockWaitSeconds = (int) config('services.ollama.queue_wait_seconds', 20);
        $concurrencyKey = 'ollama:concurrency';

        if ($maxConcurrent > 0) {
            $lock = Cache::lock($concurrencyKey, $timeoutSeconds + 10);
            try {
                $lock->block($lockWaitSeconds);
            } catch (LockTimeoutException) {
                return response()->json([
                    'error' => 'AI server is busy. Please try again in a moment.',
                ], 429);
            }
        }

        try {
            $messages = $this->buildMessages($history, $userMessage, $userRole, $systemStats, $employeeList, $employeeData, $policySnippets, $conversation);

            $chatUrl = $this->getOllamaChatUrl();

            $response = Http::timeout($timeoutSeconds)->retry(1, 200, throw: false)->withHeaders([
                'Content-Type' => 'application/json',
            ])->post($chatUrl, [
                'model' => $model,
                'messages' => $messages,
                'stream' => false,
                'options' => [
                    'temperature' => (float) config('services.ollama.temperature', 0.8),
                    'num_ctx' => (int) config('services.ollama.context_size', 4096),
                    'num_predict' => $maxPredict,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $aiResponse = $data['message']['content'] ?? null;

                if ($aiResponse) {
                    if ($cacheEnabled) {
                        Cache::put($cacheKey, [
                            'response' => $aiResponse,
                            'meta' => [
                                'sources' => $policySnippets,
                                'retrieval' => $retrievalMeta,
                            ],
                        ], now()->addMinutes(10));
                    }

                    $ollamaMs = (int) round((microtime(true) - $llmStart) * 1000);
                    $totalMs = $ollamaMs + $contextMs + $tagsMs;
                    $this->recordMetric($userId, $role, $queryHash, $contextMs, $ollamaMs, $totalMs, $policySnippets, $retrievalMeta, false, null);

                    Log::info('AI chatbot latency', [
                        'user_id' => $userId,
                        'model' => $model,
                        'tags_ms' => $tagsMs,
                        'context_ms' => $contextMs,
                        'ollama_ms' => $ollamaMs,
                        'total_ms' => $totalMs,
                        'cache_enabled' => $cacheEnabled,
                    ]);
                    $slowTotalWarnMs = (int) config('ai_chatbot.slow_total_ms_warn', 8000);
                    if ($slowTotalWarnMs > 0 && $totalMs >= $slowTotalWarnMs) {
                        Log::warning('AI chatbot slow request', [
                            'user_id' => $userId,
                            'model' => $model,
                            'tags_ms' => $tagsMs,
                            'context_ms' => $contextMs,
                            'ollama_ms' => $ollamaMs,
                            'total_ms' => $totalMs,
                        ]);
                    }
                    app(ActivityLogger::class)->log(
                        action: 'ai_chatbot.query',
                        actorUserId: $userId,
                        role: $role,
                        subjectType: 'ai_chatbot',
                        subjectId: null,
                        metadata: [
                            'source' => 'ollama',
                            'query_hash' => $queryHash,
                            'cache_hit' => false,
                            'error_type' => null,
                        ],
                    );

                    $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                    $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                    $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);
                    $responseText = $aiResponse;
                    if ($lowConfidence) {
                        $responseText .= $this->clarificationPrompt($userMessage);
                    }
                    $responseText = $this->avoidParroting($userMessage, $responseText);

                    return response()->json([
                        'response' => $responseText,
                        'meta' => [
                            'sources' => $policySnippets,
                            'retrieval' => $retrievalMeta,
                            'query_hash' => $queryHash,
                            'low_confidence' => $lowConfidence,
                            'followups' => $followups,
                        ],
                    ]);
                }
            }

            $body = $response->body();
            $bodySnippet = mb_substr($body, 0, 1000);

            Log::error('Ollama API error', [
                'url' => $chatUrl,
                'status' => $response->status(),
                'body_snippet' => $bodySnippet,
            ]);

            $cached = $cacheEnabled ? Cache::get($cacheKey) : null;
            if (is_array($cached) && isset($cached['response'])) {
                $totalMs = (int) round((microtime(true) - $llmStart) * 1000) + $contextMs;
                $this->recordMetric($userId, $role, $queryHash, $contextMs, (int) round((microtime(true) - $llmStart) * 1000), $totalMs, $policySnippets, $retrievalMeta, true, 'ollama_error');
                app(ActivityLogger::class)->log(
                    action: 'ai_chatbot.query',
                    actorUserId: $userId,
                    role: $role,
                    subjectType: 'ai_chatbot',
                    subjectId: null,
                    metadata: [
                        'source' => 'ollama',
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'error_type' => 'ollama_error',
                    ],
                );

                $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);

                return response()->json([
                    'response' => (string) $cached['response'],
                    'meta' => [
                        'sources' => $policySnippets,
                        'retrieval' => $retrievalMeta,
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'low_confidence' => $lowConfidence,
                        'followups' => $followups,
                    ],
                ]);
            }

            $ollamaMs = (int) round((microtime(true) - $llmStart) * 1000);
            $totalMs = $ollamaMs + $contextMs + $tagsMs;
            $this->recordMetric($userId, $role, $queryHash, $contextMs, (int) round((microtime(true) - $llmStart) * 1000), $totalMs, $policySnippets, $retrievalMeta, false, 'ollama_error');
            app(ActivityLogger::class)->log(
                action: 'ai_chatbot.query',
                actorUserId: $userId,
                role: $role,
                subjectType: 'ai_chatbot',
                subjectId: null,
                metadata: [
                    'source' => 'ollama',
                    'query_hash' => $queryHash,
                    'cache_hit' => false,
                    'error_type' => 'ollama_error',
                ],
            );

            return response()->json([
                'error' => 'Failed to get response from local AI. Please ensure Ollama is running and reachable at '.config('services.ollama.base_url', 'http://127.0.0.1:11434'),
                'details' => [
                    'url' => $chatUrl,
                    'status' => $response->status(),
                    'body_snippet' => $bodySnippet,
                ],
            ], 500);

        } catch (\Throwable $e) {
            Log::error('Ollama error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            $cached = $cacheEnabled ? Cache::get($cacheKey) : null;
            if (is_array($cached) && isset($cached['response'])) {
                $totalMs = (int) round((microtime(true) - $llmStart) * 1000) + $contextMs;
                $this->recordMetric($userId, $role, $queryHash, $contextMs, (int) round((microtime(true) - $llmStart) * 1000), $totalMs, $policySnippets, $retrievalMeta, true, 'ollama_exception');
                app(ActivityLogger::class)->log(
                    action: 'ai_chatbot.query',
                    actorUserId: $userId,
                    role: $role,
                    subjectType: 'ai_chatbot',
                    subjectId: null,
                    metadata: [
                        'source' => 'ollama',
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'error_type' => 'ollama_exception',
                    ],
                );

                $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
                $lowConfidence = $maxConfidence < (float) config('ai_chatbot.min_confidence', 0.35);
                $followups = $this->suggestionService->followUpSuggestions($user, null, $recentIntents);

                return response()->json([
                    'response' => (string) $cached['response'],
                    'meta' => [
                        'sources' => $policySnippets,
                        'retrieval' => $retrievalMeta,
                        'query_hash' => $queryHash,
                        'cache_hit' => true,
                        'low_confidence' => $lowConfidence,
                        'followups' => $followups,
                    ],
                ]);
            }

            $totalMs = (int) round((microtime(true) - $llmStart) * 1000) + $contextMs;
            $this->recordMetric($userId, $role, $queryHash, $contextMs, (int) round((microtime(true) - $llmStart) * 1000), $totalMs, $policySnippets, $retrievalMeta, false, 'ollama_exception');
            app(ActivityLogger::class)->log(
                action: 'ai_chatbot.query',
                actorUserId: $userId,
                role: $role,
                subjectType: 'ai_chatbot',
                subjectId: null,
                metadata: [
                    'source' => 'ollama',
                    'query_hash' => $queryHash,
                    'cache_hit' => false,
                    'error_type' => 'ollama_exception',
                ],
            );

            return response()->json([
                'error' => 'Local AI service unavailable: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Build messages array for Ollama
     */
    private function buildMessages(array $history, string $newMessage, string $userRole, array $systemStats, string $employeeList, array $employeeData, array $policySnippets, ?AIChatbotConversation $conversation = null): array
    {
        $messages = [];

        $conversationSummary = $conversation?->hasSummary() ? (string) $conversation->summary : null;

        // System message with context
        $messages[] = [
            'role' => 'system',
            'content' => $this->getSystemPrompt($userRole, $systemStats, $employeeList, $employeeData, $policySnippets, $newMessage, $conversationSummary),
        ];

        $recentHistory = array_slice($history, -30);
        foreach ($recentHistory as $msg) {
            $role = $msg['role'] ?? 'assistant';
            if (! in_array($role, ['user', 'assistant', 'system'], true)) {
                $role = 'assistant';
            }
            $messages[] = [
                'role' => $role,
                'content' => $msg['content'],
            ];
        }

        // Add new user message
        $messages[] = [
            'role' => 'user',
            'content' => $newMessage,
        ];

        return $messages;
    }

    /**
     * Get system prompt for the AI dynamically based on role
     */
    private function getSystemPrompt(string $role, array $stats = [], string $employeeList = '', array $employeeData = [], array $policySnippets = [], string $userMessage = '', ?string $conversationSummary = null): string
    {
        $prefix = '';
        if ($conversationSummary !== null && $conversationSummary !== '') {
            $prefix = '**Earlier conversation summary:**'.PHP_EOL.$conversationSummary.PHP_EOL.PHP_EOL;
        }

        $base = storage_path('app/prompts/'.strtolower($role).'_prompt');
        $fullPath = file_exists($base.'.txt') ? $base.'.txt' : (file_exists($base.'.md') ? $base.'.md' : null);

        if ($fullPath === null) {
            return $prefix.'You are a helpful HRIS assistant. Proceed to answer the query.';
        }

        $promptTemplate = $prefix.file_get_contents($fullPath);

        $statsText = '';
        if ($role === UserRole::Hr->value && ! empty($stats)) {
            $statsText = <<<STATS
**Users (excluding Admin):**
- Total employees in system: {$stats['users']['total']}
- Active employees: {$stats['users']['active']}
- Pending approval: {$stats['users']['pending_approval']}
- HR personnel: {$stats['users']['hr_personnel']}
- Regular employees: {$stats['users']['employees']}

**Leave Applications:**
- Submitted today: {$stats['leave_applications']['total_today']}
- Pending approval: {$stats['leave_applications']['pending']}
- Approved this month: {$stats['leave_applications']['approved_this_month']}

**Recent Leave Applications (Top 5):**
{$stats['leave_applications']['recent_list']}

**Training & Development:**
- Total training programs: {$stats['training']['total_programs']}
- Pending approval: {$stats['training']['pending_approval']}
- Approved: {$stats['training']['approved']}

**Recent Training Records (Top 5):**
{$stats['training']['recent_list']}

**Personal Data Sheets (PDS):**
- Total submitted: {$stats['pds']['total_submitted']}
- Draft: {$stats['pds']['draft']}
- Pending review: {$stats['pds']['pending_review']}
- Approved: {$stats['pds']['approved']}
- Rejected: {$stats['pds']['rejected']}
STATS;
        } elseif ($role === UserRole::Admin->value && ! empty($stats)) {
            $statsText = <<<STATS
- Total Users in System: {$stats['total_users']}
- Admins: {$stats['admins']}
- HR Staff: {$stats['hr_staff']}
- Employees: {$stats['employees']}
- Active users today: {$stats['active_users_today']}

**System Info:**
- PHP Version: {$stats['php_version']}
- Laravel Version: {$stats['laravel_version']}

**Recent Activity Logs (Top 5):**
{$stats['recent_activity']}
STATS;
        }

        // Shared context for all roles: Notices and Holidays
        $sharedNoticesText = Cache::remember('ai_chatbot:shared_notices_holidays', now()->addSeconds(120), function () {
            try {
                $notices = Notice::active()->latest('created_at')->limit(5)->get()->map(function ($n) {
                    return "- {$n->title} (Expires: {$n->expires_at})";
                })->implode("\n") ?: 'No active notices.';

                $holidays = $this->formatUpcomingHolidaysText(5);

                return "\n\n**Active Notices/Announcements:**\n{$notices}".
                    "\n\n**Upcoming Calendar Holidays:**\n{$holidays}";
            } catch (\Throwable $e) {
                // Ignore if tables don't exist in some environments
                return '';
            }
        });

        if (! empty($sharedNoticesText)) {
            $statsText .= $sharedNoticesText;
        }

        $policySnippetsText = $this->formatPolicySnippets($policySnippets);
        if ($policySnippetsText !== '') {
            $statsText .= "\n\n".$policySnippetsText;
        }

        $employeeName = $employeeData['name'] ?? 'Employee';
        $employeePosition = $employeeData['position'] ?? 'Not specified';
        $leaveBalances = $employeeData['leave_balances'] ?? 'None';
        $pendingRequests = $employeeData['pending_requests'] ?? 'None';

        $laborCodePath = storage_path('app/prompts/labor_code_leaves.txt');
        $laborCodePolicies = file_exists($laborCodePath) ? file_get_contents($laborCodePath) : '';

        $cscPoliciesPath = storage_path('app/prompts/csc_leave_policies.txt');
        $cscPolicies = file_exists($cscPoliciesPath) ? file_get_contents($cscPoliciesPath) : '';

        $pdsPoliciesPath = storage_path('app/prompts/pds_policies.txt');
        $pdsPolicies = file_exists($pdsPoliciesPath) ? file_get_contents($pdsPoliciesPath) : '';

        $codeOfConductPath = storage_path('app/prompts/code_of_conduct.txt');
        $codeOfConduct = file_exists($codeOfConductPath) ? file_get_contents($codeOfConductPath) : '';

        $sslViPoliciesPath = storage_path('app/prompts/ssl_vi_policies.txt');
        $sslViPolicies = file_exists($sslViPoliciesPath) ? file_get_contents($sslViPoliciesPath) : '';

        $gsisPoliciesPath = storage_path('app/prompts/gsis_policies.txt');
        $gsisPolicies = file_exists($gsisPoliciesPath) ? file_get_contents($gsisPoliciesPath) : '';

        $spmsPoliciesPath = storage_path('app/prompts/spms_policies.txt');
        $spmsPolicies = file_exists($spmsPoliciesPath) ? file_get_contents($spmsPoliciesPath) : '';

        $paternityPoliciesPath = storage_path('app/prompts/paternity_leave_policies.txt');
        $paternityPolicies = file_exists($paternityPoliciesPath) ? file_get_contents($paternityPoliciesPath) : '';

        $soloParentPoliciesPath = storage_path('app/prompts/solo_parent_leave_policies.txt');
        $soloParentPolicies = file_exists($soloParentPoliciesPath) ? file_get_contents($soloParentPoliciesPath) : '';

        $specialWomenPoliciesPath = storage_path('app/prompts/special_leave_women_policies.txt');
        $specialWomenPolicies = file_exists($specialWomenPoliciesPath) ? file_get_contents($specialWomenPoliciesPath) : '';

        $yearEndBonusPoliciesPath = storage_path('app/prompts/year_end_bonus_policies.txt');
        $yearEndBonusPolicies = file_exists($yearEndBonusPoliciesPath) ? file_get_contents($yearEndBonusPoliciesPath) : '';

        $midYearBonusPoliciesPath = storage_path('app/prompts/mid_year_bonus_policies.txt');
        $midYearBonusPolicies = file_exists($midYearBonusPoliciesPath) ? file_get_contents($midYearBonusPoliciesPath) : '';

        $pbbPoliciesPath = storage_path('app/prompts/pbb_policies.txt');
        $pbbPolicies = file_exists($pbbPoliciesPath) ? file_get_contents($pbbPoliciesPath) : '';

        $prompt = str_replace(
            [
                '{stats}',
                '{employees}',
                '{current_date}',
                '{employee_name}',
                '{employee_position}',
                '{leave_balances}',
                '{pending_requests}',
                '{labor_code}',
                '{csc_leave_policies}',
                '{pds_policies}',
                '{code_of_conduct}',
                '{ssl_vi_policies}',
                '{gsis_policies}',
                '{spms_policies}',
                '{paternity_leave}',
                '{solo_parent_leave}',
                '{special_leave_women}',
                '{year_end_bonus}',
                '{mid_year_bonus}',
                '{pbb_policies}',
            ],
            [
                $statsText,
                $employeeList,
                now()->format('Y-m-d'),
                $employeeName,
                $employeePosition,
                $leaveBalances,
                $pendingRequests,
                $laborCodePolicies,
                $cscPolicies,
                $pdsPolicies,
                $codeOfConduct,
                $sslViPolicies,
                $gsisPolicies,
                $spmsPolicies,
                $paternityPolicies,
                $soloParentPolicies,
                $specialWomenPolicies,
                $yearEndBonusPolicies,
                $midYearBonusPolicies,
                $pbbPolicies,
            ],
            $promptTemplate
        );

        if ($this->userPreferencesSummary) {
            $prompt .= PHP_EOL.PHP_EOL.$this->userPreferencesSummary;
        }

        $roleInstructions = match ($role) {
            UserRole::Admin->value => 'Format responses with sections: Overview, Details, Action Items.',
            UserRole::Hr->value => 'Format responses with sections: Summary, HR Guidance, Next Steps.',
            default => 'Format responses with sections: Answer, Next Steps.',
        };
        $roleGuardrails = match ($role) {
            UserRole::Admin->value => 'Only provide admin-level data and avoid disclosing employee personal details unless required.',
            UserRole::Hr->value => 'Only provide HR-level data and avoid admin-only or other employees personal data.',
            default => 'Only provide the user’s own data and public policy information.',
        };

        $languageInstruction = $this->detectLanguageInstruction($userMessage);

        $definitions = PHP_EOL.PHP_EOL.
            '**DEFINITIONS (always use these):**'.PHP_EOL.
            '- "Employee count" = users with role \'employee\' linked to the employees table. EXCLUDES HR staff and admins.'.PHP_EOL.
            '- "Total users" = all user accounts (admins + HR + employees).'.PHP_EOL.
            '- When the user questions a prior answer, do NOT apologize for errors you did not make. Reaffirm the facts from the conversation history. Never contradict a prior correct statement (e.g., "excluding HR and admin" vs "including HR and admin").'.PHP_EOL;

        $guardrails = PHP_EOL.PHP_EOL.
            'STRICT RULES (always follow):'.PHP_EOL.
            '- DEFINITIONS: Use the definitions above. For "employee count" always state it EXCLUDES HR and admin.'.PHP_EOL.
            '- FOR COUNT/STATISTIC QUESTIONS: Prefer the numbers in the System context/stats over guessing. If stats are provided, use them exactly.'.PHP_EOL.
            '- COMPOUND QUESTIONS: When the user asks multiple questions in one message (e.g., "How many users? How many employees?"), answer ALL parts completely. Use tools to fetch data for each part, then combine into a single comprehensive response.'.PHP_EOL.
            '- TOOL CITATIONS: When you use a tool, explicitly cite it in the answer (e.g., "Source: System tool get_user_counts_by_role, as of 2026-03-04 23:10"). Use the tool result meta.as_of and meta.filters if present.'.PHP_EOL.
            '- TOOLS FIRST: When the user asks for live data (leave balances, pending applications, announcements, statistics, activity logs), ALWAYS call the appropriate tool function. Do NOT answer from Context Snippets alone for live data queries.'.PHP_EOL.
            '- POLICY QUESTIONS: For policy or rules questions, answer from the provided Context Snippets and cite the source document (e.g., "Source: CSC Leave Policies").'.PHP_EOL.
            '- If the information is not available in tools or Context Snippets, say "I don\'t have that information" and suggest who to contact.'.PHP_EOL.
            '- If retrieved context confidence is low, ask a brief clarifying question instead of guessing. If the policy context is incomplete or unclear, state your uncertainty and ask for clarification rather than guessing.'.PHP_EOL.
            '- Never disclose secrets, API keys, database credentials, or internal configuration.'.PHP_EOL.
            '- If a request is outside HRIS scope, say so and suggest a related action.'.PHP_EOL.
            '- For sensitive topics (legal disputes, medical, payroll), do NOT give definitive advice — point to official channels.'.PHP_EOL.
            '- '.$roleGuardrails.PHP_EOL.
            '- '.$roleInstructions.PHP_EOL.
            '- '.$languageInstruction;

        return $prompt.$definitions.$guardrails;
    }

    private function compressHistory(array $history): array
    {
        // Keep history small while preserving the most useful turns.
        if (count($history) <= 12) {
            return $history;
        }

        // Only summarize user / assistant turns; system/tool messages add less value.
        $filtered = array_values(array_filter($history, function ($item) {
            $role = $item['role'] ?? 'assistant';

            return in_array($role, ['user', 'assistant'], true);
        }));

        if (count($filtered) <= 12) {
            return $filtered;
        }

        // Keep the last N user/assistant messages intact, summarize the rest.
        $recentCount = 8;
        $older = array_slice($filtered, 0, -$recentCount);
        $recent = array_slice($filtered, -$recentCount);

        $summaryLines = [];
        foreach ($older as $item) {
            $role = $item['role'] ?? 'assistant';
            $content = trim((string) ($item['content'] ?? ''));
            if ($content === '') {
                continue;
            }

            // Prefer concise Q&A style lines.
            $prefix = $role === 'user' ? 'User' : 'Assistant';
            $summaryLines[] = $prefix.': '.mb_substr($content, 0, 180);
        }

        if ($summaryLines === []) {
            return $recent;
        }

        $summary = [
            'role' => 'system',
            'content' => 'Summary of earlier conversation:'.PHP_EOL.implode(PHP_EOL, array_slice($summaryLines, -6)),
        ];

        return array_merge([$summary], $recent);
    }

    private function detectLanguageInstruction(string $userMessage): string
    {
        $normalized = mb_strtolower($userMessage);
        $tagalogMarkers = ['ano', 'paano', 'saan', 'kailan', 'bakit', 'mga', 'po', 'ng', 'sa', 'para'];
        foreach ($tagalogMarkers as $marker) {
            if (str_contains($normalized, ' '.$marker.' ') || str_starts_with($normalized, $marker.' ')) {
                return 'Respond in Tagalog.';
            }
        }

        return 'Respond in English.';
    }

    private function clarificationPrompt(string $userMessage): string
    {
        $instruction = $this->detectLanguageInstruction($userMessage);
        if ($instruction === 'Respond in Tagalog.') {
            return PHP_EOL.PHP_EOL.'Maaari mo bang ibigay ang mas maraming detalye (hal. uri ng polisiya, petsa, o department) para makasagot ako nang tama?';
        }

        return PHP_EOL.PHP_EOL.'Can you share more details (e.g., policy type, dates, or department) so I can answer accurately?';
    }

    private function shouldPreferToolsForStatsQuestion(string $normalizedMessage): bool
    {
        $text = mb_strtolower(trim($normalizedMessage));
        if ($text === '') {
            return false;
        }

        $statMarkers = [
            'how many',
            'count',
            'total',
            'number of',
            'statistics',
            'stats',
            'breakdown',
        ];
        $roleMarkers = [
            'admin',
            'admins',
            'hr',
            'human resource',
            'employee',
            'employees',
            'users',
            'user',
        ];

        $hasStat = false;
        foreach ($statMarkers as $m) {
            if (str_contains($text, $m)) {
                $hasStat = true;
                break;
            }
        }
        if (! $hasStat) {
            return false;
        }

        foreach ($roleMarkers as $m) {
            if (str_contains($text, $m)) {
                return true;
            }
        }

        return false;
    }

    private function avoidParroting(string $userMessage, string $response): string
    {
        $normalize = static function (string $text): string {
            $text = mb_strtolower(trim($text));
            $text = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $text) ?? $text;
            $text = preg_replace('/\s+/', ' ', $text) ?? $text;

            return trim($text);
        };

        $normalizedUser = $normalize($userMessage);
        $normalizedResponse = $normalize($response);

        if ($normalizedUser === '' || $normalizedResponse === '') {
            return $response;
        }

        if ($normalizedUser === $normalizedResponse) {
            return $this->clarificationPrompt($userMessage);
        }

        return $response;
    }

    private function maybeGenerateConversationTitle(AIChatbotConversation $conversation, string $assistantContent): void
    {
        if ($conversation->title !== 'New Chat') {
            return;
        }

        $firstUserMessage = $conversation->messages()
            ->forRole('user')
            ->orderBy('sequence_number')
            ->first();

        if (! $firstUserMessage) {
            return;
        }

        $model = (string) config('services.ollama.model');
        if ($model === '') {
            return;
        }

        $messages = [
            [
                'role' => 'system',
                'content' => 'You generate short, descriptive chat titles for an HR helpdesk. Respond with only the title, at most 6 words.',
            ],
            [
                'role' => 'user',
                'content' => 'User question: '.$firstUserMessage->content.PHP_EOL.
                    'Assistant answer: '.$assistantContent.PHP_EOL.
                    'Title:',
            ],
        ];

        $result = $this->runOllamaModel($model, $messages, 20);
        $rawTitle = $result['response'] ?? null;
        if (! is_string($rawTitle)) {
            return;
        }

        $title = trim((string) preg_split('/[\r\n]+/', $rawTitle)[0]);
        $title = trim($title, " \t\n\r\0\x0B\"'");
        if ($title === '') {
            return;
        }

        $words = preg_split('/\s+/', $title) ?: [];
        if (count($words) > 6) {
            $title = implode(' ', array_slice($words, 0, 6));
        }

        if ($title === '') {
            return;
        }

        $conversation->update(['title' => $title]);
    }

    private function formatUpcomingHolidaysText(int $limit): string
    {
        $items = $this->getUpcomingHolidayItems();
        if ($items === []) {
            return 'No upcoming holidays.';
        }

        $lines = array_slice($items, 0, $limit);

        return collect($lines)->map(function (array $item) {
            $suffix = '';
            if (($item['source'] ?? '') === 'custom' && isset($item['category'])) {
                $suffix = " ({$item['category']})";
            } elseif (($item['source'] ?? '') === 'google') {
                $suffix = ' (Google)';
            }

            return "- {$item['date']}: {$item['title']}{$suffix}";
        })->implode("\n");
    }

    private function getUpcomingHolidayItems(): array
    {
        $today = now()->startOfDay();
        $end = $today->copy()->addMonths(3);
        $items = [];

        $googleEvents = $this->getGoogleCalendarHolidays($today->toDateString(), $end->toDateString());
        foreach ($googleEvents as $event) {
            $start = $event['start'] ?? null;
            if (! is_string($start) || $start === '') {
                continue;
            }
            $date = substr($start, 0, 10);
            $timestamp = strtotime($date);
            if (! $timestamp) {
                continue;
            }
            $items[] = [
                'title' => $event['title'] ?? 'Holiday',
                'date' => $date,
                'timestamp' => $timestamp,
                'source' => 'google',
            ];
        }

        $customHolidays = CustomHoliday::query()->get();
        foreach ($customHolidays as $holiday) {
            $date = $holiday->date?->copy();
            if (! $date) {
                continue;
            }

            if ($holiday->is_recurring) {
                $date = $date->setYear((int) $today->format('Y'));
                if ($date->lt($today)) {
                    $date = $date->addYear();
                }
            }

            if ($date->lt($today) || $date->gt($end)) {
                continue;
            }

            $items[] = [
                'title' => $holiday->title,
                'date' => $date->toDateString(),
                'timestamp' => $date->timestamp,
                'source' => 'custom',
                'category' => $holiday->category,
            ];
        }

        usort($items, fn ($a, $b) => $a['timestamp'] <=> $b['timestamp']);

        $deduped = [];
        foreach ($items as $item) {
            $key = $item['date'].'|'.$item['title'];
            if (isset($deduped[$key])) {
                continue;
            }
            $deduped[$key] = $item;
        }

        return array_values($deduped);
    }

    private function formatPolicySnippets(array $policySnippets): string
    {
        if (empty($policySnippets)) {
            return '';
        }

        $lines = [];
        foreach ($policySnippets as $snippet) {
            $source = $snippet['source'] ?? 'unknown';
            $excerpt = $snippet['excerpt'] ?? '';
            $confidence = isset($snippet['confidence']) ? round((float) $snippet['confidence'] * 100) : null;
            if ($excerpt === '') {
                continue;
            }
            $confidenceLabel = $confidence !== null ? " (confidence: {$confidence}%)" : '';
            $lines[] = "- [{$source}]{$confidenceLabel}: {$excerpt}";
        }

        if (empty($lines)) {
            return '';
        }

        return '**Context Snippets from Knowledge Base (cite these sources in your answer):**'."\n".implode("\n", $lines);
    }

    private function recordMetric(
        int $userId,
        string $role,
        string $queryHash,
        int $contextMs,
        int $llmMs,
        int $totalMs,
        array $policySnippets,
        array $retrievalMeta,
        bool $cacheHit,
        ?string $errorType
    ): void {
        if (! Schema::hasTable('ai_chatbot_metrics')) {
            return;
        }

        $maxConfidence = (float) ($retrievalMeta['max_confidence'] ?? 0.0);
        if ($policySnippets !== []) {
            $confidences = array_filter(array_column($policySnippets, 'confidence'), fn ($value) => is_numeric($value));
            if ($confidences !== []) {
                $maxConfidence = max($maxConfidence, (float) max($confidences));
            }
        }

        try {
            AIChatbotMetric::create([
                'user_id' => $userId,
                'role' => $role,
                'query_hash' => $queryHash,
                'context_ms' => $contextMs,
                'llm_ms' => $llmMs,
                'total_ms' => $totalMs,
                'policy_sources_count' => count($policySnippets),
                'max_confidence' => $maxConfidence,
                'cache_hit' => $cacheHit,
                'error_type' => $errorType,
            ]);
        } catch (\Throwable $e) {
            Log::warning('AI chatbot metric write failed', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function getOrCreateConversation(\App\Models\User $user, ?string $conversationId): ?AIChatbotConversation
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

    private function saveMessage(
        AIChatbotConversation $conversation,
        string $role,
        string $content,
        ?array $sources = null,
        ?string $toolUsed = null,
        ?array $toolData = null
    ): AIChatbotMessage {
        \Illuminate\Support\Facades\Log::info('saveMessage called', ['role' => $role, 'conversation_id' => $conversation->id]);
        $lastSequence = $conversation->messages()->max('sequence_number') ?? 0;

        $message = new AIChatbotMessage([
            'conversation_id' => $conversation->id,
            'role' => $role,
            'sequence_number' => $lastSequence + 1,
        ]);
        $message->content = $content;
        $message->sources = $sources;
        $message->tool_used = $toolUsed;
        $message->tool_data = $toolData;
        $message->save();

        $conversation->updateLastMessage();

        if ($role === 'assistant') {
            $this->maybeSummarizeConversation($conversation);
        }

        // Auto-generate simple title from first user message
        if ($conversation->title === 'New Chat' && $role === 'user') {
            $conversation->generateTitle($content);
        }

        // Upgrade title using local LLM after the first assistant response
        if ($conversation->title === 'New Chat' && $role === 'assistant') {
            $this->maybeGenerateConversationTitle($conversation, $content);
        }

        return $message;
    }

    /**
     * Optionally summarize a long conversation into AIChatbotConversation::summary.
     */
    private function maybeSummarizeConversation(AIChatbotConversation $conversation): void
    {
        try {
            $messageCount = $conversation->getMessageCount();
            // Only summarize once the thread is reasonably long.
            $threshold = 24;
            if ($messageCount < $threshold) {
                return;
            }

            // Do not resummarize too frequently; if a summary already exists and
            // messages have not grown much since, skip. Simple heuristic: every +12 msgs.
            $lastSequence = $conversation->messages()->max('sequence_number') ?? 0;
            $lastSummarizedSeq = (int) ($conversation->metadata['last_summarized_seq'] ?? 0);
            if ($lastSequence - $lastSummarizedSeq < 12 && $conversation->hasSummary()) {
                return;
            }

            $allMessages = $conversation->messages()->get();
            if ($allMessages->isEmpty()) {
                return;
            }

            // Keep the most recent 8 turns explicit; summarize everything before that.
            $recentLimit = 8;
            $recent = $allMessages->slice(-$recentLimit);
            $older = $allMessages->slice(0, max(0, $allMessages->count() - $recent->count()));

            if ($older->isEmpty()) {
                return;
            }

            $lines = [];
            foreach ($older as $msg) {
                $role = $msg->role;
                if (! in_array($role, ['user', 'assistant'], true)) {
                    continue;
                }
                $content = trim((string) $msg->content);
                if ($content === '') {
                    continue;
                }
                $prefix = $role === 'user' ? 'User' : 'Assistant';
                $lines[] = $prefix.': '.mb_substr($content, 0, 200);
                if (count($lines) >= 40) {
                    break;
                }
            }

            if ($lines === []) {
                return;
            }

            $promptMessages = [
                [
                    'role' => 'system',
                    'content' => 'You summarize HR helpdesk chats. Produce at most 10 short bullet points capturing user questions, key facts, and decisions. Do not include policy text verbatim.',
                ],
                [
                    'role' => 'user',
                    'content' => implode(PHP_EOL, $lines),
                ],
            ];

            $model = (string) config('services.ollama.model');
            if ($model === '') {
                return;
            }

            $result = $this->runOllamaModel($model, $promptMessages, 20);
            $summary = trim((string) ($result['response'] ?? ''));
            if ($summary === '') {
                return;
            }

            $conversation->summary = $summary;
            $metadata = $conversation->metadata ?? [];
            $metadata['last_summarized_seq'] = $lastSequence;
            $conversation->metadata = $metadata;
            $conversation->save();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('AI chatbot conversation summarization failed', [
                'conversation_id' => $conversation->id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);
        }
    }
}
