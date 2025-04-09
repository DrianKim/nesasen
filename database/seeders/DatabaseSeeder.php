<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'admin',
            'email' => 'admin@example.com',
            'jabatan' => 'Admin',
            'password' => '123',
            'is_tugas' => false,
        ]);

        User::create([
            'nama' => 'dede',
            'email' => 'dede@example.com',
            'jabatan' => 'Karyawan',
            'password' => '123',
            'is_tugas' => false,
        ]);

        User::create([
            'nama' => 'didi',
            'email' => 'didi@example.com',
            'jabatan' => 'Karyawan',
            'password' => '123',
            'is_tugas' => false,
        ]);

        User::create([
            'nama' => 'dodo',
            'email' => 'dodo@example.com',
            'jabatan' => 'Karyawan',
            'password' => '123',
            'is_tugas' => true,
        ]);

        User::create([
            'nama' => 'dede admin',
            'email' => 'dedeadmin@example.com',
            'jabatan' => 'Admin',
            'password' => '123',
            'is_tugas' => false,
        ]);
    }
}
