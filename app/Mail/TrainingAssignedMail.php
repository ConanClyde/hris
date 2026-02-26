<?php

namespace App\Mail;

use App\Features\Training\Models\Training;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingAssignedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Training $training,
        public string $employeeName,
        public string $assignedBy,
        public ?string $dueDate = null,
        public bool $isRequired = true,
    ) {}

    public function build(): self
    {
        $dueDate = $this->dueDate ?? optional($this->training->date_to ?? $this->training->date_from)->toFormattedDateString();

        return $this->subject('Training Assigned')
            ->view('emails.training-assigned')
            ->with([
                'employeeName' => $this->employeeName,
                'trainingTitle' => $this->training->title,
                'trainingDescription' => $this->training->description ?? '',
                'assignedBy' => $this->assignedBy,
                'dueDate' => $dueDate,
                'isRequired' => $this->isRequired,
            ]);
    }
}
