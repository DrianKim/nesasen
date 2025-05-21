<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('guru')->insert([
            [
                'user_id' => 12,
                'nama' => 'Dedi Purnama',
                'nip' => '9876543210',
                'tanggal_lahir' => '1980-01-01',
                'no_hp' => '081234567890',
                'email' => 'dedi.purnama@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Merdeka No. 10',
            ],
            [
                'user_id' => 13,
                'nama' => 'Siti Anisa',
                'nip' => '9876543211',
                'tanggal_lahir' => '1982-05-12',
                'no_hp' => '081234567891',
                'email' => 'siti.anisa@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Melati No. 15',
            ],
            [
                'user_id' => 14,
                'nama' => 'Bambang Widodo',
                'nip' => '9876543212',
                'tanggal_lahir' => '1979-06-23',
                'no_hp' => '081234567892',
                'email' => 'bambang.widodo@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Kenangan No. 8',
            ],
            [
                'user_id' => 15,
                'nama' => 'Ayu Lestari',
                'nip' => '9876543213',
                'tanggal_lahir' => '1983-07-25',
                'no_hp' => '081234567893',
                'email' => 'ayu.lestari@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Pahlawan No. 20',
            ],
            [
                'user_id' => 16,
                'nama' => 'Rizky Maulana',
                'nip' => '9876543214',
                'tanggal_lahir' => '1984-09-30',
                'no_hp' => '081234567894',
                'email' => 'rizky.maulana@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Cemara No. 5',
            ],
            [
                'user_id' => 17,
                'nama' => 'Nadia Zahra',
                'nip' => '9876543215',
                'tanggal_lahir' => '1985-10-12',
                'no_hp' => '081234567895',
                'email' => 'nadia.zahra@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Sakura No. 7',
            ],
            [
                'user_id' => 18,
                'nama' => 'Agus Salim',
                'nip' => '9876543216',
                'tanggal_lahir' => '1986-12-16',
                'no_hp' => '081234567896',
                'email' => 'agus.salim@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Jambu No. 4',
            ],
            [
                'user_id' => 19,
                'nama' => 'Putri Andini',
                'nip' => '9876543217',
                'tanggal_lahir' => '1987-01-21',
                'no_hp' => '081234567897',
                'email' => 'putri.andini@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Durian No. 12',
            ],
            [
                'user_id' => 20,
                'nama' => 'Dani Pratama',
                'nip' => '9876543218',
                'tanggal_lahir' => '1988-03-18',
                'no_hp' => '081234567898',
                'email' => 'dani.pratama@gmail.com',
                'jenis_kelamin' => 'Laki-laki',
                'alamat' => 'Jl. Pisang No. 3',
            ],
            [
                'user_id' => 21,
                'nama' => 'Melati Anjani',
                'nip' => '9876543219',
                'tanggal_lahir' => '1989-05-05',
                'no_hp' => '081234567899',
                'email' => 'melati.anjani@gmail.com',
                'jenis_kelamin' => 'Perempuan',
                'alamat' => 'Jl. Apel No. 8',
            ],
        ]);
    }
}