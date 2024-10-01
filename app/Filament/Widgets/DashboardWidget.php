<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Stat::make(label: 'Total Alumni', value: Student::count()),
            Stat::make(label: 'Telah Mengisi', value:  Student::whereHas('entries')->count())->description('Dari Total ' . Student::count())->descriptionIcon('heroicon-s-user-group')->color('success'),
        ];
    }
}
