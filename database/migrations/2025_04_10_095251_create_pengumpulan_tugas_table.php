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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignid('tugas_id')->constrained('tugas')->onDelete('cascade');
            $table->foreignid('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->enum('status', ['Sudah Dikerjakan', 'Belum Dikerjakan'])->default('Belum Dikerjakan');
            $table->text('keterangan')->nullable();
            $table->date('waktu_pengumpulan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
