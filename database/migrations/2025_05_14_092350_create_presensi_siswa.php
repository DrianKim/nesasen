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
        Schema::create('presensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->string('foto_masuk')->nullable();
            $table->string('foto_keluar')->nullable();
            $table->json('lokasi_masuk')->nullable();
            $table->json('lokasi_keluar')->nullable();
            $table->text('alasan')->nullable();
            $table->enum('status_kehadiran', ['hadir', 'terlambat', 'alpa'])->default('hadir');
            $table->enum('status_lokasi', ['dalam_area', 'di_luar_area'])->default('dalam_area');
            $table->timestamps();

            $table->index(['siswa_id', 'tanggal']);
            $table->index('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi_siswa');
    }
};
