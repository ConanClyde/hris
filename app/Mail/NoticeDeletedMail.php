<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoticeDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $noticeTitle,
        public string $noticeCategory,
        public string $removedDate,
        public string $removedBy,
    ) {}

    public function build(): self
    {
        return $this->subject('Notice Removed')
            ->view('emails.notice-deleted')
            ->with([
                'noticeTitle' => $this->noticeTitle,
                'noticeCategory' => $this->noticeCategory,
                'removedDate' => $this->removedDate,
                'removedBy' => $this->removedBy,
            ]);
    }
}
