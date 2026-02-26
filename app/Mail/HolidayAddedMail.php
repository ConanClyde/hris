<?php

namespace App\Mail;

use App\Features\Calendar\Models\CustomHoliday;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HolidayAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CustomHoliday $holiday,
        public string $addedBy,
    ) {}

    public function build(): self
    {
        return $this->subject('New Holiday Added')
            ->view('emails.holiday-added')
            ->with([
                'holidayName' => $this->holiday->title,
                'holidayDate' => optional($this->holiday->date)->toFormattedDateString(),
                'holidayType' => $this->holiday->category,
                'addedBy' => $this->addedBy,
                'description' => $this->holiday->description ?? '',
            ]);
    }
}
