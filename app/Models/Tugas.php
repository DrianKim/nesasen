<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $fillable = [
        'mapel_kelas_id',
        'judul_tugas',
        'deskripsi',
        'lampiran',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
    ];

    public function mapelKelas()
    {
        return $this->belongsTo(MapelKelas::class, 'mapel_id');
    }

    public function pengumpulan_tugas()
    {
        return $this->hasMany(PengumpulanTugas::class, 'tugas_id');
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'tugas_siswa', 'tugas_id', 'siswa_id');
    }
}
