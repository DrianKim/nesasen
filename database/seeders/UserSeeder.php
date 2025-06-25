<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class
UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'username' => 'admin',
                'name_admin' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
        );

        $siswaList = [
            ['username' => 'dede siswa', 'password' => Hash::make('dede123'), 'role_id' => 4],
            ['username' => 'siti siswa', 'password' => Hash::make('siti123'), 'role_id' => 4],
            ['username' => 'bambang siswa', 'password' => Hash::make('bambang123'), 'role_id' => 4],
            ['username' => 'ayu siswa', 'password' => Hash::make('ayu123'), 'role_id' => 4],
            ['username' => 'rizky siswa', 'password' => Hash::make('rizky123'), 'role_id' => 4],
            ['username' => 'nadia siswa', 'password' => Hash::make('nadia123'), 'role_id' => 4],
            ['username' => 'wili siswa', 'password' => Hash::make('wili123'), 'role_id' => 4],
            ['username' => 'putri siswa', 'password' => Hash::make('putri123'), 'role_id' => 4],
            ['username' => 'dani siswa', 'password' => Hash::make('dani123'), 'role_id' => 4],
            ['username' => 'melati siswa', 'password' => Hash::make('melati123'), 'role_id' => 4],
            ['username' => 'fajar siswa', 'password' => Hash::make('fajar123'), 'role_id' => 4],
            ['username' => 'intan siswa', 'password' => Hash::make('intan123'), 'role_id' => 4],
            ['username' => 'galih siswa', 'password' => Hash::make('galih123'), 'role_id' => 4],
            ['username' => 'salsa siswa', 'password' => Hash::make('salsa123'), 'role_id' => 4],
            ['username' => 'yoga siswa', 'password' => Hash::make('yoga123'), 'role_id' => 4],
            ['username' => 'dewi siswa', 'password' => Hash::make('dewi123'), 'role_id' => 4],
            ['username' => 'rendi siswa', 'password' => Hash::make('rendi123'), 'role_id' => 4],
            ['username' => 'citra siswa', 'password' => Hash::make('citra123'), 'role_id' => 4],
            ['username' => 'bagus siswa', 'password' => Hash::make('bagus123'), 'role_id' => 4],
            ['username' => 'lina siswa', 'password' => Hash::make('lina123'), 'role_id' => 4],
            ['username' => 'dimas siswa', 'password' => Hash::make('dimas123'), 'role_id' => 4],
            ['username' => 'rina siswa', 'password' => Hash::make('rina123'), 'role_id' => 4],
            ['username' => 'fikri siswa', 'password' => Hash::make('fikri123'), 'role_id' => 4],
            ['username' => 'sari siswa', 'password' => Hash::make('sari123'), 'role_id' => 4],
        ];

        $guruList = [
            ['username' => 'dedi guru', 'password' => Hash::make('dedi123'), 'role_id' => 3],
            ['username' => 'siti guru', 'password' => Hash::make('siti123'), 'role_id' => 3],
            ['username' => 'bambang guru', 'password' => Hash::make('bambang123'), 'role_id' => 3],
            ['username' => 'ayu guru', 'password' => Hash::make('ayu123'), 'role_id' => 3],
            ['username' => 'rizky guru', 'password' => Hash::make('rizky123'), 'role_id' => 3],
            ['username' => 'nadia guru', 'password' => Hash::make('nadia123'), 'role_id' => 3],
            ['username' => 'agus guru', 'password' => Hash::make('agus123'), 'role_id' => 3],
            ['username' => 'putri guru', 'password' => Hash::make('putri123'), 'role_id' => 3],
            ['username' => 'dani guru', 'password' => Hash::make('dani123'), 'role_id' => 3],
            ['username' => 'melati guru', 'password' => Hash::make('melati123'), 'role_id' => 3],
        ];

        foreach ($siswaList as $siswa) {
            DB::table('users')->insert($siswa);
        }

        foreach ($guruList as $guru) {
            DB::table('users')->insert($guru);
        }
    }
}
