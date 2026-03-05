<?php

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Models\AIChatbotChunk;
use App\Features\AIChatbot\Models\AIChatbotDocument;

class AIChatbotIngestionService
{
    public function __construct(
        private AIChatbotDocumentIndexer $indexer,
        private AIChatbotEmbeddingService $embeddingService
    ) {}

    public function ingestPrompts(bool $embed = true): array
    {
        $indexed = $this->indexer->indexPrompts();
        $documents = AIChatbotDocument::query()->get();
        $chunksCreated = 0;

        /** @var AIChatbotDocument $document */
        foreach ($documents as $document) {
            $chunksCreated += $this->ingestDocument($document, $embed);
        }

        return [
            'documents_indexed' => $indexed,
            'chunks_created' => $chunksCreated,
        ];
    }

    private function ingestDocument(AIChatbotDocument $document, bool $embed): int
    {
        $content = (string) $document->content;
        if ($content === '') {
            return 0;
        }

        AIChatbotChunk::query()->where('document_id', $document->id)->delete();

        $chunkSize = (int) config('ai_chatbot.chunk_size', 1200);
        $overlap = (int) config('ai_chatbot.chunk_overlap', 200);
        $visibility = ['admin', 'hr', 'employee'];

        $chunks = $this->chunkText($content, $chunkSize, $overlap);
        $rows = [];
        foreach ($chunks as $index => $chunk) {
            $embedding = $embed ? $this->embeddingService->embed($chunk) : [];
            $rows[] = [
                'document_id' => $document->id,
                'source' => $document->source,
                'chunk_index' => $index,
                'content' => $chunk,
                'embedding' => $embedding !== [] ? json_encode($embedding) : null,
                'token_count' => $this->countTokens($chunk),
                'visibility' => json_encode($visibility),
                'checksum' => hash('sha256', $chunk),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if ($rows !== []) {
            AIChatbotChunk::query()->insert($rows);
        }

        return count($rows);
    }

    private function chunkText(string $content, int $chunkSize, int $overlap): array
    {
        // Split on markdown headings (##, ###, etc.) or double newlines for semantic chunking.
        // Headings are kept with their following content so the chunk has context.
        $sections = preg_split('/(?=^#{1,4}\s)/m', $content, -1, PREG_SPLIT_NO_EMPTY);

        $chunks = [];
        $currentChunk = '';
        $currentHeading = '';

        foreach ($sections as $section) {
            // Extract heading if this section starts with one
            if (preg_match('/^(#{1,4}\s.+)\n/m', $section, $headingMatch)) {
                $currentHeading = trim($headingMatch[1]);
            }

            // Further split large sections on paragraph boundaries (double newlines)
            $paragraphs = preg_split('/(\n\s*\n)/', $section, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($paragraphs as $paragraph) {
                $paragraph = trim($paragraph);
                if ($paragraph === '') {
                    continue;
                }

                if (mb_strlen($currentChunk) + mb_strlen($paragraph) + 2 > $chunkSize && trim($currentChunk) !== '') {
                    $chunks[] = trim($currentChunk);
                    // Start next chunk with heading context + overlap
                    $overlapText = mb_strlen($currentChunk) > $overlap
                        ? mb_substr($currentChunk, -$overlap)
                        : '';
                    $currentChunk = ($currentHeading !== '' ? $currentHeading."\n" : '').$overlapText."\n".$paragraph;
                } else {
                    $currentChunk .= ($currentChunk !== '' ? "\n\n" : '').$paragraph;
                }
            }
        }

        if (trim($currentChunk) !== '') {
            $chunks[] = trim($currentChunk);
        }

        // Safety: break any remaining oversized chunks
        $finalChunks = [];
        foreach ($chunks as $c) {
            if (mb_strlen($c) > $chunkSize + 500) {
                $start = 0;
                $step = max(1, $chunkSize - $overlap);
                while ($start < mb_strlen($c)) {
                    $finalChunks[] = trim(mb_substr($c, $start, $chunkSize));
                    $start += $step;
                }
            } else {
                $finalChunks[] = $c;
            }
        }

        return array_values(array_filter($finalChunks, fn ($c) => $c !== ''));
    }

    private function countTokens(string $content): int
    {
        $normalized = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $content) ?? $content;
        $parts = preg_split('/\s+/', trim($normalized)) ?: [];

        return count(array_filter($parts, fn ($p) => $p !== ''));
    }
}
