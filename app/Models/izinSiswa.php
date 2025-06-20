<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class izinSiswa extends Model
{
    protected $table = 'izin_siswa';
    protected $fillable = [
        'tanggal',
        'siswa_id',
        'jenis_izin',
        'keterangan',
        'lampiran'
    ];
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }
}
