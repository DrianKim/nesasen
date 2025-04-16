<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    protected $table = 'murid';

    protected $fillable = [
        'nama',
        'kelas_id',
        'nis',
        'tanggal_lahir',
        'no_hp',
        'email',
        'jenis_kelamin',
        'alamat',
        'foto_profil',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'murid_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
