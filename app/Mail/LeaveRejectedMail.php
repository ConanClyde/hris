<?php

namespace App\Mail;

use App\Features\Leave\Models\LeaveApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeaveRejectedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public LeaveApplication $leave,
        public string $approverName,
        public ?string $reason = null,
    ) {}

    public function build(): self
    {
        $startDate = optional($this->leave->date_from)->toFormattedDateString();
        $endDate = optional($this->leave->date_to ?? $this->leave->date_from)->toFormattedDateString();

        return $this->subject('Leave Application Rejected')
            ->view('emails.leave-rejected')
            ->with([
                'employeeName' => $this->leave->employee_name,
                'leaveType' => $this->leave->type,
                'startDate' => $startDate,
                'endDate' => $endDate,
                'duration' => $this->leave->total_days ?? 0,
                'approverName' => $this->approverName,
                'reason' => $this->reason,
            ]);
    }
}
