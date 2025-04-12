<?php

namespace App\Jobs;

use App\Mail\ContactTheFarmerMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class ContactTheFarmerJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $subjectFor,
        public string $message,
        public string $emilTo,
        public ?string $from = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->emilTo)
            ->send(new ContactTheFarmerMail(
                toEmail: $this->emilTo,
                subjectFor: $this->subjectFor,
                message: $this->message,
                fromTo: $this->from,
            ));
    }
}
