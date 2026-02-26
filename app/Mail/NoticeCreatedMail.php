<?php

namespace App\Mail;

use App\Features\Notices\Models\Notice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Notice $notice,
        public string $publishedBy,
    ) {}

    public function build(): self
    {
        return $this->subject('New Notice Published')
            ->view('emails.notice-created')
            ->with([
                'noticeTitle' => $this->notice->title,
                'noticeCategory' => $this->notice->type,
                'publishDate' => optional($this->notice->created_at)->toDayDateTimeString(),
                'publishedBy' => $this->publishedBy,
                'priority' => $this->notice->type,
                'noticeContent' => $this->notice->message,
            ]);
    }
}
