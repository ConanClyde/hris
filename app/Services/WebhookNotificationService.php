<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WebhookNotificationService
{
    /**
     * Send a notification message to all enabled webhook channels.
     */
    public function send(string $title, string $message, string $color = '#4f46e5'): void
    {
        if (config('webhooks.slack.enabled') && config('webhooks.slack.webhook_url')) {
            $this->sendToSlack($title, $message, $color);
        }

        if (config('webhooks.teams.enabled') && config('webhooks.teams.webhook_url')) {
            $this->sendToTeams($title, $message, $color);
        }
    }

    /**
     * Send a Slack incoming webhook message.
     */
    protected function sendToSlack(string $title, string $message, string $color): void
    {
        try {
            Http::post(config('webhooks.slack.webhook_url'), [
                'attachments' => [
                    [
                        'color' => $color,
                        'title' => $title,
                        'text' => $message,
                        'footer' => 'HRIS System',
                        'ts' => now()->timestamp,
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Slack webhook failed: '.$e->getMessage());
        }
    }

    /**
     * Send a Microsoft Teams incoming webhook message (Adaptive Card).
     */
    protected function sendToTeams(string $title, string $message, string $color): void
    {
        try {
            Http::post(config('webhooks.teams.webhook_url'), [
                '@type' => 'MessageCard',
                '@context' => 'http://schema.org/extensions',
                'themeColor' => str_replace('#', '', $color),
                'summary' => $title,
                'sections' => [
                    [
                        'activityTitle' => $title,
                        'text' => $message,
                    ],
                ],
            ]);
        } catch (\Throwable $e) {
            Log::warning('Teams webhook failed: '.$e->getMessage());
        }
    }

    /**
     * Build and send a leave-submitted notification.
     */
    public function notifyLeaveSubmitted(string $employeeName, string $leaveType, string $dates): void
    {
        $this->send(
            '🗓️ New Leave Application',
            "*{$employeeName}* submitted a *{$leaveType}* leave for {$dates}.",
            '#f59e0b',
        );
    }

    /**
     * Build and send a training-assigned notification.
     */
    public function notifyTrainingAssigned(string $employeeName, string $trainingTitle): void
    {
        $this->send(
            '📚 Training Assigned',
            "*{$employeeName}* has been assigned training: *{$trainingTitle}*.",
            '#3b82f6',
        );
    }

    /**
     * Build and send a daily digest summary notification.
     */
    public function notifyDailyDigest(int $pendingLeaves, int $pendingTrainings, int $pendingPds, int $outToday): void
    {
        $lines = [
            '📊 *Daily HR Summary* — '.now()->toFormattedDateString(),
            "• Pending Leaves: {$pendingLeaves}",
            "• Pending Trainings: {$pendingTrainings}",
            "• PDS Reviews: {$pendingPds}",
            "• Out Today: {$outToday}",
        ];

        $this->send('📊 HRIS Daily Digest', implode("\n", $lines), '#6366f1');
    }
}
