<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Propaganistas\LaravelPhone\PhoneNumber;

class WhatsappSendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public PhoneNumber $phoneNumber,
        public Student $student,
    )
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Http::baseUrl(config('app.whatsapp_api'))->post('/client/sendMessage/main', [
            'chatId' => substr($this->phoneNumber->formatE164(), 1) . "@c.us",
            "contentType" => "string",
            // "content" => $this->message
        ]);
    }
}
