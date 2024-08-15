<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class SurveyController extends Controller
{
    public function index()
    {
        return view('survey', [
            'survey' => Survey::first(),
            'primary_option' => Survey::first()->questions()->first()
        ]);
    }

    public function save(Request $request)
    {
        $hasil = $request->validate([
            'q*' => 'required',
        ]);

        try {
            DB::beginTransaction();
            (new Entry())->for(Survey::first())->by(Auth::guard('student')->user())->fromArray($hasil)->push();
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
        }

        Auth::guard('student')->logout();
        return redirect('/');
    }
}
