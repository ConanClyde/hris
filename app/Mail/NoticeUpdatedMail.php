<?php

namespace App\Mail;

use App\Features\Notices\Models\Notice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Notice $notice,
        public string $updatedBy,
    ) {}

    public function build(): self
    {
        return $this->subject('Notice Updated')
            ->view('emails.notice-updated')
            ->with([
                'noticeTitle' => $this->notice->title,
                'noticeCategory' => $this->notice->type,
                'updatedAt' => optional($this->notice->updated_at)->toDayDateTimeString(),
                'updatedBy' => $this->updatedBy,
                'noticeContent' => $this->notice->message,
            ]);
    }
}
