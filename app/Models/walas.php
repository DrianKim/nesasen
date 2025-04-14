<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class walas extends Model
{
    protected $table = 'walas';
    protected $fillable = [
        'guru_id',
        'kelas_id',
    ];
    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function Guru()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapelKelas()
    {
        return $this->hasMany(MapelKelas::class);
    }

    public function mataPelajaran()
    {
        return $this->hasMany(MataPelajaran::class);
    }

}
