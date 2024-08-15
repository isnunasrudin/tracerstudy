<?php

use App\Models\Rombel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Rombel::class)->constrained();

            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('nisn');
            $table->string('whatsapp')->nullable();

            $table->string('born_place');
            $table->date('born_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
