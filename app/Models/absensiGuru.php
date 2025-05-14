<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class absensiGuru extends Model
{
    protected $table = 'absensi_guru';
    protected $fillable = [
        'tanggal',
        'guru_id',
        'status',
        'jenis_absen',
        'waktu_absen',
        'foto_absen',
        'lokasi_absen',
        'keterangan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

}
