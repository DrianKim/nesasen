<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MapelKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mapel_kelas')->insert([
            [
                'mapel_id' => 1,
                'kelas_id' => 2,
                'guru_id' => 1,
            ],
            [
                'mapel_id' => 2,
                'kelas_id' => 3,
                'guru_id' => 2,
            ],
            [
                'mapel_id' => 3,
                'kelas_id' => 4,
                'guru_id' => 3,
            ],
        ]);
    }
}
