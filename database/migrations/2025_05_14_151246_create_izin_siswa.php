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
        Schema::create('izin_siswa', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->enum('jenis_izin', ['Keperluan Keluarga', 'Sakit', 'Keperluan Sekolah', 'Lainnya']);
            $table->string('keterangan')->nullable();
            $table->string('lampiran')->nullable();
            $table->timestamps();
        });
        // $table->id();
        // $table->date('tanggal');
        // $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
        // $table->enum('status', ['masuk', 'pulang']);
        // $table->enum('jenis_izin', ['Hadir', 'Sakit', 'Izin', 'Alfa']);
        // $table->datetime('waktu_izin');
        // $table->string('foto_izin');
        // $table->string('lokasi_izin');
        // $table->string('keterangan')->nullable();
        // $table->timestamps();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_siswa');
    }
};
