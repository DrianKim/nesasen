<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $table = 'users';
    protected $fillable = [
        'username',
        'name_admin',
        'password',
        'siswa_id',
        'guru_id',
        'role_id'
    ];

    public function usernanme()
    {
        return 'username';
    }


    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function walas()
    {
        return $this->belongsTo(walas::class);
    }

    public function guru()
    {
        return $this->hasOne(Guru::class);
    }

    public function kelas()
    {
        return $this->hasOne(Kelas::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
