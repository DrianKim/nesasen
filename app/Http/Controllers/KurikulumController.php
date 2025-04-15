<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Murid;
use App\Models\walas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Psy\CodeCleaner\FunctionContextPass;

class KurikulumController extends Controller
{

    // walas
    public function data_walas()
    {
        $data = array(
            'title' => 'Halaman Daftar Wali Kelas',
            'menu_admin_data_walas' => 'active',
            'walas' => Walas::with('guru')->get(),
        );
        return view('admin.kurikulum.walas.index', $data);
    }

    // guru
    public function data_guru()
    {
        $data = array(
            'title' => 'Halaman Daftar Guru',
            'menu_admin_data_guru' => 'active',
            'guru' => Guru::with('user', 'mapel_kelas.mata_pelajaran')->get(),
        );
        return view('admin.kurikulum.guru.index', $data);
    }

    public function create_guru()
    {
        $data = array(
            'title' => 'Halaman Tambah Guru',
            'menu_admin_data_guru' => 'active',
        );
        return view('admin.kurikulum.guru.create', $data);
    }


    public function store_guru(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $guru = Guru::create([
            'nama' => $request->nama,
        ]);

        $username = $this->generateUsernameFromName($request->nama);

        User::create([
            'username' => $username,
            'guru_id' => $guru->id,
            'murid_id' => null,
            'role_id' => 3,
            'password' => null,
        ]);

        return redirect()->route('admin_guru')->with('success', 'Guru berhasil ditambahkan');
    }

    // murid
    public function data_murid()
    {
        $data = array(
            'title' => 'Halaman Daftar Murid',
            'menu_admin_data_murid' => 'active',
            'murid' => Murid::with('user', 'kelas.jurusan')->get(),
        );
        return view('admin.kurikulum.murid.index', $data);
    }

    public function create_murid()
    {
        $data = array(
            'title' => 'Halaman Tambah Murid',
            'menu_admin_data_murid' => 'active',
        );
        return view('admin.kurikulum.murid.create', $data);
    }

    public function store_murid(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $murid = Murid::create([
            'nama' => $request->nama,
            'kelas_id' => 1,
        ]);

        $username = $this->generateUsernameFromName($request->nama);

        User::create([
            'username' => $username,
            'guru_id' => null,
            'murid_id' => $murid->id,
            'role_id' => 4,
            'password' => null,
        ]);

        return redirect()->route('admin_murid')->with('success', 'Murid berhasil ditambahkan');
    }

    private function generateUsernameFromName($namaLengkap)
    {
        $parts = explode(' ', strtolower($namaLengkap));

        $username = '';
        foreach ($parts as $part) {
            $username .= substr($part, 0, 1);
        }

        $username .= substr(end($parts), -2);

        $baseUsername = $username;
        $counter = 1;

        while (User::where('username', $username)->exists()) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        return $username;
    }
}
