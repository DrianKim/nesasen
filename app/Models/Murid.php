<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Murid extends Model
{
    protected $table = 'murid';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'no_hp',
        'jenis_kelamin',
        'alamat',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'jurusan_id');
    }
}
