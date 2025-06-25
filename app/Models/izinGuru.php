<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinGuru extends Model
{
    protected $table = 'izin_guru';
    protected $fillable = [
        'tanggal',
        'guru_id',
        'jenis_izin',
        'keterangan',
        'lampiran'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
