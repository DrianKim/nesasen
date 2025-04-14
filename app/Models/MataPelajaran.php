<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';
    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
    ];

    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class, 'mapel_id');
    }
    
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }

    public function walas()
    {
        return $this->belongsTo(walas::class, 'guru_id');
    }

}
