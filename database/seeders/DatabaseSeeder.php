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

        // User::create([
        //     'nama' => 'admin',
        //     'email' => 'admin@example.com',
        //     'jabatan' => 'Admin',
        //     'password' => '123',
        //     'is_tugas' => false,
        // ]);

        $this->call([
            RoleSeeder::class,
            JurusanSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
            GuruSeeder::class,
            MataPelajaranSeeder::class,
            MapelKelasSeeder::class,
            UserSeeder::class,
            // WalasSeeder::class,
        ]);

    }
}
