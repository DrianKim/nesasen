<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensi';
    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'jenis_absen',
        'waktu_absen',
        'foto_absen',
        'lokasi_absen',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
