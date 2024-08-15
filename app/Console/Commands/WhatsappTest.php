<?php

namespace App\Console\Commands;

use App\Jobs\WhatsappSendMessage;
use Illuminate\Console\Command;
use Propaganistas\LaravelPhone\PhoneNumber;

class WhatsappTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:test {phone}';

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
        WhatsappSendMessage::dispatchSync(new PhoneNumber($this->argument('phone'), 'ID'), "Ini adalah pesan dari Sistem. _Dikirim pada: . " . now()) . '_';
    }
}
