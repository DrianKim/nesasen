<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Murid;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;

class MuridImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            if ($index == 0) {
                continue;
            }

            $nama = $row[0];
            $kelas_id = $row[1];

            $username = strtolower(str_replace(' ', '_', $nama));

            $murid = Murid::create([
                'nama' => $nama,
                'kelas_id' => $kelas_id,
            ]);

            User::create([
                'username' => $username,
                'password' => null,
                'murid_id' => $murid->id,
                'role_id' => 4,
            ]);
        }
    }

    public function model(array $row)
    {
        return new Murid([
            //
        ]);
    }
}
