<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'nama_jurusan',
        'kode_jurusan',
    ];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'jurusan_id');
    }

    public function murid()
    {
        return $this->hasMany(Murid::class, 'kelas_id');
    }
}
