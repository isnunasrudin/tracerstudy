<?php

namespace App\Console\Commands;

use App\Models\Rombel;
use App\Models\SchoolYear;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ScrappingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrapping';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $result = json_decode(Storage::get('scrapping.json'));

        $ta = SchoolYear::first();

        dd(collect($result->data->data)->filter(function($item){
            return preg_match('/^12\s/', $item->anggota_rombel->rombongan_belajar->nama);
        })->map(function($item){

            $indonesianMonths = [
                'Januari' => '01', 'Februari' => '02', 'Maret' => '03', 'April' => '04',
                'Mei' => '05', 'Juni' => '06', 'Juli' => '07', 'Agustus' => '08',
                'September' => '09', 'Oktober' => '10', 'November' => '11', 'Desember' => '12'
            ];

            $born_date = str_replace(array_keys($indonesianMonths), array_values($indonesianMonths), $item->tanggal_lahir);

            return (object) [
                'name' => $item->nama,
                'nisn' => $item->nisn,
                'gender' => $item->jenis_kelamin,
                'rombel' => preg_replace('/^12\s/', '', $item->anggota_rombel->rombongan_belajar->nama),
                'born_date' => Carbon::createFromFormat('d m Y', $born_date),
                'born_place' => $item->tempat_lahir,
            ];
        })->map(function($item) use($ta) {


            $rombel = Rombel::firstOrCreate([
                'name' => $item->rombel,
                'display_name' => $item->rombel,
                'school_year_id' => $ta->id,
            ]);

            Student::create([
                'name' => $item->name,
                'nisn' => $item->nisn,
                'gender' => $item->gender,
                'born_date' => $item->born_date,
                'born_place' => $item->born_place,
                'rombel_id' => $rombel->id,
            ]);

        }));
    }
}
