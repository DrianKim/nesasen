<?php

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
        Schema::create('mapel_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignid('mapel_id')->constrained('mata_pelajaran')->onDelete('cascade');
            $table->foreignid('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignid('guru_id')->constrained('guru')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapel_kelas');
    }
};
