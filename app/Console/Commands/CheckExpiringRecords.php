<?php

namespace App\Console\Commands;

use App\Features\Training\Models\Training;
use App\Mail\DailyDigestMail;
use App\Models\User;
use App\Services\WebhookNotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckExpiringRecords extends Command
{
    protected $signature = 'alerts:check-expiring {--days=30 : Number of days to look ahead}';

    protected $description = 'Check for records expiring within the specified window and notify HR';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $now = Carbon::today();
        $cutoff = $now->copy()->addDays($days);

        $expiringItems = collect();

        // 1. Notices expiring soon
        $expiringNotices = DB::table('notices')
            ->whereNotNull('expires_at')
            ->where('is_active', true)
            ->whereBetween('expires_at', [$now->toDateString(), $cutoff->toDateString()])
            ->select('title', 'expires_at')
            ->get();

        foreach ($expiringNotices as $notice) {
            $daysLeft = $now->diffInDays(Carbon::parse($notice->expires_at));
            $expiringItems->push([
                'type' => 'Notice',
                'name' => $notice->title,
                'expires_at' => Carbon::parse($notice->expires_at)->format('M d, Y'),
                'days_left' => $daysLeft,
            ]);
        }

        // 2. Training records with date_to approaching (incomplete trainings)
        $expiringTrainings = Training::where('status', 'approved')
            ->whereNotNull('date_to')
            ->whereBetween('date_to', [$now->toDateString(), $cutoff->toDateString()])
            ->with('employee.user')
            ->get();

        foreach ($expiringTrainings as $training) {
            $daysLeft = $now->diffInDays(Carbon::parse($training->date_to));
            $expiringItems->push([
                'type' => 'Training',
                'name' => ($training->employee->full_name ?? 'Unknown') . ' — ' . $training->title,
                'expires_at' => $training->date_to->format('M d, Y'),
                'days_left' => $daysLeft,
            ]);
        }

        $count = $expiringItems->count();
        $this->info("Found {$count} expiring record(s) within {$days} days.");

        if ($count === 0) {
            return self::SUCCESS;
        }

        // Send webhook notification
        try {
            $lines = ["⏰ *Expiring Records Alert* — {$count} record(s) expiring within {$days} days:"];
            foreach ($expiringItems->take(10) as $item) {
                $lines[] = "• [{$item['type']}] {$item['name']} — expires {$item['expires_at']} ({$item['days_left']} days left)";
            }
            if ($count > 10) {
                $lines[] = "...and " . ($count - 10) . " more.";
            }
            app(WebhookNotificationService::class)->send(
                '⏰ Expiring Records Alert',
                implode("\n", $lines),
                '#ef4444',
            );
        } catch (\Throwable $e) {
            $this->warn("Webhook notification skipped: {$e->getMessage()}");
        }

        // Log the expiring items
        foreach ($expiringItems as $item) {
            $this->line("[{$item['type']}] {$item['name']} — {$item['expires_at']} ({$item['days_left']} days left)");
        }

        return self::SUCCESS;
    }
}
