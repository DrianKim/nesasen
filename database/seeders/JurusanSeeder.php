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
                'nama_jurusan' => 'Akuntansi dan Keuangan Lembaga',
                'kode_jurusan' => 'AKL',
            ],
            [
                'nama_jurusan' => 'Pemasaran',
                'kode_jurusan' => 'PS',
            ],
            [
                'nama_jurusan' => 'Manajemen Perkantoran dan Layanan Bisnis',
                'kode_jurusan' => 'MPLB',
            ],
            [
                'nama_jurusan' => 'Rekayasa Perangkat Lunak',
                'kode_jurusan' => 'RPL',
            ],
            [
                'nama_jurusan' => 'Teknik Komputer dan Jaringan',
                'kode_jurusan' => 'TKJ',
            ],
            [
                'nama_jurusan' => 'Desain Komunikasi Visual',
                'kode_jurusan' => 'DKV',
            ],
            [
                'nama_jurusan' => 'Teknik Otomotif',
                'kode_jurusan' => 'TO',
            ],
            [
                'nama_jurusan' => 'Teknik Mesin',
                'kode_jurusan' => 'TM',
            ],
            [
                'nama_jurusan' => 'Teknik Logistik',
                'kode_jurusan' => 'TL',
            ],
            [
                'nama_jurusan' => 'Kuliner',
                'kode_jurusan' => 'KL',
            ],
        ]);
    }
}
