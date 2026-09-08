<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ResponsesExport implements FromCollection, WithHeadings
{
    private ?Collection $rows = null;

    private array $questionHeadings = [];

    public function __construct(private readonly int $schoolYearId)
    {
    }

    public function collection(): Collection
    {
        return $this->rows ??= $this->buildRows();
    }

    public function headings(): array
    {
        $this->buildRows();

        return [
            'Nama',
            'NISN',
            'Angkatan',
            'Survei',
            ...array_values($this->questionHeadings),
        ];
    }

    private function buildRows(): Collection
    {
        if ($this->rows !== null) {
            return $this->rows;
        }

        $answers = DB::table('answers')
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
            ->orderBy('questions.id')
            ->get([
                'students.id as student_id',
                'students.name as nama',
                'students.nisn',
                'school_years.display_name as angkatan',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(surveys.name, '$.en')) as survei"),
                'questions.id as question_id',
                DB::raw("JSON_UNQUOTE(JSON_EXTRACT(questions.content, '$.en')) as question"),
                'answers.value as jawaban',
            ]);

        $questionCounts = [];
        $rows = [];

        foreach ($answers as $answer) {
            if (! array_key_exists($answer->question_id, $this->questionHeadings)) {
                $question = trim((string) $answer->question) ?: 'Pertanyaan '.$answer->question_id;
                $questionCounts[$question] = ($questionCounts[$question] ?? 0) + 1;

                $this->questionHeadings[$answer->question_id] = $questionCounts[$question] > 1
                    ? $question.' ('.$questionCounts[$question].')'
                    : $question;
            }

            if (! isset($rows[$answer->student_id])) {
                $rows[$answer->student_id] = [
                    'Nama' => $answer->nama,
                    'NISN' => $answer->nisn,
                    'Angkatan' => $answer->angkatan,
                    'Survei' => $answer->survei,
                    'answers' => [],
                ];
            }

            $rows[$answer->student_id]['answers'][$answer->question_id] = $answer->jawaban;
        }

        $this->rows = collect($rows)->map(function (array $row): array {
            $values = [
                $row['Nama'],
                $row['NISN'],
                $row['Angkatan'],
                $row['Survei'],
            ];

            foreach (array_keys($this->questionHeadings) as $questionId) {
                $values[] = $row['answers'][$questionId] ?? null;
            }

            return $values;
        })->values();

        return $this->rows;
    }
}
