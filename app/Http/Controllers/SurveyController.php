<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class SurveyController extends Controller
{
    public function index()
    {
        return view('survey', [
            'student' => Auth::guard('student')->user(),
            'survey' => Survey::first(),
            'primary_option' => Survey::first()->questions()->first()
        ]);
    }

    public function save(Request $request)
    {
        $rules = [];
        foreach (Survey::first()->questions()->whereDoesntHave('section')->get() as $question) {
            $rules["q$question->id"] = ['required'];
        };

        foreach (Survey::first()->questions()->whereHas('section')->get()->groupBy('section.name') as $group => $questions) {
            $questions->each(function($question) use($group, $request, &$rules) {
                $rules["q$question->id"] = [Rule::requiredIf(in_array($group, $request->q1))];

            });
        };

        $request->validate([
            'selfie' => 'required|image|max:10000',
        ]);

        $result = $request->validate($rules);

        try {
            DB::beginTransaction();
            (new Entry())->for(Survey::first())->by(Auth::guard('student')->user())->fromArray($result)->push();
            $student =  Auth::guard('student')->user();

            $student->update([
                'avatar' => $request->file('selfie')->store('selfie', ['disk' => 'public']),
            ]);
            DB::commit();
        } catch (\Throwable $th) {
            throw ValidationException::withMessages([
                'error' => $th->getMessage()
            ]);
        }


        Auth::guard('student')->logout();
        return redirect('/')->with('success', "Terima kasih $student->name telah bersedia mengisi TracerStudy SMKN 1 Pogalan");
    }
}
