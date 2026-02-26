<?php

namespace App\Mail;

use App\Features\Training\Models\Training;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainingCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Training $training,
        public string $employeeName,
        public ?string $score = null,
    ) {}

    public function build(): self
    {
        return $this->subject('Training Completed')
            ->view('emails.training-completed')
            ->with([
                'employeeName' => $this->employeeName,
                'trainingTitle' => $this->training->title,
                'completionDate' => optional($this->training->updated_at ?? $this->training->date_to ?? $this->training->date_from)
                    ->toFormattedDateString(),
                'score' => $this->score,
            ]);
    }
}
