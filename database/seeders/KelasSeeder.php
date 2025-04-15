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
                'jurusan_id' => 1,
                'tingkat' => (''),
                'no_kelas' => null,
            ],
            [
                'jurusan_id' => 2,
                'tingkat' => ('X'),
                'no_kelas' => 1,
            ],
            [
                'jurusan_id' => 3,
                'tingkat' => ('X'),
                'no_kelas' => 2,
            ],
            [
                'jurusan_id' => 4,
                'tingkat' => ('X'),
                'no_kelas' => 3,
            ],
        ]);
    }
}
