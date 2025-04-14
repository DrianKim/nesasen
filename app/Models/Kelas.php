<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class   Kelas extends Model
{
    protected $table = 'kelas';
    protected $fillable = [
        'jurusan_id',
        'tingkat',
        'no_kelas',
        'tahun_ajaran',
    ];

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function murid()
    {
        return $this->hasMany(Murid::class);
    }

    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class);
    }

    public function walas()
    {
        return $this->hasMany(walas::class);
    }


}
