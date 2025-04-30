<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\walas;
use App\Models\Jurusan;
use App\Models\MapelKelas;
use Illuminate\Support\Str;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Database\Seeders\GuruSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Psy\CodeCleaner\FunctionContextPass;

class AdminController extends Controller
{

    // walas
    // public function index_walas()
    // {
    //     $data = array(
    //         'title' => 'Halaman Daftar Wali Kelas',
    //         'menuPengguna' => 'active',
    //         // 'menu_admin_index_walas' => 'active',
    //         'walas' => Walas::with('guru')->get(),
    //     );
    //     return view('admin.walas.index', $data);
    // }

    // public function create_walas()
    // {
    //     $data = array(
    //         'title' => 'Halaman Tambah Wali Kelas',
    //         'menuPengguna' => 'active',
    //         // 'menu_admin_index_walas' => 'active',
    //         'walas' => Walas::with('guru')->get(),
    //         'guruList' => Guru::all(),
    //     );
    //     return view('admin.walas.create', $data);
    // }

    // public function store_walas() {}

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

    // umum kelas
    public function index_kelas()
    {
        $data = array(
            'title' => 'Halaman Daftar Kelas',
            'menuAdmin' => 'active',
            // 'menu_admin_index_kelas' => 'active',
            'kelas' => Kelas::withcount('siswa')->with(['jurusan', 'walas.user.guru'])
                ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                ->orderby('tingkat', 'asc')
                ->orderby('jurusan.nama_jurusan', 'asc')
                ->orderby('no_kelas', 'asc')
                ->select('kelas.*')->get(),
        );
        return view('admin.kelas.index', $data);
    }

    public function create_kelas()
    {
        $data = array(
            'title' => 'Halaman Tambah Kelas',
            'menuAdmin' => 'active',
            // 'menu_admin_index_kelas' => 'active',
            'jurusanList' => Jurusan::all(),
            'guruList' => Guru::all(),
        );
        return view('admin.kelas.create', $data);
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

        return redirect()->route('admin_index_kelas.index')->with('success', 'Kelas Berhasil Ditambahkan');
    }

    public function edit_kelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $data = array(
            'title' => 'Halaman Edit Kelas',
            'menuAdmin' => 'active',
            'kelas' => $kelas,
            'jurusanList' => Jurusan::all(),
            'guruList' => User::where('role_id', 3)->with('guru')->get(),
            'selectedWalas' => Walas::where('kelas_id', $id)->value('user_id'),
        );

        return view('admin.kelas.edit', $data);
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

        return redirect()->route('admin_index_kelas.index')->with('success', 'Kelas berhasil diperbarui!');
    }

    public function destroy_kelas($id)
    {
        $kelas = Kelas::findOrFail($id);

        $kelas->delete();

        return redirect()->route('admin_index_kelas.index')->with('success', 'Kelas Berhasil Dihapus');
    }

    // umum kelasKu
    public function index_kelasKu()
    {
        $data = array(
            'title' => 'Halaman Daftar Kelas',
            'menuAdmin' => 'active',
            // 'menu_admin_index_kelas' => 'active',
            'kelasKu' => MapelKelas::with([
                'kelas.jurusan',
                'mata_pelajaran',
                'guru',
            ])->get(),
        );
        return view('admin.kelasKu.index', $data);
    }

    // siswa
    public function index_siswa()
    {
        $data = array(
            'title' => 'Halaman Daftar Siswa',
            'menuPengguna' => 'active',
            // 'menu_admin_index_siswa' => 'active',
            'siswa' => Siswa::with('user', 'kelas.jurusan')->orderby('nama', 'asc')->get(),
        );
        return view('admin.siswa.index', $data);
    }

    public function create_siswa()
    {
        $data = array(
            'title' => 'Halaman Tambah Siswa',
            'menuPengguna' => 'active',
            // 'menu_admin_index_siswa' => 'active',
            'kelasList' => Kelas::all(),
        );
        return view('admin.siswa.create', $data);
    }

    public function store_siswa(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kelas_id' => 'required'
        ], [
            'nama.required' => 'Nama Tidak Boleh Kosong',
            'kelas_id.required' => 'Pilih Salah Satu Kelas',
        ]);

        $siswa = Siswa::create([
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
        ]);

        $username = $this->generateUsernameFromName($request->nama);

        User::create([
            'username' => $username,
            'guru_id' => null,
            'siswa_id' => $siswa->id,
            'role_id' => 4,
            'password' => null,
        ]);

        return redirect()->route('admin_siswa.index')->with('success', 'Siswa berhasil ditambahkan dengan username: ' .  $username);
    }

    public function edit_siswa($id)
    {
        $data = array(
            'title' => 'Halaman Edit Siswa',
            'menuPengguna' => 'active',
            // 'menu_admin_index_siswa' => 'active',
            'siswa' => Siswa::with('user', 'kelas.jurusan')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.siswa.edit', $data);
    }

    public function update_siswa(Request $request, $id)
    {
        $siswa = Siswa::with('user')->findorfail($id);

        $siswa->update(array_filter([
            'nama' => $request->nama,
            'kelas_id' => $request->kelas_id,
            'nis' => $request->nis,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
        ]));

        $user = $siswa->user;

        if ($user) {
            $user->update(array_filter([
                'username' => $request->username,
                'password' => $request->password ? bcrypt($request->password) : null,
            ]));

            return redirect()->route('admin_siswa.index')->with('success', 'Data Siswa Berhasil Diedit');
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

    public function destroy_siswa($id)
    {
        $siswa = Siswa::with('user')->findOrFail($id);

        if ($siswa->user) {
            $siswa->user->delete();
        }

        $siswa->delete();

        return redirect()->route('admin_siswa.index')->with('success', 'Data Siswa Berhasil Dihapus');
    }

    public function import_siswa(Request $request)
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

                $siswa = Siswa::create([
                    'nama' => $nama,
                    'kelas_id' => null,
                ]);

                User::create([
                    'username' => $username,
                    'password' => null,
                    'siswa_id' => $siswa->id,
                    'role_id' => 4,
                ]);

                $imported++;
            }
        }

        return back()->with('success', "Import siswa berhasil! Total $imported siswa.");
    }

    // guru
    public function index_guru()
    {
        $data = array(
            'title' => 'Halaman Daftar Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_index_guru' => 'active',
            'guru' => Guru::with('user', 'mapel_kelas.mata_pelajaran')->orderby('nama', 'asc')->get(),
        );
        return view('admin.guru.index', $data);
    }

    public function create_guru()
    {
        $data = array(
            'title' => 'Halaman Tambah Guru',
            'menuPengguna' => 'active',
            // 'menu_admin_index_guru' => 'active',
        );
        return view('admin.guru.create', $data);
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
            'siswa_id' => null,
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
            // 'menu_admin_index_guru' => 'active',
            'guru' => Guru::with('user')->findOrFail($id),
            'kelasList' => Kelas::all(),
        );
        return view('admin.guru.edit', $data);
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

    // umum jurusan
    public function index_jadwal_pelajaran()
    {
        $data = array(
            'title' => 'Halaman Jadwal Pelajaran',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
            // 'jurusan' => Jurusan::orderby('nama_jurusan', 'asc')->get(),
        );
        return view('admin.jadwal_pelajaran.index', $data);
    }

    // umum jurusan
    public function index_jurusan()
    {
        $data = array(
            'title' => 'Halaman Daftar Jurusan',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
            'jurusan' => Jurusan::orderby('nama_jurusan', 'asc')->get(),
        );
        return view('admin.jurusan.index', $data);
    }

    public function create_jurusan()
    {
        $data = array(
            'title' => 'Halaman Tambah Jurusan',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
        );
        return view('admin.jurusan.create', $data);
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

        return redirect()->route('admin_index_jurusan.index')->with('success', 'Jurusan Berhasil Ditambahkan');
    }

    public function edit_jurusan($id)
    {
        $data = array(
            'title' => 'Halaman Edit Jurusan',
            'menuAdmin' => 'active',
            // 'menu_admin_index_jurusan' => 'active',
            'jurusan' => Jurusan::findOrFail($id),
        );
        return view('admin.jurusan.edit', $data);
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

        return redirect()->route('admin_index_jurusan.index')->with('success', 'Jurusan Berhasil Diedit');

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

        return redirect()->route('admin_index_jurusan.index')->with('success', 'Jurusan Berhasil Dihapus');
    }

    // umum mapel
    public function index_mapel()
    {
        $data = array(
            'title' => 'Halaman Daftar Mapel',
            'menuAdmin' => 'active',
            // 'menu_admin_index_mapel' => 'active',
            'mapel' => MataPelajaran::orderby('nama_mapel', 'asc')->get(),
        );
        return view('admin.mata_pelajaran.index', $data);
    }

    public function create_mapel()
    {
        $data = array(
            'title' => 'Halaman Tambah Mapel',
            'menuAdmin' => 'active',
            // 'menu_admin_index_mapel' => 'active',
        );
        return view('admin.mata_pelajaran.create', $data);
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

        return redirect()->route('admin_index_mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan');
    }

    public function edit_mapel($id)
    {
        $data = array(
            'title' => 'Halaman Daftar Mapel',
            'menuAdmin' => 'active',
            'mapel' => MataPelajaran::findorfail($id),
            // 'mapel' => $mapel,
            // 'menu_admin_index_mapel' => 'active',
        );
        return view('admin.mata_pelajaran.edit', $data);
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

        return redirect()->route('admin_index_mapel.index')->with('success', 'Mapel Berhasil Diedit');
    }

    public function destroy_mapel($id)
    {
        $mapel = MataPelajaran::findorfail($id);

        $mapel->delete();

        return redirect()->route('admin_index_mapel.index')->with('success', 'Mapel Berhasil Dihapus');
    }

    // presensi siswa
    public function index_presensi_siswa()
    {
        $data = array (
            'title' => 'Presensi Siswa',
        );
        return view('admin.presensi.siswa.index', $data);
    }

    // presensi siswa
    public function index_presensi_guru()
    {
        $data = array (
            'title' => 'Presensi Guru',
        );
        return view('admin.presensi.guru.index', $data);
    }

    // presensi siswa
    public function index_presensi_per_mapel()
    {
        $data = array (
            'title' => 'Presensi Per-Mapel',
        );
        return view('admin.presensi.per_mapel.index', $data);
    }

    // izin siswa
    public function index_izin_siswa()
    {
        $data = array (
            'title' => 'Izin Siswa',
        );
        return view('admin.izin.siswa.index', $data);
    }

    // izin guru
    public function index_izin_guru()
    {
        $data = array (
            'title' => 'Izin Guru',
        );
        return view('admin.izin.guru.index', $data);
    }
}
// public function index_semester()
// {
//     $data = array(
//         'title' => 'Halaman Daftar Semester',
//         'menu_admin_index_guru' => 'active',
//         'guru' => Guru::get(),
//     );
//     return view('admin.guru.index', $data);
// }

// public function index_tahunAjaran()
// {
//     $data = array(
//         'title' => 'Halaman Daftar Guru',
//         'menu_admin_index_guru' => 'active',
//         'guru' => Guru::with('user', 'mapel_kelas.mata_pelajaran')->get(),
//     );
//     return view('admin.guru.index', $data);
// }
