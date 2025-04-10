<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::insert([
            [
                'username' => 'admin',
                'nama' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
            [
                'username' => 'walas',
                'nama' => 'Wali Kelas',
                'password' => Hash::make('walas123'),
                'role_id' => 2,
            ],
            [
                'username' => 'guru',
                'nama' => 'Guru Mapel',
                'password' => Hash::make('guru123'),
                'role_id' => 3,
            ],
            [
                'username' => 'murid',
                'nama' => 'Siswa/i',
                'password' => Hash::make('murid123'),
                'role_id' => 4,
            ],
        ]);
    }
}
