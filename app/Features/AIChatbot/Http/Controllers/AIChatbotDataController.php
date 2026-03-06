<?php

namespace App\Features\AIChatbot\Http\Controllers;

use App\Features\ActivityLogs\Services\ActivityLogger;
use App\Features\AIChatbot\Models\AIChatbotFeedback;
use App\Features\AIChatbot\Models\AIChatbotIngestionLog;
use App\Features\AIChatbot\Models\AIChatbotMetric;
use App\Features\AIChatbot\Repositories\ActivityLogRepository;
use App\Features\AIChatbot\Repositories\LeaveApplicationRepository;
use App\Features\AIChatbot\Repositories\NoticeRepository;
use App\Features\AIChatbot\Repositories\TrainingRepository;
use App\Features\AIChatbot\Repositories\UserRepository;
use App\Features\AIChatbot\Services\AIChatbotContextService;
use App\Features\AIChatbot\Services\AIChatbotEnhancementFrameworkService;
use App\Features\AIChatbot\Services\AIChatbotIngestionService;
use App\Features\AIChatbot\Services\AIChatbotSuggestionService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AIChatbotDataController extends Controller
{
    public function __construct(
        private AIChatbotContextService $contextService,
        private UserRepository $userRepository,
        private LeaveApplicationRepository $leaveRepository,
        private TrainingRepository $trainingRepository,
        private NoticeRepository $noticeRepository,
        private AIChatbotSuggestionService $suggestionService,
        private ActivityLogRepository $activityLogRepository,
        private AIChatbotEnhancementFrameworkService $enhancementFrameworkService,
    ) {}

    public function context(Request $request): JsonResponse
    {
        try {
            if (! $this->dataApiEnabled()) {
                return response()->json(['error' => 'AI data API disabled'], 503);
            }

            $request->validate([
                'query' => 'nullable|string|max:4000',
            ]);

            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $query = (string) $request->input('query', '');
            $context = $this->contextService->getContext($user, $query)->toArray();

            Log::info('AI chatbot data context generated', [
                'user_id' => $user->id,
                'role' => $context['role'] ?? null,
            ]);

            return response()->json($context);
        } catch (\Throwable $e) {
            Log::error('AI chatbot data context error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to load AI data context.',
            ], 500);
        }
    }

    public function policy(Request $request, string $filename)
    {
        try {
            if (! $this->dataApiEnabled()) {
                return response()->json(['error' => 'AI data API disabled'], 503);
            }

            $user = Auth::user();
            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $safeName = basename($filename);
            $promptDir = storage_path('app/prompts');
            $txtFiles = glob($promptDir.'/*.txt') ?: [];
            $mdFiles = glob($promptDir.'/*.md') ?: [];
            $files = array_values(array_unique(array_merge($txtFiles, $mdFiles)));
            $allowed = [];
            $fileMap = [];

            foreach ($files as $filePath) {
                if (str_ends_with($filePath, '_prompt.txt') || str_ends_with($filePath, '_prompt.md')) {
                    continue;
                }
                $basename = pathinfo($filePath, PATHINFO_BASENAME);
                $allowed[] = $basename;
                $fileMap[$basename] = $basename;
            }

            if (! in_array($safeName, $allowed, true)) {
                $altName = $safeName;
                if (str_ends_with($safeName, '.txt')) {
                    $altName = pathinfo($safeName, PATHINFO_FILENAME).'.md';
                } elseif (str_ends_with($safeName, '.md')) {
                    $altName = pathinfo($safeName, PATHINFO_FILENAME).'.txt';
                }

                if (isset($fileMap[$altName])) {
                    $safeName = $altName;
                } else {
                    return response()->json(['error' => 'Not found'], 404);
                }
            }

            if (! in_array($safeName, $allowed, true)) {
                return response()->json(['error' => 'Not found'], 404);
            }

            $fullPath = storage_path('app/prompts/'.$safeName);
            $content = @file_get_contents($fullPath);
            if ($content === false) {
                return response()->json(['error' => 'Not found'], 404);
            }

            $maxChars = (int) config('ai_chatbot.max_policy_chars', 20000);
            $content = mb_substr($content, 0, max(1000, $maxChars));

            Log::info('AI chatbot policy served', [
                'user_id' => $user->id,
                'file' => $safeName,
            ]);

            return response($content, 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
            ]);
        } catch (\Throwable $e) {
            Log::error('AI chatbot policy error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Failed to load policy.',
            ], 500);
        }
    }

    public function users(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'search' => 'nullable|string|max:200',
            'role' => 'nullable|string|max:50',
            'status' => 'nullable|string|max:50',
            'is_active' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $filters = $request->only(['search', 'role', 'status', 'is_active']);
        if ($user->isHr()) {
            $filters['role'] = $filters['role'] ?? null;
            $filters['role_exclude'] = 'admin';
        } elseif (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $perPage = (int) $request->input('per_page', 10);
        $paginator = $this->userRepository->paginate($filters, $perPage);

        return response()->json($paginator);
    }

    public function leaveApplications(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'search' => 'nullable|string|max:200',
            'status' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $filters = $request->only(['search', 'status', 'employee_id', 'date_from', 'date_to']);
        if ($user->isEmployee()) {
            $employeeId = (string) ($user->employee?->id ?? '');
            if ($employeeId !== '') {
                $filters['employee_id'] = $employeeId;
            }
        }

        $perPage = (int) $request->input('per_page', 10);
        $paginator = $this->leaveRepository->paginate($filters, $perPage);

        return response()->json($paginator);
    }

    public function trainings(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'search' => 'nullable|string|max:200',
            'status' => 'nullable|string|max:50',
            'employee_id' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $filters = $request->only(['search', 'status', 'employee_id', 'date_from', 'date_to']);
        if ($user->isEmployee()) {
            $employeeId = (string) ($user->employee?->id ?? '');
            if ($employeeId !== '') {
                $filters['employee_id'] = $employeeId;
            }
        }

        $perPage = (int) $request->input('per_page', 10);
        $paginator = $this->trainingRepository->paginate($filters, $perPage);

        return response()->json($paginator);
    }

    public function notices(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'search' => 'nullable|string|max:200',
            'active_only' => 'nullable|boolean',
            'expires_after' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $filters = $request->only(['search', 'active_only', 'expires_after']);
        $perPage = (int) $request->input('per_page', 10);
        $paginator = $this->noticeRepository->paginate($filters, $perPage);

        return response()->json($paginator);
    }

    public function health(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['status' => 'disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $ollamaBaseUrl = rtrim((string) config('services.ollama.base_url', 'http://127.0.0.1:11434'), '/');
        $ollamaModel = (string) config('services.ollama.model', 'qwen2.5:1.5b');
        $ollamaStatus = [
            'reachable' => false,
            'base_url' => $ollamaBaseUrl,
            'model' => $ollamaModel,
            'latency_ms' => null,
            'error' => null,
        ];

        $ollamaStart = microtime(true);
        try {
            $response = Http::timeout(2)->retry(1, 200, throw: false)->get($ollamaBaseUrl.'/api/tags');
            if ($response->successful()) {
                $ollamaStatus['reachable'] = true;
            } else {
                $ollamaStatus['error'] = 'HTTP '.$response->status();
            }
        } catch (\Throwable $e) {
            $ollamaStatus['error'] = $e->getMessage();
        }
        $ollamaStatus['latency_ms'] = (int) round((microtime(true) - $ollamaStart) * 1000);

        return response()->json([
            'status' => 'ok',
            'tables' => [
                'ai_chatbot_documents' => Schema::hasTable('ai_chatbot_documents'),
                'ai_chatbot_chunks' => Schema::hasTable('ai_chatbot_chunks'),
                'ai_chatbot_metrics' => Schema::hasTable('ai_chatbot_metrics'),
            ],
            'features' => [
                'retrieval' => (bool) config('ai_chatbot.enable_retrieval', true),
                'data_api' => (bool) config('ai_chatbot.enable_data_api', true),
                'response_cache' => (bool) config('ai_chatbot.enable_response_cache', true),
            ],
            'limits' => [
                'max_policy_chars' => (int) config('ai_chatbot.max_policy_chars', 20000),
                'max_sources' => (int) config('ai_chatbot.max_sources', 3),
            ],
            'ollama' => $ollamaStatus,
        ]);
    }

    public function ingest(Request $request, AIChatbotIngestionService $ingestionService): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'embed' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $embed = (bool) $request->input('embed', true);
        $startedAt = microtime(true);

        try {
            $result = $ingestionService->ingestPrompts($embed);
            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            AIChatbotIngestionLog::create([
                'user_id' => $user->id,
                'embed' => $embed,
                'documents_indexed' => (int) ($result['documents_indexed'] ?? 0),
                'chunks_created' => (int) ($result['chunks_created'] ?? 0),
                'duration_ms' => $durationMs,
                'status' => 'success',
            ]);

            return response()->json([
                'status' => 'ok',
                'documents_indexed' => $result['documents_indexed'] ?? 0,
                'chunks_created' => $result['chunks_created'] ?? 0,
                'duration_ms' => $durationMs,
            ]);
        } catch (\Throwable $e) {
            $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

            AIChatbotIngestionLog::create([
                'user_id' => $user->id,
                'embed' => $embed,
                'documents_indexed' => 0,
                'chunks_created' => 0,
                'duration_ms' => $durationMs,
                'status' => 'failed',
                'error_message' => mb_substr($e->getMessage(), 0, 255),
            ]);

            return response()->json([
                'error' => 'Ingestion failed.',
            ], 500);
        }
    }

    public function enhancementFramework(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'hours' => 'nullable|integer|min:1|max:720',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $hours = (int) $request->input('hours', (int) config('ai_chatbot.enhancement.monitoring.default_window_hours', 168));

        return response()->json(
            $this->enhancementFrameworkService->buildReport($hours)
        );
    }

    public function ingestLogs(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $logs = AIChatbotIngestionLog::query()
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(fn (AIChatbotIngestionLog $log) => [
                'id' => $log->id,
                'embed' => $log->embed,
                'documents_indexed' => $log->documents_indexed,
                'chunks_created' => $log->chunks_created,
                'duration_ms' => $log->duration_ms,
                'status' => $log->status,
                'error_message' => $log->error_message,
                'created_at' => $log->created_at?->toDateTimeString(),
            ]);

        return response()->json(['logs' => $logs]);
    }

    public function feedback(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'rating' => 'required|integer|in:-1,1',
            'message_id' => 'nullable|string|max:64',
            'query_hash' => 'nullable|string|max:64',
            'prompt' => 'nullable|string|max:1000',
            'response' => 'nullable|string|max:2000',
            'sources' => 'nullable|array',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $responseText = (string) $request->input('response', '');

        AIChatbotFeedback::create([
            'user_id' => $user->id,
            'role' => (string) $user->role,
            'query_hash' => $request->input('query_hash'),
            'message_id' => $request->input('message_id'),
            'prompt' => $request->input('prompt'),
            'rating' => (int) $request->input('rating'),
            'response_excerpt' => $responseText !== '' ? mb_substr($responseText, 0, 500) : null,
            'sources' => $request->input('sources', []),
        ]);
        app(ActivityLogger::class)->log(
            action: 'ai_chatbot.feedback',
            actorUserId: $user->id,
            role: (string) $user->role,
            subjectType: 'ai_chatbot',
            subjectId: null,
            metadata: [
                'rating' => (int) $request->input('rating'),
                'message_id' => $request->input('message_id'),
                'query_hash' => $request->input('query_hash'),
            ],
        );

        return response()->json(['status' => 'ok']);
    }

    public function exportFeedback(Request $request): StreamedResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->streamDownload(function (): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['error', 'AI data API disabled']);
                fclose($out);
            }, 'ai-chatbot-feedback.csv', ['Content-Type' => 'text/csv']);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->streamDownload(function (): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['error', 'Unauthorized']);
                fclose($out);
            }, 'ai-chatbot-feedback.csv', ['Content-Type' => 'text/csv']);
        }

        if (! $user->isAdmin()) {
            return response()->streamDownload(function (): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['error', 'Forbidden']);
                fclose($out);
            }, 'ai-chatbot-feedback.csv', ['Content-Type' => 'text/csv']);
        }

        $rating = $request->input('rating');
        $query = AIChatbotFeedback::query()->orderByDesc('created_at');
        if (in_array($rating, ['-1', '-1.0', -1, 1, '1', '1.0'], true)) {
            $query->where('rating', (int) $rating);
        }

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'ID',
                'User ID',
                'Role',
                'Rating',
                'Prompt',
                'Response',
                'Sources',
                'Query Hash',
                'Message ID',
                'Created At',
            ]);

            $query->chunkById(500, function ($rows) use ($out): void {
                foreach ($rows as $row) {
                    $sources = is_array($row->sources)
                        ? json_encode($row->sources, JSON_UNESCAPED_UNICODE)
                        : '';
                    fputcsv($out, [
                        $row->id,
                        $row->user_id ?? '',
                        $row->role ?? '',
                        $row->rating ?? 0,
                        $row->prompt ?? '',
                        $row->response_excerpt ?? '',
                        $sources,
                        $row->query_hash ?? '',
                        $row->message_id ?? '',
                        optional($row->created_at)?->toDateTimeString() ?? '',
                    ]);
                }
            });

            fclose($out);
        }, 'ai-chatbot-feedback.csv', ['Content-Type' => 'text/csv']);
    }

    public function feedbackSummary(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $aggregation = app(\App\Features\AIChatbot\Services\AIChatbotFeedbackAggregationService::class);
        $data = $aggregation->getSummary(5, 10);

        return response()->json([
            'summary' => [
                'total' => $data['total'],
                'helpful' => $data['helpful'],
                'not_helpful' => $data['not_helpful'],
            ],
            'top_failing' => $data['top_failing'],
            'most_downvoted_sources' => $data['most_downvoted_sources'],
        ]);
    }

    public function analytics(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $days = (int) $request->input('days', 14);
        $days = min(30, max(1, $days));
        $start = now()->subDays($days - 1)->startOfDay();

        $daily = AIChatbotMetric::query()
            ->select([
                DB::raw('DATE(created_at) as day'),
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(total_ms) as avg_total_ms'),
                DB::raw('AVG(max_confidence) as avg_max_confidence'),
                DB::raw('SUM(CASE WHEN cache_hit = 1 THEN 1 ELSE 0 END) as cache_hits'),
                DB::raw('SUM(CASE WHEN error_type IS NOT NULL THEN 1 ELSE 0 END) as errors'),
            ])
            ->where('created_at', '>=', $start)
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->map(fn ($row) => [
                'day' => (string) $row->day,
                'total' => (int) $row->total,
                'avg_total_ms' => (int) round((float) $row->avg_total_ms),
                'avg_max_confidence' => round((float) ($row->avg_max_confidence ?? 0), 2),
                'cache_hits' => (int) $row->cache_hits,
                'cache_hit_ratio' => (int) $row->total > 0
                    ? round((int) $row->cache_hits / (int) $row->total, 2)
                    : 0,
                'errors' => (int) $row->errors,
            ]);

        $byRole = AIChatbotMetric::query()
            ->select([
                'role',
                DB::raw('COUNT(*) as total'),
                DB::raw('AVG(total_ms) as avg_total_ms'),
                DB::raw('SUM(CASE WHEN error_type IS NOT NULL THEN 1 ELSE 0 END) as errors'),
            ])
            ->where('created_at', '>=', $start)
            ->groupBy('role')
            ->orderBy('role')
            ->get()
            ->map(fn ($row) => [
                'role' => (string) $row->role,
                'total' => (int) $row->total,
                'avg_total_ms' => (int) round((float) $row->avg_total_ms),
                'errors' => (int) $row->errors,
            ]);

        $errorTypes = AIChatbotMetric::query()
            ->select([
                'error_type',
                DB::raw('COUNT(*) as total'),
            ])
            ->whereNotNull('error_type')
            ->where('created_at', '>=', $start)
            ->groupBy('error_type')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(fn ($row) => [
                'error_type' => (string) $row->error_type,
                'total' => (int) $row->total,
            ]);

        return response()->json([
            'range' => [
                'days' => $days,
                'start' => $start->toDateString(),
                'end' => now()->toDateString(),
            ],
            'daily' => $daily,
            'by_role' => $byRole,
            'error_types' => $errorTypes,
        ]);
    }

    public function exportMetrics(Request $request): StreamedResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->streamDownload(function (): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['error', 'AI data API disabled']);
                fclose($out);
            }, 'ai-chatbot-metrics.csv', ['Content-Type' => 'text/csv']);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->streamDownload(function (): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['error', 'Unauthorized']);
                fclose($out);
            }, 'ai-chatbot-metrics.csv', ['Content-Type' => 'text/csv']);
        }

        if (! $user->isAdmin()) {
            return response()->streamDownload(function (): void {
                $out = fopen('php://output', 'w');
                fputcsv($out, ['error', 'Forbidden']);
                fclose($out);
            }, 'ai-chatbot-metrics.csv', ['Content-Type' => 'text/csv']);
        }

        $query = AIChatbotMetric::query()->orderByDesc('created_at');

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'ID',
                'User ID',
                'Role',
                'Query Hash',
                'Context MS',
                'LLM MS',
                'Total MS',
                'Sources Count',
                'Max Confidence',
                'Cache Hit',
                'Error Type',
                'Created At',
            ]);

            $query->chunkById(500, function ($rows) use ($out): void {
                foreach ($rows as $row) {
                    fputcsv($out, [
                        $row->id,
                        $row->user_id ?? '',
                        $row->role ?? '',
                        $row->query_hash ?? '',
                        $row->context_ms ?? 0,
                        $row->llm_ms ?? 0,
                        $row->total_ms ?? 0,
                        $row->policy_sources_count ?? 0,
                        $row->max_confidence ?? 0,
                        $row->cache_hit ? 1 : 0,
                        $row->error_type ?? '',
                        optional($row->created_at)?->toDateTimeString() ?? '',
                    ]);
                }
            });

            fclose($out);
        }, 'ai-chatbot-metrics.csv', ['Content-Type' => 'text/csv']);
    }

    public function policyCoverage(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $required = config('ai_chatbot.policy_required_files', []);
        $staleDays = (int) config('ai_chatbot.policy_stale_days', 180);
        $now = now();
        $basePath = storage_path('app/prompts');

        $missing = [];
        $outdated = [];
        foreach ($required as $file) {
            $full = $basePath.DIRECTORY_SEPARATOR.$file;
            if (! file_exists($full)) {
                $missing[] = $file;

                continue;
            }
            $mtime = filemtime($full);
            if ($mtime === false) {
                continue;
            }
            $updatedAt = $now->copy()->setTimestamp($mtime);
            $ageDays = $updatedAt->diffInDays($now);
            if ($ageDays >= $staleDays) {
                $outdated[] = [
                    'file' => $file,
                    'age_days' => $ageDays,
                    'updated_at' => $updatedAt->toDateTimeString(),
                ];
            }
        }

        $existing = glob($basePath.DIRECTORY_SEPARATOR.'*.txt') ?: [];
        $extras = [];
        foreach ($existing as $path) {
            $name = basename($path);
            if (str_ends_with($name, '_prompt.txt')) {
                continue;
            }
            if (! in_array($name, $required, true)) {
                $extras[] = $name;
            }
        }

        return response()->json([
            'missing' => $missing,
            'outdated' => $outdated,
            'extras' => $extras,
        ]);
    }

    public function suggestions(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $suggestions = $this->suggestionService->suggestionsForUser($user);
        $recentIntents = $this->suggestionService->recentIntents($user);

        return response()->json([
            'suggestions' => $suggestions,
            'recent_intents' => $recentIntents,
        ]);
    }

    public function suggestionAnswer(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'id' => 'required|string|max:100',
            'question' => 'nullable|string|max:4000',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $answer = $this->suggestionService->answerForUser(
                $user,
                (string) $request->input('id'),
                $request->filled('question') ? (string) $request->input('question') : null
            );
            $source = $this->suggestionService->sourceForSuggestion((string) $request->input('id'));
            $sources = $source ? [[
                'source' => $source['source'],
                'url' => route('ai-chatbot.policy', ['filename' => $source['source']]),
                'confidence' => 1.0,
            ]] : [];
            $recentIntents = $this->suggestionService->rememberIntent($user, (string) $request->input('id'));

            return response()->json([
                'answer' => $answer,
                'meta' => [
                    'sources' => $sources,
                    'followups' => $this->suggestionService->followUpSuggestions($user, (string) $request->input('id'), $recentIntents),
                ],
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('AI chatbot suggestion error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
            ]);

            return response()->json(['error' => 'Failed to load suggestion answer.'], 500);
        }
    }

    public function activityLogs(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $request->validate([
            'action' => 'nullable|string|max:200',
            'subject_type' => 'nullable|string|max:200',
            'actor_id' => 'nullable|string|max:50',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'per_page' => 'nullable|integer|min:1|max:50',
            'limit' => 'nullable|integer|min:1|max:100',
        ]);

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Activity logs are admin-only; HR and Employee cannot access
        if (! $user->isAdmin()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        // If limit is provided, return simple list without pagination
        if ($request->filled('limit')) {
            $limit = (int) $request->input('limit', 5);
            $logs = $this->activityLogRepository->recent($limit);

            return response()->json([
                'data' => $logs->map(fn ($log) => [
                    'id' => $log->id,
                    'action' => $log->action,
                    'subject_type' => $log->subject_type,
                    'subject_id' => $log->subject_id,
                    'actor' => $log->actor ? [
                        'id' => $log->actor->id,
                        'name' => trim($log->actor->first_name.' '.$log->actor->last_name),
                    ] : null,
                    'created_at' => $log->created_at?->toDateTimeString(),
                ]),
                'meta' => ['limit' => $limit, 'count' => $logs->count()],
            ]);
        }

        $filters = $request->only(['action', 'subject_type', 'actor_id', 'date_from', 'date_to']);
        $perPage = (int) $request->input('per_page', 10);
        $paginator = $this->activityLogRepository->paginate($filters, $perPage);

        return response()->json($paginator);
    }

    /**
     * Get personal insights for the authenticated user
     * Includes: conversation count, message count, feedback given
     */
    public function personalInsights(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Get conversation stats
            $conversationCount = DB::table('ai_chatbot_conversations')
                ->where('user_id', $user->id)
                ->count();

            // Get message stats
            $messageCount = DB::table('ai_chatbot_messages')
                ->join('ai_chatbot_conversations', 'ai_chatbot_messages.conversation_id', '=', 'ai_chatbot_conversations.id')
                ->where('ai_chatbot_conversations.user_id', $user->id)
                ->count();

            // Get recent conversations (last 5)
            $recentConversations = DB::table('ai_chatbot_conversations')
                ->where('user_id', $user->id)
                ->orderBy('last_message_at', 'desc')
                ->limit(5)
                ->get(['id', 'title', 'message_count', 'last_message_at', 'created_at']);

            // Get user's most used models
            $modelUsage = DB::table('ai_chatbot_metrics')
                ->where('user_id', $user->id)
                ->select('model', DB::raw('COUNT(*) as count'))
                ->groupBy('model')
                ->orderBy('count', 'desc')
                ->limit(3)
                ->get();

            return response()->json([
                'conversation_count' => $conversationCount,
                'message_count' => $messageCount,
                'recent_conversations' => $recentConversations,
                'model_usage' => $modelUsage,
                'user_id' => $user->id,
            ]);
        } catch (\Throwable $e) {
            Log::error('AI chatbot personal insights error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'error' => 'Failed to load personal insights.',
            ], 500);
        }
    }

    /**
     * Get personal feedback history for the authenticated user
     */
    public function personalFeedback(Request $request): JsonResponse
    {
        if (! $this->dataApiEnabled()) {
            return response()->json(['error' => 'AI data API disabled'], 503);
        }

        $user = Auth::user();
        if (! $user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $feedback = AIChatbotFeedback::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(20)
                ->get(['id', 'prompt', 'rating', 'response', 'created_at']);

            $summary = [
                'total' => AIChatbotFeedback::where('user_id', $user->id)->count(),
                'helpful' => AIChatbotFeedback::where('user_id', $user->id)->where('rating', '>', 0)->count(),
                'not_helpful' => AIChatbotFeedback::where('user_id', $user->id)->where('rating', '<', 0)->count(),
            ];

            return response()->json([
                'feedback' => $feedback,
                'summary' => $summary,
            ]);
        } catch (\Throwable $e) {
            Log::error('AI chatbot personal feedback error', [
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'error' => 'Failed to load personal feedback.',
            ], 500);
        }
    }

    private function dataApiEnabled(): bool
    {
        return (bool) config('ai_chatbot.enable_data_api', true);
    }
}
