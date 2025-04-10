<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['nama_role' => 'admin', 'deskripsi' => 'Admin Kurikulum'],
            ['nama_role' => 'walas', 'deskripsi' => 'Wali Kelas'],
            ['nama_role' => 'guru', 'deskripsi' => 'Guru Mapel'],
            ['nama_role' => 'admin', 'deskripsi' => 'Siswa/i Sekolah'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'nama_role' => $role['nama_role'],
                'deskripsi' => $role['deskripsi'],
            ]);
        }
    }
}
