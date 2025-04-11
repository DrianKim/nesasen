<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JurusanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jurusan')->insert([
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'kode_jurusan' => 'RPL',
            ],
            [
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'kode_jurusan' => 'TKJ',
            ],
            [
                'nama_jurusan' => 'Desain Komunikasi dan Visual',
                'kode_jurusan' => 'DKV',
            ],
        ]);
    }
}
