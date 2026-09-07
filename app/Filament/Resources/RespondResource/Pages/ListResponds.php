<?php

namespace App\Filament\Resources\RespondResource\Pages;

use App\Exports\ResponsesExport;
use App\Filament\Resources\RespondResource;
use App\Models\SchoolYear;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListResponds extends ListRecords
{
    protected static string $resource = RespondResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->form([
                    Select::make('school_year_id')
                        ->label('Tahun Angkatan')
                        ->options(fn () => SchoolYear::query()
                            ->orderByDesc('year')
                            ->pluck('display_name', 'id'))
                        ->searchable()
                        ->required(),
                ])
                ->modalHeading('Export Hasil per Angkatan')
                ->modalSubmitActionLabel('Download')
                ->action(function (array $data) {
                    $schoolYear = SchoolYear::query()->findOrFail($data['school_year_id']);
                    $filename = 'hasil-tracer-'.$schoolYear->display_name.'-'.now()->format('YmdHis').'.xlsx';

                    return Excel::download(new ResponsesExport($schoolYear->id), $filename);
                }),
        ];
    }
}
