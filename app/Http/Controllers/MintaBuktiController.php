<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MattDaneshvar\Survey\Models\Survey;

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
        $link = "https://wa.me/6282233441765?text=" . urlencode($pesan);

        return view('minta-bukti', compact(['student', 'survey', 'link']));
    }
}
