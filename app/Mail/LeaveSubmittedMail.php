<?php

namespace App\Mail;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LeaveApplication $leave) {}

    public function build(): self
    {
        $startDate = optional($this->leave->date_from)->toFormattedDateString();
        $endDate = optional($this->leave->date_to ?? $this->leave->date_from)->toFormattedDateString();

        return $this->subject('Leave Application Submitted')
            ->view('emails.leave-submitted')
            ->with([
                'employeeName' => $this->leave->employee_name,
                'leaveType' => $this->leave->type,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'duration' => $this->leave->total_days ?? 0,
                'reason' => $this->leave->reason ?? '',
                'submittedDate' => optional($this->leave->created_at)->toDayDateTimeString(),
            ]);
    }
}
