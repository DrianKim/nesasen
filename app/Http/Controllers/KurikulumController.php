<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\walas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Illuminate\Support\Facades\Hash;
use Psy\CodeCleaner\FunctionContextPass;

class KurikulumController extends Controller
{

    // walas
    public function data_walas() {
        $data = array(
            'title' => 'Halaman Daftar Wali Kelas',
            'menu_admin_data_walas' => 'active',
            'walas' => Walas::with('guru')->get(),
        );
        return view('admin.kurikulum.walas.index', $data);
    }

    // guru
    public function data_guru() {
        $data = array(
            'title' => 'Halaman Daftar Guru',
            'menu_admin_data_guru' => 'active',
            'guru' => Guru::with('user', 'mapel_kelas.mata_pelajaran')->get(),
        );
        return view('admin.kurikulum.guru.index', $data);
    }

    public function create_guru() {
        $data = array(
            'title' => 'Halaman Tambah Guru',
            'menu_admin_data_guru' => 'active',
        );
        return view('admin.kurikulum.guru.create', $data);
    }


    public function store_guru(Request $request) {
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

        return redirect()->route('admin_guru.index')->with('success', 'Guru berhasil ditambahkan dengan username: '. $username);
    }

    public function edit_guru($id) {
        $data = array(
            'title' => 'Halaman Edit Guru',
            'menu_admin_edit_guru' => 'active',
            'guru' => Guru::with('user')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.kurikulum.guru.edit', $data);
    }

    public function update_guru(Request $request, $id) {
        $guru = Guru::with('user')->findorfail($id);

        $guru->update(array_filter([
            'nama' => $request->nama,
            'nip' => $request->nip,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]));

        $user = $guru->user;

        if ($user) {
            $user->update(array_filter([
                'username' => $request->username,
                'password' => $request->password ? bcrypt($request->password) : null,
            ]));

            return redirect()->route('admin_guru.index')->with('success', 'Data Guru Berhasil Diedit');
        }

        // $user->save();
        // $request->validate([
        //     'username' => 'nullable|min:3',
        //     'password' => 'nullable|min:5',
        // ], [
        //     'username.min' => 'Minimal 3 Karakter',
        //     'password.min' => 'Minimal 5 Karakter',
        // ]);
    }

    public function destroy_guru($id) {
        $guru = Guru::with('user')->findOrFail($id);

        if($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('admin_guru.index')->with('success', 'Data Guru Berhasil Dihapus');
    }

    // murid
    public function data_murid() {
        $data = array(
            'title' => 'Halaman Daftar Murid',
            'menu_admin_data_murid' => 'active',
            'murid' => Murid::with('user', 'kelas.jurusan')->get(),
        );
        return view('admin.kurikulum.murid.index', $data);
    }

    public function create_murid() {
        $data = array(
            'title' => 'Halaman Tambah Murid',
            'menu_admin_data_murid' => 'active',
        );
        return view('admin.kurikulum.murid.create', $data);
    }

    public function store_murid(Request $request) {
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

        return redirect()->route('admin_murid.index')->with('success', 'Murid berhasil ditambahkan dengan username: ' .  $username);
    }

    public function edit_murid($id) {
        $data = array(
            'title' => 'Halaman Edit Murid',
            'menu_admin_edit_murid' => 'active',
            'murid' => Murid::with('user', 'kelas.jurusan')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.kurikulum.murid.edit', $data);
    }

    public function update_murid(Request $request, $id) {
        $murid = Murid::with('user')->findorfail($id);

        $murid->update(array_filter([
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
            'nis' => $request->nis,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]));

        $user = $murid->user;

        if ($user) {
            $user->update(array_filter([
                'username' => $request->username,
                'password' => $request->password ? bcrypt($request->password) : null,
            ]));

            return redirect()->route('admin_murid.index')->with('success', 'Data Murid Berhasil Diedit');
        }

        // $user->save();
        // $request->validate([
        //     'username' => 'nullable|min:3',
        //     'password' => 'nullable|min:5',
        // ], [
        //     'username.min' => 'Minimal 3 Karakter',
        //     'password.min' => 'Minimal 5 Karakter',
        // ]);
    }

    public function destroy_murid($id) {
        $murid = Murid::with('user')->findOrFail($id);

        if($murid->user) {
            $murid->user->delete();
        }

        $murid->delete();

        return redirect()->route('admin_murid.index')->with('success', 'Data Murid Berhasil Dihapus');
    }

    private function generateUsernameFromName($namaLengkap) {
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

    // umum
    public function umum_kelas() {
        $data = array(
            'title' => 'Halaman Daftar Kelas',
            'menu_admin_umum_kelas' => 'active',
            'kelas' => Kelas::get(),
        );
        return view('admin.kurikulum.umum.kelas', $data);
    }

    public function umum_mataPelajaran() {
        $data = array(
            'title' => 'Halaman Daftar Mata Pelajaran',
            'menu_admin_umum_mapel' => 'active',
            'mapel' => MataPelajaran::get(),
        );
        return view('admin.kurikulum.umum.mata_pelajaran', $data);
    }
}
// public function umum_semester()
// {
//     $data = array(
//         'title' => 'Halaman Daftar Semester',
//         'menu_admin_data_guru' => 'active',
//         'guru' => Guru::get(),
//     );
//     return view('admin.kurikulum.guru.index', $data);
// }

// public function umum_tahunAjaran()
// {
//     $data = array(
//         'title' => 'Halaman Daftar Guru',
//         'menu_admin_data_guru' => 'active',
//         'guru' => Guru::with('user', 'mapel_kelas.mata_pelajaran')->get(),
//     );
//     return view('admin.kurikulum.guru.index', $data);
// }
