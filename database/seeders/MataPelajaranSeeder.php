<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //         No.	Nama Mata Pelajaran
        // 1	Bahasa Indonesia
        // 2	Bahasa Inggris
        // 3	Informatika
        // 4	Matematika Wajib
        // 5	Pendidikan Agama dan Budi Pekerti
        // 6	Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)
        // 7	Pendidikan Pancasila dan Kewarganegaraan (PPKn)
        // 8	Projek Penguatan Profil Pelajar Pancasila (P5)
        // 9	Sejarah Indonesia
        // 10	Seni Budaya
        DB::table('mata_pelajaran')->insert([
            [
                'nama_mapel' => 'Bahasa Indonesia',
                'kode_mapel' => 'B. Indonesia',
            ],
            [
                'nama_mapel' => 'Bahasa Inggris',
                'kode_mapel' => 'B. Inggris',
            ],
            [
                'nama_mapel' => 'Bahasa Jepang',
                'kode_mapel' => 'B. Jepang',
            ],
            [
                'nama_mapel' => 'Bahasa Mandarin',
                'kode_mapel' => 'B. Mandarin',
            ],
            [
                'nama_mapel' => 'Informatika',
                'kode_mapel' => 'Informatika',
            ],
            [
                'nama_mapel' => 'Matematika',
                'kode_mapel' => 'MTK',
            ],
            [
                'nama_mapel' => 'Pendidikan Agama Islam',
                'kode_mapel' => 'PAI',
            ],
            [
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
                'kode_mapel' => 'PJOK',
            ],
            [
                'nama_mapel' => 'Pendidikan Pancasila dan Kewarganegaraan',
                'kode_mapel' => 'PPKn',
            ],
            [
                'nama_mapel' => 'Sejarah Indonesia',
                'kode_mapel' => 'Sejarah',
            ],
            [
                'nama_mapel' => 'Seni Budaya',
                'kode_mapel' => 'Senbud',
            ],
            [
                'nama_mapel' => 'Kewirausahaan',
                'kode_mapel' => 'KWU',
            ],
            [
                'nama_mapel' => 'Projek Penguatan Profil Pelajar Pancasila',
                'kode_mapel' => 'P5',
            ],
            [
                'nama_mapel' => 'Ilmu Pengetahuan Alam dan Sosial',
                'kode_mapel' => 'IPAS',
            ],
        ]);
    }
}
