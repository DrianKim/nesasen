<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WalasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('walas')->insert([
            'user_id' => 1,
            'kelas_id' => 1,
        ],[
            'user_id' => 2,
            'kelas_id' => 2,
        ],[
            'user_id' => 3,
            'kelas_id' => 3,
        ]);
    }
}
