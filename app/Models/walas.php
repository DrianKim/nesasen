<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walas extends Model
{
    protected $table = 'walas';
    protected $fillable = [
        'user_id',
        'kelas_id',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id', 'kelas_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class, 'kelas_id', 'kelas_id');
    }

    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class, 'mapel_kelas_id', 'id');
    }

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'jurusan_id', 'id');
    }


}
