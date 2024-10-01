<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\ChartWidget;

class ZAlumniChart extends ChartWidget
{
    protected static ?string $heading = 'Pengisian Alumni';

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    // 'label' => 'Blog posts created',
                    'data' => [
                        Student::whereHas('entries')->count(),
                        Student::whereDoesntHave('entries')->count(),
                    ],
                    'backgroundColor' => [
                        'green',
                        'red',
                    ]
                ],
            ],

            'labels' => ['Sudah Mengisi', 'Belum Mengisi'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
