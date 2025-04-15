<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class
UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert(
            [
                'username' => 'admin',
                'name_admin' => 'Admin',
                'password' => Hash::make('admin123'),
                'role_id' => 1,
            ],
        );

        $userList = [
            ['username' => 'dede murid', 'password' => Hash::make('dede123'), 'murid_id' => 1, 'role_id' => 4],
            ['username' => 'siti murid', 'password' => Hash::make('siti123'), 'murid_id' => 2, 'role_id' => 4],
            ['username' => 'bambang murid', 'password' => Hash::make('bambang123'), 'murid_id' => 3, 'role_id' => 4],
            ['username' => 'ayu murid', 'password' => Hash::make('ayu123'), 'murid_id' => 4, 'role_id' => 4],
            ['username' => 'rizky murid', 'password' => Hash::make('rizky123'), 'murid_id' => 5, 'role_id' => 4],
            ['username' => 'nadia murid', 'password' => Hash::make('nadia123'), 'murid_id' => 6, 'role_id' => 4],
            ['username' => 'agus murid', 'password' => Hash::make('agus123'), 'murid_id' => 7, 'role_id' => 4],
            ['username' => 'putri murid', 'password' => Hash::make('putri123'), 'murid_id' => 8, 'role_id' => 4],
            ['username' => 'dani murid', 'password' => Hash::make('dani123'), 'murid_id' => 9, 'role_id' => 4],
            ['username' => 'melati murid', 'password' => Hash::make('melati123'), 'murid_id' => 10, 'role_id' => 4],
        ];

        $guruList = [
            ['username' => 'dedi guru', 'password' => Hash::make('dedi123'), 'guru_id' => 1, 'role_id' => 3],
            ['username' => 'siti guru', 'password' => Hash::make('siti123'), 'guru_id' => 2, 'role_id' => 3],
            ['username' => 'bambang guru', 'password' => Hash::make('bambang123'), 'guru_id' => 3, 'role_id' => 3],
            ['username' => 'ayu guru', 'password' => Hash::make('ayu123'), 'guru_id' => 4, 'role_id' => 3],
            ['username' => 'rizky guru', 'password' => Hash::make('rizky123'), 'guru_id' => 5, 'role_id' => 3],
            ['username' => 'nadia guru', 'password' => Hash::make('nadia123'), 'guru_id' => 6, 'role_id' => 3],
            ['username' => 'agus guru', 'password' => Hash::make('agus123'), 'guru_id' => 7, 'role_id' => 3],
            ['username' => 'putri guru', 'password' => Hash::make('putri123'), 'guru_id' => 8, 'role_id' => 3],
            ['username' => 'dani guru', 'password' => Hash::make('dani123'), 'guru_id' => 9, 'role_id' => 3],
            ['username' => 'melati guru', 'password' => Hash::make('melati123'), 'guru_id' => 10, 'role_id' => 3],
        ];

        foreach ($userList as $user) {
            DB::table('users')->insert($user);
        }

        foreach ($guruList as $guru) {
            DB::table('users')->insert($guru);
        }
    }
}
