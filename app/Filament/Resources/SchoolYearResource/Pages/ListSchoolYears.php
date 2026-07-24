<?php

namespace App\Filament\Resources\SchoolYearResource\Pages;

use App\Filament\Imports\StudentImporter;
use App\Filament\Resources\SchoolYearResource;
use App\Models\Rombel;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ValueError;

class ListSchoolYears extends ListRecords
{
    protected static string $resource = SchoolYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('importStudents')
                ->label('Import Siswa')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File CSV / Excel')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel', 'text/comma-separated-values'])
                        ->disk('local')
                        ->directory('imports')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $filePath = Storage::disk('local')->path($data['file']);

                    if (!file_exists($filePath)) {
                        Notification::make()
                            ->title('File tidak ditemukan')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Baca file CSV
                    $file = fopen($filePath, 'r');
                    $header = fgetcsv($file, 1000, ';'); // Ambil baris pertama sebagai header

                    // Normalisasi nama header agar huruf kecil semua dan tanpa spasi aneh
                    $header = array_map(fn($item) => strtolower(trim($item)), $header);

                    $successCount = 0;
                    $failedCount = 0;

                    DB::beginTransaction();

                    try {
                        while (($row = fgetcsv($file, 1000, ';')) !== false) {
                            // Abaikan jika baris kosong
                            if (array_filter($row) == null) {
                                continue;
                            }


                            $row = array_combine($header, $row);

                            $tahunLulus  = trim($row['tahun_lulus'] ?? '');
                            $namaRombel  = trim($row['rombel'] ?? '');
                            $nisn        = trim($row['nisn'] ?? '');
                            $nama        = trim($row['nama'] ?? '');
                            $gender      = trim($row['gender'] ?? null);
                            $tempatLahir = trim($row['tempat_lahir'] ?? null);
                            $tanggalLahir = trim($row['tanggal_lahir'] ?? null);

                            if (!$nisn || !$nama) {
                                $failedCount++;
                                continue;
                            }

                            // 1. Cari / Buat SchoolYear
                            $tahunAngkatan = SchoolYear::firstOrCreate([
                                'year' => $tahunLulus,
                            ], [
                                'display_name' => $tahunLulus,
                            ]);

                            // 2. Cari / Buat Rombel
                            $rombel = Rombel::firstOrCreate([
                                'name'           => $namaRombel,
                                'school_year_id' => $tahunAngkatan->id,
                            ], [
                                'display_name'   => $namaRombel,
                            ]);

                            // Parse tanggal lahir
                            $bornDate = null;
                            if (!empty($tanggalLahir)) {
                                try {
                                    $bornDate = Carbon::parse($tanggalLahir)->format('Y-m-d');
                                } catch (\Exception $e) {
                                    $bornDate = null;
                                }
                            }

                            // 3. Update atau Create Student
                            Student::updateOrCreate(
                                [
                                    'nisn' => $nisn,
                                ],
                                [
                                    'rombel_id'  => $rombel->id,
                                    'name'       => strtoupper($nama),
                                    'gender'     => $gender,
                                    'born_place' => $tempatLahir ? strtoupper($tempatLahir) : null,
                                    'born_date'  => $bornDate,
                                ]
                            );

                            $successCount++;
                        }

                        DB::commit();
                        fclose($file);

                        // Hapus temporary file setelah selesai
                        Storage::disk('local')->delete($data['file']);

                        Notification::make()
                            ->title('Import Berhasil')
                            ->body("Berhasil mengimpor {$successCount} data siswa.")
                            ->success()
                            ->send();
                    } catch (\Exception $e) {

                        DB::rollBack();
                        if (is_resource($file)) {
                            fclose($file);
                        }

                        Notification::make()
                            ->title('Gagal mengimpor data')
                            ->body('Terjadi kesalahan: ' . $e->getMessage())
                            ->danger()
                            ->persistent()
                            ->send();
                    }
                }),
        ];
    }
}
