<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            [
                'tingkat' => ('X'),
                'jurusan_id' => 5,
                'no_kelas' => 1,
            ],
            [
                'tingkat' => ('X'),
                'jurusan_id' => 2,
                'no_kelas' => 1,
            ],
            [
                'tingkat' => ('X'),
                'jurusan_id' => 3,
                'no_kelas' => 2,
            ],
            [
                'tingkat' => ('X'),
                'jurusan_id' => 4,
                'no_kelas' => 3,
            ],
        ]);
    }
}
