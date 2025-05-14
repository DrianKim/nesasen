<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class absensiSiswa extends Model
{
    protected $table = 'absensi_siswa';
    protected $fillable = [
        'tanggal',
        'siswa_id',
        'status',
        'jenis_absen',
        'waktu_absen',
        'foto_absen',
        'lokasi_absen',
        'keterangan',
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

}
