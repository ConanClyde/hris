<?php

namespace App\Features\AIChatbot\Services;

use App\Features\AIChatbot\Models\AIChatbotDocument;

class AIChatbotDocumentIndexer
{
    public function indexPrompts(): int
    {
        $txtFiles = glob(storage_path('app/prompts/*.txt')) ?: [];
        $mdFiles = glob(storage_path('app/prompts/*.md')) ?: [];
        $files = array_values(array_unique(array_merge($txtFiles, $mdFiles)));
        $count = 0;
        $sources = [];

        foreach ($files as $filePath) {
            if (str_ends_with($filePath, '_prompt.txt') || str_ends_with($filePath, '_prompt.md')) {
                continue;
            }

            $source = pathinfo($filePath, PATHINFO_BASENAME);
            $sources[] = $source;
            $content = @file_get_contents($filePath);
            if ($content === false) {
                continue;
            }

            $checksum = hash('sha256', $content);
            $document = AIChatbotDocument::query()->where('source', $source)->first();
            if ($document && $document->checksum === $checksum) {
                continue;
            }

            $tokens = $this->tokenize($content);
            $termCount = array_sum($tokens);

            AIChatbotDocument::updateOrCreate(
                ['source' => $source],
                [
                    'content' => $content,
                    'tokens' => $tokens,
                    'term_count' => $termCount,
                    'checksum' => $checksum,
                ]
            );
            $count++;
        }

        if ($sources !== []) {
            AIChatbotDocument::query()->whereNotIn('source', $sources)->delete();
        }

        return $count;
    }

    private function tokenize(string $content): array
    {
        $normalized = mb_strtolower($content);
        $normalized = preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $normalized) ?? $normalized;
        $terms = preg_split('/\s+/', $normalized) ?: [];

        $counts = [];
        foreach ($terms as $term) {
            $clean = trim($term);
            if ($clean === '' || mb_strlen($clean) < 3) {
                continue;
            }
            $counts[$clean] = ($counts[$clean] ?? 0) + 1;
        }

        return $counts;
    }
}
