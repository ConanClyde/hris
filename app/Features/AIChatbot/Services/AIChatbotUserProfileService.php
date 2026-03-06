<?php

namespace App\Features\AIChatbot\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class AIChatbotUserProfileService
{
    private function ensureProfileRow(User $user): void
    {
        if (! Schema::hasTable('ai_chatbot_user_profiles')) {
            throw new \RuntimeException('ai_chatbot_user_profiles table is missing');
        }

        $exists = DB::table('ai_chatbot_user_profiles')->where('user_id', $user->id)->exists();
        if ($exists) {
            return;
        }

        DB::table('ai_chatbot_user_profiles')->insert([
            'user_id' => $user->id,
            'preferred_language' => null,
            'detail_level' => 'normal',
            'favorite_topics' => json_encode([]),
            'metadata' => json_encode([]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function updateLanguage(User $user, string $instruction): void
    {
        $language = str_contains($instruction, 'Tagalog') ? 'tagalog' : 'english';

        try {
            $this->ensureProfileRow($user);

            $current = DB::table('ai_chatbot_user_profiles')
                ->where('user_id', $user->id)
                ->value('preferred_language');

            if ($current === $language) {
                return;
            }

            DB::table('ai_chatbot_user_profiles')
                ->where('user_id', $user->id)
                ->update([
                    'preferred_language' => $language,
                    'updated_at' => now(),
                ]);
        } catch (\Throwable $e) {
            Log::warning('AIChatbotUserProfile updateLanguage skipped', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function recordTopic(User $user, string $topic): void
    {
        $topic = trim($topic);
        if ($topic === '') {
            return;
        }

        try {
            $this->ensureProfileRow($user);
            $raw = DB::table('ai_chatbot_user_profiles')
                ->where('user_id', $user->id)
                ->value('favorite_topics');

            $topics = [];
            if (is_string($raw) && $raw !== '') {
                $decoded = json_decode($raw, true);
                if (is_array($decoded)) {
                    $topics = $decoded;
                }
            }

            if (! in_array($topic, $topics, true)) {
                $topics[] = $topic;
                DB::table('ai_chatbot_user_profiles')
                    ->where('user_id', $user->id)
                    ->update([
                        'favorite_topics' => json_encode(array_values($topics)),
                        'updated_at' => now(),
                    ]);
            }
        } catch (\Throwable $e) {
            Log::warning('AIChatbotUserProfile recordTopic skipped', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Record topics from retrieval/suggestion sources for cross-conversation recall.
     */
    public function recordTopicsFromSources(User $user, array $sources): void
    {
        $sourceToTopic = [
            'csc' => 'leave_policies',
            'labor' => 'leave_policies',
            'pds' => 'PDS',
            'code_of_conduct' => 'code_of_conduct',
            'ssl' => 'SSL',
            'gsis' => 'GSIS',
            'spms' => 'SPMS',
            'paternity' => 'paternity_leave',
            'solo_parent' => 'solo_parent_leave',
            'special_leave' => 'special_leave',
            'year_end' => 'year_end_bonus',
            'mid_year' => 'mid_year_bonus',
            'pbb' => 'PBB',
        ];

        foreach ($sources as $source) {
            $base = pathinfo((string) $source, PATHINFO_FILENAME);
            foreach ($sourceToTopic as $prefix => $topic) {
                if (str_starts_with($base, $prefix)) {
                    $this->recordTopic($user, $topic);

                    break;
                }
            }
        }
    }

    public function getFavoriteTopics(User $user): array
    {
        try {
            if (! Schema::hasTable('ai_chatbot_user_profiles')) {
                return [];
            }

            $raw = DB::table('ai_chatbot_user_profiles')
                ->where('user_id', $user->id)
                ->value('favorite_topics');

            if (! is_string($raw) || $raw === '') {
                return [];
            }

            $topics = json_decode($raw, true);

            return is_array($topics) ? $topics : [];
        } catch (\Throwable $e) {
            Log::warning('AIChatbotUserProfile getFavoriteTopics failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function buildPreferencesSummary(User $user): ?string
    {
        try {
            if (! Schema::hasTable('ai_chatbot_user_profiles')) {
                return null;
            }

            $row = DB::table('ai_chatbot_user_profiles')->where('user_id', $user->id)->first();
            if (! $row) {
                return null;
            }

            $parts = [];
            if (is_string($row->preferred_language) && $row->preferred_language !== '') {
                $parts[] = 'preferred language: '.$row->preferred_language;
            }

            $topics = [];
            if (is_string($row->favorite_topics) && $row->favorite_topics !== '') {
                $decoded = json_decode($row->favorite_topics, true);
                if (is_array($decoded)) {
                    $topics = $decoded;
                }
            }
            if ($topics !== []) {
                $parts[] = 'typical topics: '.implode(', ', $topics);
            }

            if ($parts === []) {
                return null;
            }

            return 'User preferences: '.implode('; ', $parts).'.';
        } catch (\Throwable $e) {
            Log::warning('AIChatbotUserProfile buildPreferencesSummary failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
