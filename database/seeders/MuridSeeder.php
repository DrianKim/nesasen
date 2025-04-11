<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MuridSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('murid')->insert([
            'user_id' => 1,
            'kelas_id' => 1,
            'nis' => '123456',
            'no_hp' => '08123456789',
            'jenis_kelamin' => 'Laki-laki',
            'alamat' => 'Jl. Contoh Alamat',
            // 'foto_profil' => 'path/to/foto_profil.jpg',
        ]);
    }
}
