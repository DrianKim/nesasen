<?php

namespace App\Imports;

use App\Models\Guru;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class GuruImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row)
        {
            if ($row[0] == 'nama'){
                continue;
            }

            $nama = $row[0];

            $username = strtolower(str_replace('', '_', $nama));

            $guru = Guru::create([
                'nama' => $nama,
            ]);

            User::create([
                'username' => $username,
                'password' => null,
                'guru_id' => $guru->id,
                'role_id' => 3,
            ]);
        }
    }

    public function model(array $row)
    {
        return new Guru([
            //
        ]);
    }
}
