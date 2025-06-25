<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'mapel_kelas_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
    ];

    public function mapelKelas()
    {
        return $this->belongsto(MapelKelas::class);
    }

    public function kelas()
    {
        return $this->belongsto(Kelas::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }
}
