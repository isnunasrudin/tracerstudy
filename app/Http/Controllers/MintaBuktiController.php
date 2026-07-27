<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use MattDaneshvar\Survey\Models\Survey;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Typography\FontFactory;

class MintaBuktiController extends Controller
{
    public function __invoke(Request $request)
    {
        $student = Auth::guard('student')->user();
        $survey = Survey::first();

        $pesan = "Halo, saya {$student->name}.
Tolong dikirimkan bukti tracer study saya.

<{$student->token}/>
*Pesan jangan diubah*";

        // urlencode() otomatis menangani spasi, enter, dan karakter < > /
        // $link = "https://wa.me/6282233441765?text=" . urlencode($pesan);

        $link = route('download-bukti');

        return view('minta-bukti', compact(['student', 'survey', 'link']));
    }

    public function download(Request $request)
    {
        $student = Auth::guard('student')->user();

        if (!Storage::disk('public')->exists($student->avatar)) {

            abort(403);
        }

        $student->update(['notified_at' => now()]);
        $filename = $student->id . ".jpg";

        $img = Image::create(1000, 1000);

        $avatar = Image::read(Storage::disk('public')->path($student->avatar));
        $avatar->cover(355, 355);

        $frame = Image::read(resource_path('jadi.png'));
        $frame->cover(1000, 1000);

        $img->place($avatar, 'center', 0, -230);
        $img->place($frame);

        $img->text($student->name, 350 + 150, 600 - 20, function (FontFactory $font) {
            $font->filename(resource_path('arial.ttf'));
            $font->size(40);
            $font->color('fff');
            $font->align('center');
            $font->lineHeight(1.6);
        });

        $img->text('Alumni ' . $student->rombel->name . ' - Tahun ' . $student->rombel->school_year->display_name, 350 + 150, 650 - 10, function (FontFactory $font) {
            $font->filename(resource_path('arial.ttf'));
            $font->size(30);
            $font->color('fff');
            $font->align('center');
            $font->lineHeight(1.6);
        });

        $img->text('Mengisi: ' . $student->entries()->first()->created_at, 350 + 150, 700 - 10, function (FontFactory $font) {
            $font->filename(resource_path('arial.ttf'));
            $font->size(25);
            $font->color('fff');
            $font->align('center');
            $font->lineHeight(1.6);
        });

        $img->save(Storage::disk('public')->path('ahai/' . $filename));

        return redirect()->to(Storage::disk('public')->url('ahai/' . $filename));
    }
}
