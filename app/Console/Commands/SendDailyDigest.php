<?php

namespace App\Console\Commands;

use App\Features\Leave\Models\LeaveApplication;
use App\Features\Pds\Models\Pds;
use App\Features\Training\Models\Training;
use App\Mail\DailyDigestMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendDailyDigest extends Command
{
    protected $signature = 'digest:send-daily';

    protected $description = 'Send a daily summary digest email to all HR users';

    public function handle(): int
    {
        $today = Carbon::today()->toDateString();

        // Pending leave applications
        $pendingLeaves = LeaveApplication::with(['employee.user'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($leave) => [
                'employee_name' => $leave->employee->full_name ?? 'Unknown',
                'leave_type' => $leave->type ?? 'Leave',
                'from_date' => optional($leave->date_from)->format('M d, Y') ?? '',
                'to_date' => optional($leave->date_to)->format('M d, Y') ?? '',
            ])
            ->toArray();

        // Pending training requests
        $pendingTrainings = Training::with(['employee.user'])
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->get()
            ->map(fn ($t) => [
                'employee_name' => $t->employee->full_name ?? 'Unknown',
                'title' => $t->title ?? 'Untitled',
            ])
            ->toArray();

        // Pending PDS count
        $pendingPdsCount = Pds::where('status', 'submitted')
            ->whereHas('employee.user', fn ($q) => $q->where('role', '!=', 'admin'))
            ->count();

        // Who's out today
        $outToday = LeaveApplication::with(['employee.user'])
            ->where('status', 'approved')
            ->where('date_from', '<=', $today)
            ->where('date_to', '>=', $today)
            ->get()
            ->map(fn ($leave) => [
                'employee_name' => $leave->employee->full_name ?? 'Unknown',
                'leave_type' => $leave->type ?? 'Leave',
            ])
            ->toArray();

        // Upcoming expiry placeholder (contracts/documents expiring in 30 days)
        $upcomingExpiry = [];

        $generatedAt = now()->toDayDateTimeString();

        // Send to every HR user
        $hrUsers = User::where('role', 'hr')->get();

        if ($hrUsers->isEmpty()) {
            $this->warn('No HR users found to send digest to.');
            return self::SUCCESS;
        }

        $mail = new DailyDigestMail(
            $pendingLeaves,
            $pendingTrainings,
            $pendingPdsCount,
            $outToday,
            $upcomingExpiry,
            $generatedAt,
        );

        foreach ($hrUsers as $hrUser) {
            try {
                Mail::to($hrUser->email)->send($mail);
            } catch (\Throwable $e) {
                $this->error("Failed to send digest to {$hrUser->email}: {$e->getMessage()}");
            }
        }

        // Also send to Slack/Teams if configured
        try {
            app(\App\Services\WebhookNotificationService::class)->notifyDailyDigest(
                count($pendingLeaves),
                count($pendingTrainings),
                $pendingPdsCount,
                count($outToday),
            );
        } catch (\Throwable $e) {
            $this->warn("Webhook notification skipped: {$e->getMessage()}");
        }

        $this->info("Daily digest sent to {$hrUsers->count()} HR user(s).");

        return self::SUCCESS;
    }
}
