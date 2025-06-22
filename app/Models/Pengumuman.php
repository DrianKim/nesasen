<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    protected $fillable = [
        'judul', 'isi', 'ditujukan_untuk', 'tanggal',
        'kadaluarsa_sampai', 'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
