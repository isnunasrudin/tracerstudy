<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Propaganistas\LaravelPhone\PhoneNumber;

class WelcomeController extends Controller
{
    public function index()
    {
        return view('welcome', [
            'title' => 'Tracer Study Berdaya',
            'survey' => \MattDaneshvar\Survey\Models\Survey::first()
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'nisn' => 'required',
            'born_date' => 'required|date|before:now',
            'phone' => 'required|min:5|phone:ID'
        ], [
            'nisn.required' => 'Nomor Induk Siswa Nasional tidak boleh kosong!',
            'born_date.required' => 'Tanggal Lahir tidak boleh kosong!',
            'born_date.date' => 'Tanggal Lahir tidak valid!',
            'born_date.before' => 'Tanggal Lahir tidak valid!',
            'phone.required' => 'No. WhatsApp tidak boleh kosong!',
            'phone.min' => 'No. WhatsApp tidak valid!',
            'phone.phone' => 'No. WhatsApp tidak valid!'
        ]);

        $nisn = $request->nisn;
        $born_date = $request->born_date;

        if( $student = Student::whereNisn($nisn)->whereBornDate($born_date)->first() ) {
            $student->whatsapp = $request->phone;
            $student->save();

            Auth::guard('student')->loginUsingId($student->id);
            return redirect()->route('survey.show');
        }

        throw ValidationException::withMessages([
            'nisn' => 'Data tidak ditemukan!'
        ]);
    }
}
