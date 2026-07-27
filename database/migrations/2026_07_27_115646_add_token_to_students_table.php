<?php

use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('token')->nullable()->index();
        });

        DB::table('students')
            ->select('id', 'nisn')
            ->whereNull('token')
            ->chunkById(500, function ($students) {
                foreach ($students as $student) {
                    DB::table('students')
                        ->where('id', $student->id)
                        ->update([
                            'token' => md5($student->id . $student->nisn),
                        ]);
                }
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['token']);
            $table->dropColumn('token');
        });
    }
};
