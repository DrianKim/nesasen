<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $fillable = [
        'nama',
        'nip',
        'tanggal_lahir',
        'no_hp',
        'email',
        'jenis_kelamin',
        'alamat',
        'foto_profil',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
    public function walas()
    {
        return $this->hasMany(walas::class);
    }
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'guru_id');
    }

    public function MapelKelas()
    {
        return $this->hasOne(MapelKelas::class);
    }

}
