<?php

namespace App\Console\Commands;

use App\Features\AIChatbot\Models\AIChatbotConversation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PurgeOldAiChatConversations extends Command
{
    protected $signature = 'ai-chat:purge-old {--days=7 : Delete conversations with last activity older than N days} {--dry-run : Show how many would be deleted without deleting}';

    protected $description = 'Hard-delete AI chatbot conversations older than N days (including their messages).';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        if ($days < 1) {
            $this->error('Days must be >= 1');

            return self::FAILURE;
        }

        $cutoff = now()->subDays($days);

        $query = AIChatbotConversation::query()
            ->whereIn('status', ['active', 'archived', 'deleted'])
            ->where(function ($q) use ($cutoff) {
                $q->whereNotNull('last_message_at')->where('last_message_at', '<', $cutoff)
                    ->orWhere(function ($q2) use ($cutoff) {
                        $q2->whereNull('last_message_at')->where('created_at', '<', $cutoff);
                    });
            });

        $count = (int) $query->count();

        if ($this->option('dry-run')) {
            $this->info("[Dry run] Would delete {$count} conversation(s) older than {$days} day(s). Cutoff: {$cutoff}");

            return self::SUCCESS;
        }

        if ($count === 0) {
            $this->info('No conversations to purge.');

            return self::SUCCESS;
        }

        DB::transaction(function () use ($query) {
            // messages are ON DELETE CASCADE, so deleting conversations removes messages too
            $query->delete();
        });

        $this->info("Deleted {$count} conversation(s) older than {$days} day(s). Cutoff: {$cutoff}");

        return self::SUCCESS;
    }
}
