<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class mapelPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('mapel_pelajaran')->insert([
            [
                'nama_mapel' => 'Matematika',
                'kode_mapel' => 'Mtk',
            ],
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kode_mapel' => 'B. Indonesia',
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'kode_mapel' => 'B. Inggris',
            ],
            [
                'nama_mapel' => 'Pendidikan Agama Islam',
                'kode_mapel' => 'PAI',
            ],
        ]);
    }
}
