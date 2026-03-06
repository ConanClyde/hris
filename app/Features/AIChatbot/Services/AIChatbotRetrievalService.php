<?php

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Models\AIChatbotChunk;
use App\Features\AIChatbot\Models\AIChatbotDocument;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AIChatbotRetrievalService
{
    public function __construct(
        private AIChatbotDocumentIndexer $indexer,
        private AIChatbotEmbeddingService $embeddingService
    ) {}

    /**
     * @param  array<string>|null  $topics  Optional topics from user profile (e.g. ["leave_policies", "PDS"])
     */
    public function retrieve(string $query, int $limit = 3, ?string $role = null, ?array $topics = null): array
    {
        $normalizedQuery = trim($query);
        if ($normalizedQuery === '') {
            return ['snippets' => [], 'meta' => $this->buildMeta(0, 0, 0.0)];
        }

        $topicsKey = $topics !== null ? implode(',', $topics) : '';
        $cacheKey = 'ai_chatbot:retrieval:'.md5($normalizedQuery).':'.$limit.':'.($role ?? 'all').':'.$topicsKey;

        return Cache::remember($cacheKey, now()->addSeconds(120), function () use ($normalizedQuery, $limit, $role, $topics) {
            $terms = $this->extractQueryTerms($normalizedQuery);
            $embeddingResult = [];

            if (config('ai_chatbot.enable_embeddings', true) && Schema::hasTable('ai_chatbot_chunks')) {
                $embedding = $this->embeddingService->embed($normalizedQuery);
                if ($embedding !== []) {
                    $embeddingResult = $this->retrieveFromChunks($embedding, max(3, $limit), $role);
                }
            }

            if (empty($terms)) {
                if ($embeddingResult !== []) {
                    return $this->applyRoleTopicBias($embeddingResult, $role, $topics ?? []);
                }

                return ['snippets' => [], 'meta' => $this->buildMeta(0, 0, 0.0)];
            }

            $keywordResult = $this->retrieveKeywordSnippets($terms, max(3, $limit), $role, $topics ?? []);

            if ($embeddingResult !== []) {
                $merged = $this->mergeHybridResults($embeddingResult, $keywordResult, $limit, count($terms));

                return $this->applyRoleTopicBias($merged, $role, $topics ?? []);
            }

            return $this->applyRoleTopicBias($keywordResult, $role, $topics ?? []);
        });
    }

    /**
     * @param  array<string>  $topics
     */
    private function retrieveKeywordSnippets(array $terms, int $limit, ?string $role = null, array $topics = []): array
    {
        $minConfidence = (float) config('ai_chatbot.min_confidence', 0.35);

        if (! Schema::hasTable('ai_chatbot_documents')) {
            $fallback = $this->fallbackToFileScan($terms, $limit);

            return [
                'snippets' => $fallback['snippets'],
                'meta' => $this->buildMeta(0, count($terms), $fallback['max_confidence'], 'keyword'),
            ];
        }

        try {
            // Optimized query: only fetch documents that contain at least one term
            // instead of loading ALL documents
            $documents = AIChatbotDocument::query()
                ->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhereJsonContains('tokens', $term);
                    }
                })
                ->orWhere(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhere('content', 'LIKE', '%'.$term.'%');
                    }
                })
                ->limit(100) // Limit to most relevant documents
                ->get();
        } catch (QueryException $e) {
            $fallback = $this->fallbackToFileScan($terms, $limit);

            return [
                'snippets' => $fallback['snippets'],
                'meta' => $this->buildMeta(0, count($terms), $fallback['max_confidence'], 'keyword'),
            ];
        }

        if ($documents->isEmpty()) {
            $this->indexer->indexPrompts();
            // Retry with indexed documents
            $documents = AIChatbotDocument::query()
                ->where(function ($query) use ($terms) {
                    foreach ($terms as $term) {
                        $query->orWhereJsonContains('tokens', $term);
                    }
                })
                ->limit(100)
                ->get();
        }

        if ($documents->isEmpty()) {
            $fallback = $this->fallbackToFileScan($terms, $limit);

            return [
                'snippets' => $fallback['snippets'],
                'meta' => $this->buildMeta(0, count($terms), $fallback['max_confidence'], 'keyword'),
            ];
        }

        $docCount = $documents->count();
        $docFrequency = $this->buildDocFrequency($documents, $terms);

        $scored = [];
        foreach ($documents as $doc) {
            $tokens = is_array($doc->tokens) ? $doc->tokens : [];
            $score = 0.0;
            foreach ($terms as $term) {
                $tf = (int) ($tokens[$term] ?? 0);
                if ($tf <= 0) {
                    // Fallback to content search if token not found
                    $tf = substr_count(mb_strtolower($doc->content), $term);
                }
                if ($tf <= 0) {
                    continue;
                }
                $idf = log((1 + $docCount) / (1 + ($docFrequency[$term] ?? 0))) + 1;
                $score += $tf * $idf;
            }
            if ($score <= 0.0) {
                continue;
            }

            $bias = $this->roleTopicBiasMultiplier((string) $doc->source, $role, $topics);
            $score *= $bias;

            $scored[] = [
                'source' => $doc->source,
                'excerpt' => $this->extractExcerpt((string) $doc->content, $terms),
                'score' => $score,
            ];
        }

        usort($scored, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $maxScore = 0.0;
        foreach ($scored as $item) {
            $maxScore = max($maxScore, (float) $item['score']);
        }

        $normalized = [];
        foreach ($scored as $item) {
            $confidence = $maxScore > 0 ? round(((float) $item['score']) / $maxScore, 2) : 0.0;
            $normalized[] = [
                'source' => $item['source'],
                'excerpt' => $item['excerpt'],
                'score' => $item['score'],
                'confidence' => $confidence,
                'url' => route('ai-chatbot.policy', ['filename' => $item['source']]),
            ];
        }

        $filtered = array_values(array_filter($normalized, function (array $item) use ($minConfidence): bool {
            return ($item['confidence'] ?? 0.0) >= $minConfidence;
        }));

        $snippets = array_slice($filtered, 0, $limit);
        $maxConfidence = $snippets !== [] ? max(array_column($snippets, 'confidence')) : 0.0;

        return [
            'snippets' => $snippets,
            'meta' => $this->buildMeta($docCount, count($terms), $maxConfidence, 'keyword'),
        ];
    }

    private function mergeHybridResults(array $embeddingResult, array $keywordResult, int $limit, int $termCount): array
    {
        $embeddingWeight = (float) config('ai_chatbot.embedding_weight', 0.7);
        $keywordWeight = (float) config('ai_chatbot.keyword_weight', 0.3);
        $minConfidence = (float) config('ai_chatbot.min_confidence', 0.35);

        $combined = [];
        foreach ($embeddingResult['snippets'] ?? [] as $item) {
            $source = (string) ($item['source'] ?? '');
            if ($source === '') {
                continue;
            }
            $combined[$source] = [
                'source' => $source,
                'excerpt' => $item['excerpt'] ?? '',
                'url' => $item['url'] ?? null,
                'score' => $embeddingWeight * (float) ($item['confidence'] ?? 0),
            ];
        }

        foreach ($keywordResult['snippets'] ?? [] as $item) {
            $source = (string) ($item['source'] ?? '');
            if ($source === '') {
                continue;
            }
            if (! isset($combined[$source])) {
                $combined[$source] = [
                    'source' => $source,
                    'excerpt' => $item['excerpt'] ?? '',
                    'url' => $item['url'] ?? null,
                    'score' => 0.0,
                ];
            }
            $combined[$source]['score'] += $keywordWeight * (float) ($item['confidence'] ?? 0);
            if (($combined[$source]['excerpt'] ?? '') === '' && ! empty($item['excerpt'])) {
                $combined[$source]['excerpt'] = $item['excerpt'];
            }
            if (! empty($item['url'])) {
                $combined[$source]['url'] = $item['url'];
            }
        }

        $merged = array_values($combined);
        usort($merged, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $maxScore = 0.0;
        foreach ($merged as $item) {
            $maxScore = max($maxScore, (float) $item['score']);
        }

        $normalized = [];
        foreach ($merged as $item) {
            $confidence = $maxScore > 0 ? round(((float) $item['score']) / $maxScore, 2) : 0.0;
            $normalized[] = [
                'source' => $item['source'],
                'excerpt' => $item['excerpt'],
                'score' => $item['score'],
                'confidence' => $confidence,
                'url' => $item['url'] ?? route('ai-chatbot.policy', ['filename' => $item['source']]),
            ];
        }

        $filtered = array_values(array_filter($normalized, function (array $item) use ($minConfidence): bool {
            return ($item['confidence'] ?? 0.0) >= $minConfidence;
        }));

        $snippets = array_slice($filtered, 0, $limit);
        $maxConfidence = $snippets !== [] ? max(array_column($snippets, 'confidence')) : 0.0;

        return [
            'snippets' => $snippets,
            'meta' => $this->buildHybridMeta(
                $keywordResult['meta']['doc_count'] ?? 0,
                $termCount,
                $maxConfidence,
                count($embeddingResult['snippets'] ?? []),
                count($keywordResult['snippets'] ?? [])
            ),
        ];
    }

    private function retrieveFromChunks(array $queryEmbedding, int $limit = 3, ?string $role = null): array
    {
        $maxChunks = (int) config('ai_chatbot.max_chunks', 800);
        $chunks = AIChatbotChunk::query()
            ->select(['source', 'content', 'embedding', 'visibility'])
            ->limit(max(1, $maxChunks))
            ->get();

        if ($chunks->isEmpty()) {
            return ['snippets' => [], 'meta' => $this->buildEmbeddingMeta(0, 0.0)];
        }

        $scored = [];
        foreach ($chunks as $chunk) {
            $visibility = is_array($chunk->visibility) ? $chunk->visibility : [];
            if ($role && $visibility !== [] && ! in_array($role, $visibility, true) && ! in_array('all', $visibility, true)) {
                continue;
            }

            $embedding = is_array($chunk->embedding) ? $chunk->embedding : [];
            if ($embedding === []) {
                continue;
            }

            $score = $this->cosineSimilarity($queryEmbedding, $embedding);
            if ($score <= 0) {
                continue;
            }

            $scored[] = [
                'source' => $chunk->source,
                'excerpt' => $this->excerptFromContent((string) $chunk->content),
                'score' => $score,
            ];
        }

        usort($scored, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $maxScore = 0.0;
        foreach ($scored as $item) {
            $maxScore = max($maxScore, (float) $item['score']);
        }

        $normalized = [];
        foreach ($scored as $item) {
            $confidence = $maxScore > 0 ? round(((float) $item['score']) / $maxScore, 2) : 0.0;
            $normalized[] = [
                'source' => $item['source'],
                'excerpt' => $item['excerpt'],
                'score' => $item['score'],
                'confidence' => $confidence,
                'url' => route('ai-chatbot.policy', ['filename' => $item['source']]),
            ];
        }

        $snippets = array_slice($normalized, 0, $limit);
        $maxConfidence = $snippets !== [] ? max(array_column($snippets, 'confidence')) : 0.0;

        return [
            'snippets' => $snippets,
            'meta' => $this->buildEmbeddingMeta(count($scored), $maxConfidence),
        ];
    }

    private function buildDocFrequency($documents, array $terms): array
    {
        $frequency = [];
        foreach ($terms as $term) {
            $count = 0;
            foreach ($documents as $doc) {
                $tokens = is_array($doc->tokens) ? $doc->tokens : [];
                if (! empty($tokens[$term])) {
                    $count++;
                }
            }
            $frequency[$term] = $count;
        }

        return $frequency;
    }

    private function fallbackToFileScan(array $terms, int $limit): array
    {
        $txtFiles = glob(storage_path('app/prompts/*.txt')) ?: [];
        $mdFiles = glob(storage_path('app/prompts/*.md')) ?: [];
        $files = array_values(array_unique(array_merge($txtFiles, $mdFiles)));
        $scored = [];

        foreach ($files as $filePath) {
            if (str_ends_with($filePath, '_prompt.txt') || str_ends_with($filePath, '_prompt.md')) {
                continue;
            }

            $content = @file_get_contents($filePath);
            if ($content === false) {
                continue;
            }

            $lower = mb_strtolower($content);
            $score = 0;
            foreach ($terms as $term) {
                $score += substr_count($lower, $term);
            }

            if ($score <= 0) {
                continue;
            }

            $source = pathinfo($filePath, PATHINFO_BASENAME);
            $scored[] = [
                'source' => $source,
                'excerpt' => $this->extractExcerpt($content, $terms),
                'score' => $score,
            ];
        }

        usort($scored, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $maxScore = 0;
        foreach ($scored as $item) {
            $maxScore = max($maxScore, (int) $item['score']);
        }

        $minConfidence = (float) config('ai_chatbot.min_confidence', 0.35);

        $normalized = [];
        foreach ($scored as $item) {
            $confidence = $maxScore > 0 ? round(((int) $item['score']) / $maxScore, 2) : 0.0;
            $normalized[] = [
                'source' => $item['source'],
                'excerpt' => $item['excerpt'],
                'score' => $item['score'],
                'confidence' => $confidence,
                'url' => route('ai-chatbot.policy', ['filename' => $item['source']]),
            ];
        }

        $filtered = array_values(array_filter($normalized, function (array $item) use ($minConfidence): bool {
            return ($item['confidence'] ?? 0.0) >= $minConfidence;
        }));

        $snippets = array_slice($filtered, 0, $limit);
        $maxConfidence = $snippets !== [] ? max(array_column($snippets, 'confidence')) : 0.0;

        return [
            'snippets' => $snippets,
            'max_confidence' => $maxConfidence,
        ];
    }

    private function extractQueryTerms(string $query): array
    {
        $rawTerms = preg_split('/\s+/', mb_strtolower($query)) ?: [];
        $stopWords = [
            'the', 'and', 'for', 'with', 'that', 'this', 'from', 'are', 'was', 'were', 'has', 'have', 'had',
            'your', 'you', 'our', 'can', 'will', 'what', 'how', 'when', 'where', 'why', 'who', 'which', 'is',
            'to', 'of', 'in', 'on', 'by', 'an', 'a', 'as', 'at', 'it', 'be', 'or', 'if', 'not',
        ];

        $terms = [];
        foreach ($rawTerms as $term) {
            $clean = trim($term);
            if ($clean === '' || mb_strlen($clean) < 3) {
                continue;
            }
            if (in_array($clean, $stopWords, true)) {
                continue;
            }
            $terms[] = $clean;
        }

        return array_values(array_unique($terms));
    }

    private function extractExcerpt(string $content, array $terms): string
    {
        $lower = mb_strtolower($content);
        $position = null;

        foreach ($terms as $term) {
            $termPos = mb_stripos($lower, $term);
            if ($termPos !== false) {
                $position = $position === null ? $termPos : min($position, $termPos);
            }
        }

        if ($position === null) {
            $position = 0;
        }

        $start = max(0, $position - 200);
        $excerpt = mb_substr($content, $start, 500);
        $excerpt = preg_replace('/\s+/', ' ', $excerpt) ?? $excerpt;

        return trim($excerpt);
    }

    private function buildMeta(int $docCount, int $termCount, float $maxConfidence, string $retrieval = 'keyword'): array
    {
        return [
            'doc_count' => $docCount,
            'term_count' => $termCount,
            'max_confidence' => $maxConfidence,
            'retrieval' => $retrieval,
        ];
    }

    private function buildEmbeddingMeta(int $chunkCount, float $maxConfidence): array
    {
        return [
            'chunk_count' => $chunkCount,
            'max_confidence' => $maxConfidence,
            'retrieval' => 'embeddings',
        ];
    }

    private function buildHybridMeta(int $docCount, int $termCount, float $maxConfidence, int $embeddingCount, int $keywordCount): array
    {
        return [
            'doc_count' => $docCount,
            'term_count' => $termCount,
            'max_confidence' => $maxConfidence,
            'embedding_count' => $embeddingCount,
            'keyword_count' => $keywordCount,
            'retrieval' => 'hybrid',
        ];
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        $len = min(count($a), count($b));
        if ($len === 0) {
            return 0.0;
        }

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        for ($i = 0; $i < $len; $i++) {
            $va = (float) $a[$i];
            $vb = (float) $b[$i];
            $dot += $va * $vb;
            $normA += $va * $va;
            $normB += $vb * $vb;
        }

        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    /**
     * Apply small score boost for role- or topic-relevant sources.
     *
     * @param  array<string>  $topics
     */
    private function roleTopicBiasMultiplier(string $source, ?string $role, array $topics): float
    {
        $base = pathinfo($source, PATHINFO_FILENAME);
        $multiplier = 1.0;

        if ($role === 'employee') {
            $employeePref = ['leave', 'pds', 'labor', 'csc', 'code_of_conduct', 'ssl', 'gsis', 'spms'];
            foreach ($employeePref as $pref) {
                if (str_contains($base, $pref)) {
                    $multiplier = 1.1;

                    break;
                }
            }
        } elseif (in_array($role, ['hr', 'admin'], true)) {
            $hrPref = ['csc', 'paternity', 'solo_parent', 'special_leave', 'pbb', 'year_end', 'mid_year', 'spms'];
            foreach ($hrPref as $pref) {
                if (str_contains($base, $pref)) {
                    $multiplier = 1.1;

                    break;
                }
            }
        }

        foreach ($topics as $topic) {
            $topicNorm = strtolower(str_replace([' ', '-'], '_', $topic));
            if (str_contains($base, $topicNorm)) {
                $multiplier = max($multiplier, 1.15);

                break;
            }
        }

        return $multiplier;
    }

    /**
     * @param  array<string>  $topics
     */
    private function applyRoleTopicBias(array $result, ?string $role, array $topics): array
    {
        $snippets = $result['snippets'] ?? [];
        if ($snippets === []) {
            return $result;
        }

        foreach ($snippets as $i => $snippet) {
            $source = (string) ($snippet['source'] ?? '');
            $conf = (float) ($snippet['confidence'] ?? 0);
            $bias = $this->roleTopicBiasMultiplier($source, $role, $topics);
            $snippets[$i]['confidence'] = round(min(1.0, $conf * $bias), 2);
        }

        usort($snippets, fn ($a, $b) => ($b['confidence'] ?? 0) <=> ($a['confidence'] ?? 0));
        $result['snippets'] = $snippets;

        return $result;
    }

    private function excerptFromContent(string $content): string
    {
        $excerpt = mb_substr($content, 0, 500);
        $excerpt = preg_replace('/\s+/', ' ', $excerpt) ?? $excerpt;

        return trim($excerpt);
    }
}
