<?php

namespace App\Filament\Imports;

use App\Models\Rombel;
use App\Models\SchoolYear;
use App\Models\Student;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class StudentImporter extends Importer
{
    protected static ?string $model = Student::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nama')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),

            ImportColumn::make('nisn')
                ->requiredMapping()
                ->rules(['required', 'string']),

            ImportColumn::make('gender')
                ->rules(['nullable', 'in:L,P']),

            ImportColumn::make('tempat_lahir')
                ->rules(['nullable', 'string', 'max:255']),

            ImportColumn::make('tanggal_lahir')
                ->rules(['nullable', 'date']),

            ImportColumn::make('rombel')
                ->requiredMapping()
                ->rules(['required', 'string']),

            ImportColumn::make('tahun_lulus')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): ?Student
    {
        $tahunAngkatan = SchoolYear::firstOrCreate([
            'year' => $this->data['tahun_lulus']
        ], [
            'display_name' => $this->data['tahun_lulus']
        ]);

        $rombel = Rombel::firstOrCreate([
            'name' => $this->data['rombel'],
            'school_year_id' => $tahunAngkatan->id,
        ], [
            'display_name' => $this->data['rombel'],
        ]);

        $murid = Student::firstOrNew([
            'nisn' => $this->data['nisn'],
            'rombel_id' => $rombel->id
        ], [
            'name' => strtoupper($this->data['nama']),
            'gender' => $this->data['gender'],
            'born_place' => strtoupper($this->data['tempat_lahir']),
            'born_date' => $this->data['tanggal_lahir'],
        ]);

        return $murid;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your student import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
