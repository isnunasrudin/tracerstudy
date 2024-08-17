<?php

namespace App\Console\Commands;

use App\Jobs\WhatsappSendMessage;
use App\Models\Student;
use Illuminate\Console\Command;
use Propaganistas\LaravelPhone\PhoneNumber;

class WhatsappTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mencoba bot wa';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Student::whereHas('entries')->get()->each(function($student){
            WhatsappSendMessage::dispatchSync(new PhoneNumber('6282228403855', 'ID'), $student);
        });
    }
}
