<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $fillable = [
        'user_id',
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
        return $this->belongsTo(User::class);
    }

    public function walas()
    {
        return $this->hasOne(Walas::class);
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }

    public function mapel_kelas()
    {
        return $this->hasOne(MapelKelas::class);
    }

    public function izin()
    {
        return $this->hasMany(IzinGuru::class);
    }
    public function absensiGuru()
    {
        return $this->hasMany(absensiGuru::class);
    }

}
