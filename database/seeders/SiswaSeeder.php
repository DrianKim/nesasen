<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('siswa')->insert([
            [
                'nama' => 'Dede Sukmadik',
                'kelas_id' => 2,
                'nis' => '123456',
                'tanggal_lahir' => '2005-01-01',
                'no_hp' => '08123456789',
                'email' => 'dede@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Ngawi No. 1',
            ],
            [
                'nama' => 'Siti Nurjanah',
                'kelas_id' => 3,
                'nis' => '123457',
                'tanggal_lahir' => '2005-03-12',
                'no_hp' => '089612345678',
                'email' => 'siti@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Mawar No. 5',
            ],
            [
                'nama' => 'Bambang Pamungkas',
                'kelas_id' => 2,
                'nis' => '123458',
                'tanggal_lahir' => '2005-05-22',
                'no_hp' => '081289990000',
                'email' => 'bambang@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Kenangan No. 9',
            ],
            [
                'nama' => 'Ayu Lestari',
                'kelas_id' => 4,
                'nis' => '123459',
                'tanggal_lahir' => '2005-07-10',
                'no_hp' => '082112341234',
                'email' => 'ayu@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Melati No. 11',
            ],
            [
                'nama' => 'Rizky Maulana',
                'kelas_id' => 3,
                'nis' => '123460',
                'tanggal_lahir' => '2005-09-30',
                'no_hp' => '087812345678',
                'email' => 'rizky@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Anggrek No. 7',
            ],
            [
                'nama' => 'Nadia Zahra',
                'kelas_id' => 2,
                'nis' => '123461',
                'tanggal_lahir' => '2005-02-14',
                'no_hp' => '085612345678',
                'email' => 'nadia@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Sakura No. 4',
            ],
            [
                'nama' => 'Wili Salim',
                'kelas_id' => 4,
                'nis' => '123462',
                'tanggal_lahir' => '2005-06-06',
                'no_hp' => '081345678900',
                'email' => 'agus@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Durian No. 10',
            ],
            [
                'nama' => 'Putri Andini',
                'kelas_id' => 3,
                'nis' => '123463',
                'tanggal_lahir' => '2005-11-11',
                'no_hp' => '088812345678',
                'email' => 'putri@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Cemara No. 8',
            ],
            [
                'nama' => 'Dani Pratama',
                'kelas_id' => 2,
                'nis' => '123464',
                'tanggal_lahir' => '2005-04-18',
                'no_hp' => '083812345678',
                'email' => 'dani@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Apel No. 2',
            ],
            [
                'nama' => 'Melati Anjani',
                'kelas_id' => 4,
                'nis' => '123465',
                'tanggal_lahir' => '2005-08-25',
                'no_hp' => '082134567890',
                'email' => 'melati@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Pisang No. 6',
            ],
        ]);
    }
}
