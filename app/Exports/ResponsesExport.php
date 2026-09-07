<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResponsesExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly int $schoolYearId)
    {
    }

    public function collection(): Collection
    {
        return DB::table('answers')
            ->join('questions', 'questions.id', '=', 'answers.question_id')
            ->join('entries', 'entries.id', '=', 'answers.entry_id')
            ->join('surveys', 'surveys.id', '=', 'entries.survey_id')
            ->join('students', 'students.id', '=', 'entries.participant_id')
            ->join('rombels', 'rombels.id', '=', 'students.rombel_id')
            ->join('school_years', 'school_years.id', '=', 'rombels.school_year_id')
            ->where('school_years.id', $this->schoolYearId)
            ->whereNull('entries.deleted_at')
            ->orderBy('entries.created_at')
            ->orderBy('students.name')
            ->orderBy('answers.id')
            ->get([
                'students.name as nama',
                'students.nisn',
                'school_years.display_name as angkatan',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(surveys.name, '$.en')) as survei"),
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(questions.content, '$.en')) as pertanyaan"),
                'answers.value as jawaban',
                'entries.created_at as waktu_pengisian',
            ]);
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NISN',
            'Angkatan',
            'Survei',
            'Pertanyaan',
            'Jawaban',
            'Waktu Pengisian',
        ];
    }
}
