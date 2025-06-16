<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class presensiGuru extends Model
{
    use HasFactory;

    protected $table = 'presensi_guru';

    protected $fillable = [
        'guru_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'foto_masuk',
        'foto_keluar',
        'lokasi_masuk',
        'lokasi_keluar',
        'alasan',
        'status_kehadiran',
        'status_lokasi',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'lokasi_masuk' => 'array',
        'lokasi_keluar' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFotoMasukUrlAttribute()
    {
        if ($this->foto_masuk) {
            return asset('storage/' . $this->foto_masuk);
        }
        return null;
    }

    public function getFotoKeluarUrlAttribute()
    {
        if ($this->foto_keluar) {
            return asset('storage/' . $this->foto_keluar);
        }
        return null;
    }

    public function getStatusTextAttribute()
    {
        $statusMap = [
            'hadir' => 'Hadir',
            'terlambat' => 'Terlambat',
            'di_luar_area' => 'Di Luar Area',
            'tidak_hadir' => 'Tidak Hadir'
        ];

        return $statusMap[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colorMap = [
            'hadir' => 'success',
            'terlambat' => 'warning',
            'di_luar_area' => 'info',
            'tidak_hadir' => 'danger'
        ];

        return $colorMap[$this->status] ?? 'secondary';
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate('tanggal', $date);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function hasCheckedIn()
    {
        return !is_null($this->jam_masuk);
    }

    public function hasCheckedOut()
    {
        return !is_null($this->jam_keluar);
    }

    public function getDurasiKerjaAttribute()
    {
        if ($this->jam_masuk && $this->jam_keluar) {
            $masuk = Carbon::createFromFormat('H:i:s', $this->jam_masuk);
            $keluar = Carbon::createFromFormat('H:i:s', $this->jam_keluar);

            $diff = $keluar->diff($masuk);
            return $diff->format('%H:%I');
        }

        return null;
    }

    public function getLokasiMasukCoordinatesAttribute()
    {
        if ($this->lokasi_masuk && is_array($this->lokasi_masuk)) {
            return [
                'lat' => $this->lokasi_masuk['latitude'] ?? null,
                'lng' => $this->lokasi_masuk['longitude'] ?? null
            ];
        }
        return null;
    }

    public function getLokasiKeluarCoordinatesAttribute()
    {
        if ($this->lokasi_keluar && is_array($this->lokasi_keluar)) {
            return [
                'lat' => $this->lokasi_keluar['latitude'] ?? null,
                'lng' => $this->lokasi_keluar['longitude'] ?? null
            ];
        }
        return null;
    }
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
