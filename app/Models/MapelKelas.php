<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapelKelas extends Model
{
    protected $table = 'mapel_kelas';
    protected $fillable = [
        'mapel_id',
        'kelas_id',
        'guru_id',
    ];
    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function walas()
    {
        return $this->belongsTo(walas::class);
    }
}
