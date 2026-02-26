<?php

namespace App\Mail;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public LeaveApplication $leave, public string $cancelledAt) {}

    public function build(): self
    {
        $startDate = optional($this->leave->date_from)->toFormattedDateString();
        $endDate = optional($this->leave->date_to ?? $this->leave->date_from)->toFormattedDateString();

        return $this->subject('Leave Application Cancelled')
            ->view('emails.leave-cancelled')
            ->with([
                'employeeName' => $this->leave->employee_name,
                'leaveType' => $this->leave->type,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'duration' => $this->leave->total_days ?? 0,
                'cancelledAt' => $this->cancelledAt,
            ]);
    }
}
