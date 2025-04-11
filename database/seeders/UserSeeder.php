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
            // Admins
            [
                'username' => 'admin_zaky',
                'nama' => 'Zaky Maulana',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
            [
                'username' => 'admin_reva',
                'nama' => 'Reva Nuraini',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
            [
                'username' => 'admin_rizal',
                'nama' => 'Rizal Hakim',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
            [
                'username' => 'admin_intan',
                'nama' => 'Intan Lestari',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
            [
                'username' => 'admin_devi',
                'nama' => 'Devi Oktaviani',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],

            // Walas
            [
                'username' => 'walas_firman',
                'nama' => 'Firman Saputra',
                'password' => Hash::make('walas123'),
                'role_id' => 2,
            ],
            [
                'username' => 'walas_nadia',
                'nama' => 'Nadia Azzahra',
                'password' => Hash::make('walas123'),
                'role_id' => 2,
            ],
            [
                'username' => 'walas_bambang',
                'nama' => 'Bambang Suryono',
                'password' => Hash::make('walas123'),
                'role_id' => 2,
            ],
            [
                'username' => 'walas_dina',
                'nama' => 'Dina Kusuma',
                'password' => Hash::make('walas123'),
                'role_id' => 2,
            ],
            [
                'username' => 'walas_eko',
                'nama' => 'Eko Prasetyo',
                'password' => Hash::make('walas123'),
                'role_id' => 2,
            ],

            // Guru Mapel
            [
                'username' => 'guru_andini',
                'nama' => 'Andini Putri',
                'password' => Hash::make('guru123'),
                'role_id' => 3,
            ],
            [
                'username' => 'guru_rama',
                'nama' => 'Rama Dwianto',
                'password' => Hash::make('guru123'),
                'role_id' => 3,
            ],
            [
                'username' => 'guru_siti',
                'nama' => 'Siti Nurlaila',
                'password' => Hash::make('guru123'),
                'role_id' => 3,
            ],
            [
                'username' => 'guru_wahyu',
                'nama' => 'Wahyu Hidayat',
                'password' => Hash::make('guru123'),
                'role_id' => 3,
            ],
            [
                'username' => 'guru_dian',
                'nama' => 'Dian Cahyani',
                'password' => Hash::make('guru123'),
                'role_id' => 3,
            ],

            // Murid
            [
                'username' => 'murid_aldi',
                'nama' => 'Aldi Ramadhan',
                'password' => Hash::make('murid123'),
                'role_id' => 4,
            ],
            [
                'username' => 'murid_rahma',
                'nama' => 'Rahma Safitri',
                'password' => Hash::make('murid123'),
                'role_id' => 4,
            ],
            [
                'username' => 'murid_fahri',
                'nama' => 'Fahri Maulana',
                'password' => Hash::make('murid123'),
                'role_id' => 4,
            ],
            [
                'username' => 'murid_laras',
                'nama' => 'Laras Widya',
                'password' => Hash::make('murid123'),
                'role_id' => 4,
            ],
            [
                'username' => 'murid_rehan',
                'nama' => 'Rehan Saputra',
                'password' => Hash::make('murid123'),
                'role_id' => 4,
            ],
        ]);
    }
}
