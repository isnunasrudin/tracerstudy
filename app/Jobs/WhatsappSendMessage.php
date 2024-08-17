<?php

namespace App\Jobs;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;


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
        $filename = $this->student->id . ".jpg";

        $img = Image::create(1000, 1000);

        $avatar = Image::read(Storage::disk('public')->path($this->student->avatar));
        $avatar->cover(355, 355);

        $frame = Image::read(resource_path('jadi.png'));
        $frame->cover(1000, 1000);

        $img->place($avatar, 'center', 0, -230);
        $img->place($frame);

        $img->text($this->student->name, 350 + 150, 600 - 20, function (FontFactory $font) {
            $font->filename(resource_path('arial.ttf'));
            $font->size(40);
            $font->color('fff');
            $font->align('center');
            $font->lineHeight(1.6);
        });
        
        $img->text('Alumni '.$this->student->rombel->name.' - Tahun ' . $this->student->rombel->school_year->display_name, 350 + 150, 650 - 10, function (FontFactory $font) {
            $font->filename(resource_path('arial.ttf'));
            $font->size(30);
            $font->color('fff');
            $font->align('center');
            $font->lineHeight(1.6);
        });
        
        $img->text('Mengisi: ' . $this->student->entries()->first()->created_at, 350 + 150, 700 - 10, function (FontFactory $font) {
            $font->filename(resource_path('arial.ttf'));
            $font->size(25);
            $font->color('fff');
            $font->align('center');
            $font->lineHeight(1.6);
        });
        
        $img->save(Storage::disk('public')->path('ahai/' . $filename));

        $url = config('app.url');
        $url .= Storage::url("ahai/$filename");

        Log::info($url);

        // Http::baseUrl(config('app.whatsapp_api'))->post('/client/sendMessage/main', [
        //     // 'chatId' => substr($this->phoneNumber->formatE164(), 1) . "@c.us",
        //     'chatId' => "6282228403855@c.us",
        //     "contentType" => "MessageMediaFromURL",
        //     "content"=> $url
        // ]);
    }
}
