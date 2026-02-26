<?php

namespace App\Mail;

use App\Features\Calendar\Models\CustomHoliday;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HolidayUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CustomHoliday $holiday,
        public string $updatedBy,
    ) {}

    public function build(): self
    {
        return $this->subject('Holiday Updated')
            ->view('emails.holiday-updated')
            ->with([
                'holidayName' => $this->holiday->title,
                'holidayDate' => optional($this->holiday->date)->toFormattedDateString(),
                'holidayType' => $this->holiday->category,
                'updatedBy' => $this->updatedBy,
                'description' => $this->holiday->description ?? '',
            ]);
    }
}
