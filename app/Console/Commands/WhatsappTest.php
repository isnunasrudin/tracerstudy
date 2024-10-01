<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http as FacadesHttp;

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
        FacadesHttp::baseUrl(config('app.whatsapp_api'))->post('/client/sendMessage/main', [
            // 'chatId' => substr($this->phoneNumber->formatE164(), 1) . "@c.us",
            'chatId' => "6282228403855@c.us",
            "contentType" => "string",
            "content"=> "Ini percobaan bosque"
        ]);
        // Student::whereHas('entries')->whereNull('notified_at')->get()->each(function($student){
        //     WhatsappSendMessage::dispatch(new PhoneNumber('6282228403855', 'ID'), $student);
        // });
    }
}
