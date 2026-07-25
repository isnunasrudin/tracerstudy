<?php

namespace App\Filament\Widgets;

use App\Models\SchoolYear;
use App\Models\Student;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ZAlumniChart extends ChartWidget
{
    protected static ?string $heading = 'Pengisian Alumni per Angkatan / Tahun Ajaran';

    protected function getData(): array
    {
        // 1. Ambil daftar Tahun Ajaran (misal berdasarkan nama/tahun)
        $schoolYears = SchoolYear::orderBy('year', 'asc')->get();

        $labels = [];
        $dataSudah = [];
        $dataBelum = [];

        foreach ($schoolYears as $year) {
            $labels[] = $year->year; // Misal: '2021/2022', '2022/2023'

            // Query siswa yang terhubung ke SchoolYear ini lewat Rombel
            $studentQuery = Student::whereHas('rombel', function ($query) use ($year) {
                $query->where('school_year_id', $year->id);
            });

            // Hitung Sudah Mengisi untuk Tahun Ajaran ini
            $dataSudah[] = (clone $studentQuery)
                ->whereHas('entries')
                ->count();

            // Hitung Belum Mengisi untuk Tahun Ajaran ini
            $dataBelum[] = (clone $studentQuery)
                ->whereDoesntHave('entries')
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sudah Mengisi',
                    'data' => $dataSudah,
                    'backgroundColor' => '#22c55e', // Hijau
                ],
                [
                    'label' => 'Belum Mengisi',
                    'data' => $dataBelum,
                    'backgroundColor' => '#ef4444', // Merah
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
