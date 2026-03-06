<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $pendingLeaves,
        public array $pendingTrainings,
        public int $pendingPdsCount,
        public array $outToday,
        public array $upcomingExpiry,
        public string $generatedAt,
    ) {}

    public function build(): self
    {
        return $this->subject('HRIS Daily Summary Digest — '.now()->toFormattedDateString())
            ->view('emails.daily-digest')
            ->with([
                'pendingLeaves' => $this->pendingLeaves,
                'pendingTrainings' => $this->pendingTrainings,
                'pendingPdsCount' => $this->pendingPdsCount,
                'outToday' => $this->outToday,
                'upcomingExpiry' => $this->upcomingExpiry,
                'generatedAt' => $this->generatedAt,
            ]);
    }
}
