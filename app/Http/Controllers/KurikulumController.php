<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Murid;
use App\Models\walas;
use App\Models\Jurusan;
use App\Models\MapelKelas;
use Illuminate\Support\Str;
use App\Imports\MuridImport;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Database\Seeders\GuruSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Psy\CodeCleaner\FunctionContextPass;

class KurikulumController extends Controller
{

    // walas
    public function data_walas()
    {
        $data = array(
            'title' => 'Halaman Daftar Wali Kelas',
            'menuPengguna' => 'active',
            // 'menu_admin_data_walas' => 'active',
            'walas' => Walas::with('guru')->get(),
        );
        return view('admin.kurikulum.walas.index', $data);
    }

    public function create_walas()
    {
        $data = array(
            'title' => 'Halaman Tambah Wali Kelas',
            'menuPengguna' => 'active',
            // 'menu_admin_data_walas' => 'active',
            'walas' => Walas::with('guru')->get(),
            'guruList' => Guru::all(),
        );
        return view('admin.kurikulum.walas.create', $data);
    }

    public function store_walas() {}

    // guru
    public function data_guru()
    {
        $data = array(
            'title' => 'Halaman Daftar Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_data_guru' => 'active',
            'guru' => Guru::with('user', 'mapel_kelas.mata_pelajaran')->orderby('nama', 'asc')->get(),
        );
        return view('admin.kurikulum.guru.index', $data);
    }

    public function create_guru()
    {
        $data = array(
            'title' => 'Halaman Tambah Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_data_guru' => 'active',
        );
        return view('admin.kurikulum.guru.create', $data);
    }


    public function store_guru(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong'
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

        return redirect()->route('admin_guru.index')->with('success', 'Guru berhasil ditambahkan dengan username: ' . $username);
    }

    public function edit_guru($id)
    {
        $data = array(
            'title' => 'Halaman Edit Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_data_guru' => 'active',
            'guru' => Guru::with('user')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.kurikulum.guru.edit', $data);
    }

    public function update_guru(Request $request, $id)
    {
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

        if ($request->filled('new_password') && $request->filled('current_password')) {
            if (Hash::check($request->new_password, $user->password)) {
                return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama'])->withInput();
            }
        }

        if ($request->filled('new_password')) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return redirect()->route('admin_guru.index')->with('success', 'Data Guru Berhasil Diedit');

        // $user->save();
        // $request->validate([
        //     'username' => 'nullable|min:3',
        //     'password' => 'nullable|min:5',
        // ], [
        //     'username.min' => 'Minimal 3 Karakter',
        //     'password.min' => 'Minimal 5 Karakter',
        // ]);
    }

    public function destroy_guru($id)
    {
        $guru = Guru::with('user')->findOrFail($id);

        if ($guru->user) {
            $guru->user->delete();
        }

        $guru->delete();

        return redirect()->route('admin_guru.index')->with('success', 'Data Guru Berhasil Dihapus');
    }

    public function import_guru(Request $request)
    {
        $file = $request->file('file');
        $data = Excel::toArray([], $file);
        $rows = array_slice($data[0], 1);
        $imported = 0;

        foreach ($rows as $row) {
            $nama = $row[0];
            if (!empty($nama)) {
                $username = $this->generateUsernameFromName($nama);

                $guru = Guru::create([
                    'nama' => $nama,
                ]);

                User::create([
                    'username' => $username,
                    'password' => null,
                    'guru_id' => $guru->id,
                    'role_id' => 3,
                ]);

                $imported++;
            }
        }

        return back()->with('success', 'Import guru berhasil! Total: $imported guru.');
    }

    // murid
    public function data_murid()
    {
        $data = array(
            'title' => 'Halaman Daftar Murid',
            'menuPengguna' => 'active',
            // 'menu_admin_data_murid' => 'active',
            'murid' => Murid::with('user', 'kelas.jurusan')->orderby('nama', 'asc')->get(),
        );
        return view('admin.kurikulum.murid.index', $data);
    }

    public function create_murid()
    {
        $data = array(
            'title' => 'Halaman Tambah Murid',
            'menuPengguna' => 'active',
            // 'menu_admin_data_murid' => 'active',
            'kelasList' => Kelas::all(),
        );
        return view('admin.kurikulum.murid.create', $data);
    }

    public function store_murid(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required'
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong',
            'kelas_id.required' => 'Pilih Salah Satu Kelas',
        ]);

        $murid = Murid::create([
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
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

    public function edit_murid($id)
    {
        $data = array(
            'title' => 'Halaman Edit Murid',
            'menuPengguna' => 'active',
            // 'menu_admin_data_murid' => 'active',
            'murid' => Murid::with('user', 'kelas.jurusan')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.kurikulum.murid.edit', $data);
    }

    public function update_murid(Request $request, $id)
    {
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

    public function destroy_murid($id)
    {
        $murid = Murid::with('user')->findOrFail($id);

        if ($murid->user) {
            $murid->user->delete();
        }

        $murid->delete();

        return redirect()->route('admin_murid.index')->with('success', 'Data Murid Berhasil Dihapus');
    }

    public function import_murid(Request $request)
    {
        $file = $request->file('file');
        $data = Excel::toArray([], $file);
        $rows = array_slice($data[0], 1);
        $imported = 0;

        // dd($data);
        foreach ($rows as $row) {
            $nama = $row[0];
            if (!empty($nama)) {
                $username = $this->generateUsernameFromName($nama);

                $murid = Murid::create([
                    'nama' => $nama,
                    'kelas_id' => null,
                ]);

                User::create([
                    'username' => $username,
                    'password' => null,
                    'murid_id' => $murid->id,
                    'role_id' => 4,
                ]);

                $imported++;
            }
        }

        return back()->with('success', "Import murid berhasil! Total $imported murid.");
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

    // umum jurusan
    public function umum_jurusan()
    {
        $data = array(
            'title' => 'Halaman Daftar Jurusan',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_jurusan' => 'active',
            'jurusan' => Jurusan::orderby('nama_jurusan', 'asc')->get(),
        );
        return view('admin.kurikulum.umum.jurusan.index', $data);
    }

    public function create_jurusan()
    {
        $data = array(
            'title' => 'Halaman Tambah Jurusan',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_jurusan' => 'active',
        );
        return view('admin.kurikulum.umum.jurusan.create', $data);
    }

    public function store_jurusan(Request $request)
    {
        $request->validate([
            'nama_jurusan' => 'required|string|max:255',
            'kode_jurusan' => 'required|string|max:255',
        ], [
            'nama_jurusan.required' => 'Nama Jurusan Tidak Boleh Kosong',
            'kode_jurusan.required' => 'Kode Jurusan Tidak Boleh Kosong',
        ]);

        Jurusan::create([
            'nama_jurusan' => $request->nama_jurusan,
            'kode_jurusan' => $request->kode_jurusan,
        ]);

        return redirect()->route('admin_umum_jurusan.index')->with('success', 'Jurusan Berhasil Ditambahkan');
    }

    public function edit_jurusan($id)
    {
        $data = array(
            'title' => 'Halaman Edit Jurusan',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_jurusan' => 'active',
            'jurusan' => Jurusan::findOrFail($id),
        );
        return view('admin.kurikulum.umum.jurusan.edit', $data);
    }

    public function update_jurusan(Request $request, $id)
    {
        $jurusan = Jurusan::findorfail($id);

        $request->validate([
            'nama_jurusan' => 'required|string',
            'kode_jurusan' => 'required|string',
        ], [
            'nama_jurusan.required' => 'Nama Jurusan Tidak Boleh Kosong',
            'kode_jurusan.required' => 'Kode Jurusan Tidak Boleh Kosong',
        ]);

        $jurusan->update(array_filter([
            'nama_jurusan' => $request->nama_jurusan,
            'kode_jurusan' => $request->kode_jurusan,
        ]));

        return redirect()->route('admin_umum_jurusan.index')->with('success', 'Jurusan Berhasil Diedit');

        // $user->save();
        // $request->validate([
        //     'username' => 'nullable|min:3',
        //     'password' => 'nullable|min:5',
        // ], [
        //     'username.min' => 'Minimal 3 Karakter',
        //     'password.min' => 'Minimal 5 Karakter',
        // ]);
    }

    public function destroy_jurusan($id)
    {
        $jurusan = Jurusan::findOrFail($id);

        $jurusan->delete();

        return redirect()->route('admin_umum_jurusan.index')->with('success', 'Jurusan Berhasil Dihapus');
    }

    // umum kelas
    public function umum_kelas()
    {
        $data = array(
            'title' => 'Halaman Daftar Kelas',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_kelas' => 'active',
            'kelas' => Kelas::withcount('murid')->with(['jurusan', 'walas.user.guru'])
                ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                ->orderby('tingkat', 'asc')
                ->orderby('jurusan.nama_jurusan', 'asc')
                ->orderby('no_kelas', 'asc')
                ->select('kelas.*')->get(),
        );
        return view('admin.kurikulum.umum.kelas.index', $data);
    }

    public function create_kelas()
    {
        $data = array(
            'title' => 'Halaman Tambah Kelas',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_kelas' => 'active',
            'jurusanList' => Jurusan::all(),
            'guruList' => Guru::all(),
        );
        return view('admin.kurikulum.umum.kelas.create', $data);
    }

    public function store_kelas(Request $request)
    {
        $request->validate([
            'tingkat' => 'required|in:X,XI,XII',
            'jurusan_id' => 'required|exists:jurusan,id',
            'no_kelas' => 'required',
            'guru_id' => 'nullable|exists:guru,id',
        ], [
            'tingkat.required' => 'Tingkat Tidak Boleh Kosong',
            'tingkat.in' => 'Tingkat Tidak Valid',
            'jurusan_id.required' => 'Jurusan Tidak Boleh Kosong',
            'no_kelas' => 'No Kelas Tidak Boleh Kosong',
        ]);

        $kelas = Kelas::create([
            'tingkat' => $request->tingkat,
            'jurusan_id' => $request->jurusan_id,
            'no_kelas' => $request->no_kelas,
        ]);

        if ($request->guru_id) {
            $guru = Guru::findOrFail($request->guru_id);

            $user = User::where('guru_id', $guru->id)->first();

            if ($user && $user->role_id == 3) {
                Walas::create([
                    'user_id' => $user->id,
                    'kelas_id' => $kelas->id,
                ]);

                $user->role_id = 2;
                $user->save();
            }
        }

        return redirect()->route('admin_umum_kelas.index')->with('success', 'Kelas Berhasil Ditambahkan');
    }

    public function edit_kelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $data = array(
            'title' => 'Halaman Edit Kelas',
            'menuKurikulum' => 'active',
            'kelas' => $kelas,
            'jurusanList' => Jurusan::all(),
            'guruList' => User::where('role_id', 3)->with('guru')->get(),
            'selectedWalas' => Walas::where('kelas_id', $id)->value('user_id'),
        );

        return view('admin.kurikulum.umum.kelas.edit', $data);
    }

    public function update_kelas(Request $request, $id)
    {
        $request->validate([
            'tingkat' => 'required|in:X,XI,XII',
            'jurusan_id' => 'required|exists:jurusan,id',
            'no_kelas' => 'required|in:1,2,3,4',
            'guru_id' => 'required|exists:users,id',
        ]);

        // $walasLama = Walas::where('kelas_id', $id)->first();
        // if ($walasLama) {
        //     $userLama = User::find($walasLama->user_id);
        //     if ($userLama) {
        //         $userLama->role_id = 3;
        //         $userLama->save();
        //     }
        // }

        DB::transaction(function () use ($request, $id) {
            $kelas = Kelas::findOrFail($id);

            $kelas->update([
                'tingkat' => $request->tingkat,
                'jurusan_id' => $request->jurusan_id,
                'no_kelas' => $request->no_kelas,
            ]);

            $walasLama = Walas::where('kelas_id', $id)->first();
            if ($walasLama && $walasLama->user_id != $request->guru_id) {
                $userLama = User::find($walasLama->user_id);
                if ($userLama) {
                    $userLama->role_id = 3;
                    $userLama->save();
                }
            }

            Walas::updateOrCreate(
                ['kelas_id' => $id],
                ['user_id' => $request->guru_id]
            );

            $userBaru = User::find($request->guru_id);
            if ($userBaru) {
                $userBaru->role_id = 2;
                $userBaru->save();
            }
        });

        return redirect()->route('admin_umum_kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy_kelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelas->delete();

        return redirect()->route('admin_umum_kelas.index')->with('success', 'Kelas Berhasil Dihapus');
    }

    // umum mapel
    public function umum_mapel()
    {
        $data = array(
            'title' => 'Halaman Daftar Mapel',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_mapel' => 'active',
            'mapel' => MataPelajaran::orderby('nama_mapel', 'asc')->get(),
        );
        return view('admin.kurikulum.umum.mata_pelajaran.index', $data);
    }

    public function create_mapel()
    {
        $data = array(
            'title' => 'Halaman Tambah Mapel',
            'menuKurikulum' => 'active',
            // 'menu_admin_umum_mapel' => 'active',
        );
        return view('admin.kurikulum.umum.mata_pelajaran.create', $data);
    }

    public function store_mapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string',
            'kode_mapel' => 'required|string',
        ], [
            'nama_mapel.required' => 'Nama Mapel Tidak Boleh Kosong',
            'kode_mapel.required' => 'Kode Mapel Tidak Boleh Kosong',
        ]);

        MataPelajaran::create([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => $request->kode_mapel,
        ]);

        return redirect()->route('admin_umum_mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan');
    }

    public function edit_mapel($id)
    {
        $data = array(
            'title' => 'Halaman Daftar Mapel',
            'menuKurikulum' => 'active',
            'mapel' => MataPelajaran::findorfail($id),
            // 'mapel' => $mapel,
            // 'menu_admin_umum_mapel' => 'active',
        );
        return view('admin.kurikulum.umum.mata_pelajaran.edit', $data);
    }

    public function update_mapel(Request $request, $id)
    {
        $mapel = MataPelajaran::findorfail($id);

        $request->validate([
            'nama_mapel' => 'required|string',
            'kode_mapel' => 'required|string',
        ], [
            'nama_mapel.required' => 'Nama Mapel Tidak Boleh Kosong',
            'kode_mapel.required' => 'Kode Mapel Tidak Boleh Kosong',
        ]);

        $mapel->update(array_filter([
            'nama_mapel' => $request->nama_mapel,
            'kode_mapel' => $request->kode_mapel,
        ]));

        return redirect()->route('admin_umum_mapel.index')->with('success', 'Mapel Berhasil Diedit');
    }

    public function destroy_mapel($id)
    {
        $mapel = MataPelajaran::findorfail($id);

        $mapel->delete();

        return redirect()->route('admin_umum_mapel.index')->with('success', 'Mapel Berhasil Dihapus');
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
