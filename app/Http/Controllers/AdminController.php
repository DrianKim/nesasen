<?php

namespace App\Http\Controllers;

use Svg\Tag\Rect;
use App\Models\Guru;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\walas;
use App\Models\Jurusan;
use App\Models\MapelKelas;
use App\Models\absensiGuru;
use Illuminate\Support\Str;
use App\Imports\SiswaImport;
use App\Models\absensiSiswa;
use Illuminate\Http\Request;
use App\Models\MataPelajaran;
use Database\Seeders\GuruSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

use Maatwebsite\Excel\Facades\Excel;
use Psy\CodeCleaner\FunctionContextPass;
use function PHPUnit\Framework\returnSelf;

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
    public function index_kelas(Request $request)
    {
        $kelasId = $request->input('kelas');
        $tahunAjaran = $request->input('tahun_ajaran');
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Kelas::with(['jurusan', 'walas.user.guru', 'siswa']);

        if ($kelasId) {
            $query->where('id', $kelasId);
        }

        if ($tahunAjaran) {
            $query->where('tahun_ajaran', $tahunAjaran);
        }

        if ($search) {
            $searchTerm = strtolower(trim($search));
            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(tingkat) LIKE ?', ["%{$searchTerm}%"])
                    ->orWhereRaw('LOWER(no_kelas) LIKE ?', ["%{$searchTerm}%"])

                    ->orWhereHas('jurusan', function ($q2) use ($searchTerm) {
                        $q2->whereRaw('LOWER(nama_jurusan) LIKE ?', ["%{$searchTerm}%"])
                            ->orWhereRaw('LOWER(kode_jurusan) LIKE ?', ["%{$searchTerm}%"]);
                    })

                    ->orWhereHas('walas.user.guru', function ($q3) use ($searchTerm) {
                        $q3->whereRaw('LOWER(nama) LIKE ?', ["%{$searchTerm}%"]);
                    });
            });
        }


        if ($sortBy) {
            switch ($sortBy) {
                case 'Nama Kelas':
                    $query->orderByRaw("tingkat $sortDirection")
                        ->orderByRaw("no_kelas $sortDirection")
                        ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                        ->orderBy('jurusan.nama_jurusan', $sortDirection);
                    break;

                case 'Wali Kelas':
                    $query->join('walas', 'kelas.id', '=', 'walas.kelas_id')
                        ->join('users', 'walas.user_id', '=', 'users.id')
                        ->join('guru', 'users.id', '=', 'guru.user_id')
                        ->orderBy("guru.nama", $sortDirection)
                        ->select('kelas.*');
                    break;

                case 'Jumlah Siswa':
                    $query->withCount('siswa')->orderBy('siswa_count', $sortDirection);
                    break;

                default:
                    $query->orderBy('tingkat', $sortDirection);
                    break;
            }
        } else {
            $query->orderBy('tingkat', 'asc');
        }

        $kelas = $query->paginate($perPage)->withQueryString();

        $tahunAjaranFilter = Kelas::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran');
        $guruWalasIds = Walas::pluck('user_id');


        $data = [
            'title' => 'Halaman Daftar Kelas',
            'menuAdmin' => 'active',
            'jurusanList' => Jurusan::all(),
            'guruList' => Guru::whereHas('user', function ($query) use ($guruWalasIds) {
                $query->whereNotIn('id', $guruWalasIds);
            })->get(),
            'kelas' => $kelas,
            'tahunAjaranFilter' => $tahunAjaranFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.kelas.partials.table', $data)->render(),
                'pagination' => view('admin.kelas.partials.pagination', ['kelas' => $kelas])->render(),
            ]);
        }

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

        try {
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

            return redirect()->route('admin_kelas.index')->with('success', 'Kelas Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
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

        $walas = Walas::where('kelas_id', $kelas->id)->first();

        if ($walas) {
            $user = User::find($walas->user_id);
            if ($user) {
                $user->role_id = 3;
                $user->save();
            }
            $walas->delete();
        }

        $kelas->delete();

        return redirect()->route('admin_kelas.index')->with('success', 'Kelas Berhasil Dihapus');
    }

    public function bulkAction_kelas(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang Dipilih!');
        }

        foreach ($ids as $id) {
            $kelas = Kelas::find($id)->get();

            if ($kelas) {
                if ($kelas->user) {
                    $kelas->user->delete();
                }

                $kelas->delete();
            }
        }
        return redirect()->back()->with('success', 'Kelas Berhasil Dihapus');
    }

    // umum kelasKu
    public function index_kelasKu(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = MapelKelas::with('kelas.jurusan', 'mata_pelajaran', 'guru');

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('mata_pelajaran', function ($q2) use ($searchTerm) {
                    $q2->whereRaw('LOWER(REPLACE(nama_mapel, " ", "")) LIKE ?', ["%$searchTerm%"])
                        ->orWhereRaw('LOWER(REPLACE(kode_mapel, " ", "")) LIKE ?', ["%$searchTerm%"]);
                })
                    ->orWhereHas('guru', function ($q3) use ($searchTerm) {
                        $q3->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ["%$searchTerm%"]);
                    });
            });
        }

        if ($sortBy) {
            switch ($sortBy) {
                case 'KelasKu':
                    $query->join('mata_pelajaran', 'mapel_kelas.mata_pelajaran_id', '=', 'mata_pelajaran.id')
                        ->orderBy('mata_pelajaran.nama_mapel', $sortDirection)
                        ->select('mapel_kelas.*');
                    break;

                case 'Kelas':
                    $query->join('kelas', 'mapel_kelas.kelas_id', '=', 'kelas.id')
                        ->join('jurusan', 'kelas.jurusan_id', '=', 'jurusan.id')
                        ->orderByRaw("CONCAT(kelas.tingkat, jurusan.kode_jurusan, kelas.no_kelas) $sortDirection")
                        ->select('mapel_kelas.*');
                    break;

                case 'Guru':
                    // FIX: join langsung ke guru dari mapel_kelas.guru_id
                    $query->join('guru', 'mapel_kelas.guru_id', '=', 'guru.id')
                        ->orderBy('guru.nama', $sortDirection)
                        ->select('mapel_kelas.*');
                    break;

                default:
                    $query->orderBy('id', 'asc');
                    break;
            }
        } else {
            $query->orderBy('id', 'asc');
        }

        // dd($sortBy, $columnMap[$sortBy] ?? null);
        //     if (isset($columnMap[$sortBy])) {
        //         $query->orderBy($columnMap[$sortBy], $sortDirection);
        //     } else {
        //         $query->orderBy('nama_mapel', 'asc')->orderBy('kode_mapel', 'asc');
        //     }
        // }

        $kelasKu = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Halaman Kelasku',
            'menuAdmin' => 'active',
            'kelasKu' => $kelasKu,
            'mapelList' => MataPelajaran::all(),
            'guruList' => Guru::all(),
            'kelasList' => Kelas::with('jurusan')->get(),
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.kelasKu.partials.table', $data)->render(),
                'pagination' => view('admin.kelasKu.partials.pagination', ['kelasKu' => $kelasKu])->render(),
            ]);
        }

        return view('admin.kelasKu.index', $data);
    }

    public function store_kelasKu(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'guru_id' => 'required|exists:guru,id',
        ], [
            'kelas_id.required' => 'Kelas Tidak Boleh Kosong',
            'mapel_id.required' => 'Mata Pelajaran Tidak Boleh Kosong',
            'guru_id.required' => 'Guru Tidak Boleh Kosong',
        ]);

        try {
            MapelKelas::create([
                'kelas_id' => $request->kelas_id,
                'mapel_id' => $request->mapel_id,
                'guru_id' => $request->guru_id,
            ]);

            return redirect()->route('admin_kelasKu.index')->with('success', 'KelasKu Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function bulkAction_kelasKu(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang Dipilih!');
        }

        foreach ($ids as $id) {
            $kelas = Kelas::find($id)->get();

            if ($kelas) {
                $walas = $kelas->walas;

                if ($walas && $walas->user) {
                    $walas->user->delete();
                }

                $kelas->delete();
            }
        }
        return redirect()->back()->with('success', 'KelasKu Berhasil Dihapus');
    }

    // siswa
    protected function mapSortColumn($label)
    {
        return match ($label) {
            'NIS' => 'nis',
            'Nama Siswa' => 'nama',
            'No. HP' => 'no_hp',
            'Kelas' => 'kelas_id',
            'default' => 'id',
        };
    }

    public function index_siswa(Request $request)
    {
        $kelasId = $request->input('kelas');
        $tahunAjaran = $request->input('tahun_ajaran');
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Siswa::with(['user.siswa', 'kelas.jurusan']);

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        if ($tahunAjaran) {
            $query->whereHas('kelas', function ($q) use ($tahunAjaran) {
                $q->where('tahun_ajaran', $tahunAjaran);
            });
        }

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->whereHas('user.siswa', function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            })->orWhere(function ($q) use ($searchTerm) {
                $q->orWhere('nis', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%");
            });
        }

        if ($sortBy) {
            $columnMap = [
                // 'NISN' => 'nisn',
                'NIS' => 'nis',
                'Nama Siswa' => 'nama',
                'No. HP' => 'no_hp',
                'Kelas' => 'kelas_id'
            ];

            // dd($sortBy, $columnMap[$sortBy] ?? null);

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('kelas_id', 'asc')->orderBy('nama', 'asc');
            }
        }

        $siswa = $query->paginate($perPage)->withQueryString();

        $kelasFilter = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('jurusan_id')->orderBy('no_kelas')->get();
        $tahunAjaranFilter = Kelas::select('tahun_ajaran')->distinct()->pluck('tahun_ajaran');

        $data = [
            'title' => 'Halaman Data Siswa',
            'kelasList' => Kelas::all(),
            'siswa' => $siswa,
            'kelasFilter' => $kelasFilter,
            'tahunAjaranFilter' => $tahunAjaranFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.siswa.partials.table', $data)->render(),
                'pagination' => view('admin.siswa.partials.pagination', ['siswa' => $siswa])->render(),
            ]);
        }

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
        // dd($request->all());
        $validated = $request->validate([
            'nisn' => 'required|numeric|digits_between:8,10|unique:siswa,nisn',
            'nis' => 'nullable|numeric|digits_between:4,10|unique:siswa,nis',
            'nama' => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'tanggal_lahir' => 'required|date',
            'kelas_id' => 'required',
            'no_hp' => 'required|digits_between:10,15',
            'email' => 'required|email|max:255',
        ], [
            'nisn.required' => 'NISN Tidak Boleh Kosong',
            'nisn.numeric' => 'NISN Harus Angka',
            'nisn.digits_between' => 'NISN Harus Antara 8-10 Digit',
            'nisn.unique' => 'NISN Sudah Digunakan',

            'nis.numeric' => 'NIS Harus Angka',
            'nis.digits_between' => 'NIS Harus Antara 4-10 Digit',
            'nis.unique' => 'NIS Sudah Digunakan',

            'nama.required' => 'Nama Tidak Boleh Kosong',

            'username.required' => 'Username Tidak Boleh Kosong',
            'username.unique' => 'Username Sudah Digunakan',

            'tanggal_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'tanggal_lahir.date' => 'Tanggal Lahir Tidak Valid',

            'kelas_id.required' => 'Kelas Tidak Boleh Kosong',

            'no_hp.required' => 'No HP Tidak Boleh Kosong',
            'no_hp.digits_between' => 'No HP Harus Antara 10-15 Digit',

            'email.required' => 'Email Tidak Boleh Kosong',
            'email.email' => 'Email Tidak Valid',
        ]);

        try {
            // Simpan data siswa
            $siswa = Siswa::create([
                'nisn' => $request->nisn,
                'nis' => $request->nis,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'kelas_id' => $request->kelas_id,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
            ]);

            // Buat akun user untuk siswa
            $password = date('dmY', strtotime($request->tanggal_lahir)); // Password default adalah tanggal lahir

            User::create([
                'username' => $request->username,
                'guru_id' => null,
                'siswa_id' => $siswa->id,
                'role_id' => 4,
                'password' => Hash::make($password),
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('admin_siswa.index')->with('success', 'Siswa berhasil ditambahkan!');
        } catch (\Exception $e) {
            // Jika ada error, kembali ke form dengan error message
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
    }

    public function inline_update_siswa(Request $request, $id)
    {
        try {
            $request->validate([
                'nisn' => 'required|string',
                'nis' => 'required|string',
                'nama' => 'required|string',
                'no_hp' => 'required|string',
            ]);

            $siswa = Siswa::findOrFail($id);

            // Only update the fields we expect to be editable
            $allowedFields = ['nisn', 'nis', 'nama', 'no_hp'];

            foreach ($allowedFields as $field) {
                if ($request->has($field)) {
                    $siswa->$field = $request->$field;
                }
            }

            $siswa->save();

            return response()->json(['success' => true, 'message' => 'Data berhasil diperbarui']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
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

    public function bulkAction_siswa(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang Dipilih!');
        }

        foreach ($ids as $id) {
            $siswa = Siswa::with('user')->find($id);

            if ($siswa) {
                if ($siswa->user) {
                    $siswa->user->delete();
                }

                $siswa->delete();
            }
        }
        return redirect()->back()->with('success', 'Siswa Berhasil Dihapus');
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
    public function index_guru(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Guru::with('user');

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->whereHas('user.guru', function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            })->orWhere(function ($q) use ($searchTerm) {
                $q->orWhere('nip', 'like', "%{$searchTerm}%")
                    ->orWhere('no_hp', 'like', "%{$searchTerm}%");
            });
        }

        if ($sortBy) {
            $columnMap = [
                'NIP' => 'nip',
                'Nama Guru' => 'nama',
                'No. HP' => 'no_hp',
            ];

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('nama', 'asc');
            }
        }


        $guru = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Halaman Data Guru',
            'guru' => $guru,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.guru.partials.table', $data)->render(),
                'pagination' => view('admin.guru.partials.pagination', ['guru' => $guru])->render(),
            ]);
        }

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
        // dd($request->all());
        $validated = $request->validate([
            'nip' => 'required|string|unique:guru,nip',
            'nama' => 'required|string|max:255',
            'username' => 'required|unique:users,username',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string|digits',
            'email' => 'required|email|max:255',
        ], [
            'nip.required' => 'NIP Tidak Boleh Kosong',
            'nip.unique' => 'NIP Sudah Digunakan',

            'nama.required' => 'Nama Tidak Boleh Kosong',

            'username.required' => 'Username Tidak Boleh Kosong',
            'username.unique' => 'Username Sudah Digunakan',

            'tanggal_lahir.required' => 'Tanggal Lahir Tidak Boleh Kosong',
            'tanggal_lahir.date' => 'Tanggal Lahir Tidak Valid',

            'no_hp.required' => 'No HP Tidak Boleh Kosong',
            'no_hp.digits_between' => 'No HP Harus Antara 10-15 Digit',

            'email.required' => 'Email Tidak Boleh Kosong',
            'email.email' => 'Email Tidak Valid',
        ]);

        try {
            $guru = Guru::create([
                'nip' => $request->nip,
                'nama' => $request->nama,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
            ]);

            $password = date('dmY', strtotime($request->tanggal_lahir));

            User::create([
                'username' => $request->username,
                'guru_id' => $guru->id,
                'siswa_id' => null,
                'role_id' => 3,
                'password' => Hash::make($password),
            ]);

            return redirect()->route('admin_guru.index')->with('success', 'Guru berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
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

    public function bulkAction_guru(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang Dipilih!');
        }

        foreach ($ids as $id) {
            $guru = Guru::with('user')->find($id);

            if ($guru) {
                if ($guru->user) {
                    $guru->user->delete();
                }

                $guru->delete();
            }
        }
        return redirect()->back()->with('success', 'Guru Berhasil Dihapus');
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

        return back()->with('success', "Import guru berhasil! Total: $imported guru.");
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
    public function index_jurusan(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = Jurusan::query();

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama_jurusan, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(REPLACE(kode_jurusan, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            });
        }

        if ($sortBy) {
            $columnMap = [
                // 'NISN' => 'nisn',
                'Nama Jurusan' => 'nama_jurusan',
                'Kode Jurusan' => 'kode_jurusan',
            ];

            // dd($sortBy, $columnMap[$sortBy] ?? null);

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('nama_jurusan', 'asc')->orderBy('kode_jurusan', 'asc');
            }
        }

        $jurusan = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Halaman Daftar Jurusan',
            // 'menuAdmin' => 'active',
            'jurusan' => $jurusan,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.jurusan.partials.table', $data)->render(),
                'pagination' => view('admin.jurusan.partials.pagination', ['jurusan' => $jurusan])->render(),
            ]);
        }

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
        // dd($request->all());
        $request->validate([
            'nama_jurusan' => 'required|string|max:255|unique:jurusan,nama_jurusan',
            'kode_jurusan' => 'required|string|max:255|unique:jurusan,kode_jurusan',
        ], [
            'nama_jurusan.required' => 'Nama Jurusan Tidak Boleh Kosong',
            'nama_jurusan.unique' => 'Nama Jurusan Sudah Ada',

            'kode_jurusan.required' => 'Kode Jurusan Tidak Boleh Kosong',
            'kode_jurusan.unique' => 'Kode Jurusan Sudah Ada',
        ]);

        try {
            Jurusan::create([
                'nama_jurusan' => $request->nama_jurusan,
                'kode_jurusan' => $request->kode_jurusan,
            ]);

            return redirect()->route('admin_jurusan.index')->with('success', 'Jurusan Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
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

        return redirect()->route('admin_jurusan.index')->with('success', 'Jurusan Berhasil Diedit');

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

        return redirect()->route('admin_jurusan.index')->with('success', 'Jurusan Berhasil Dihapus');
    }

    public function bulkAction_jurusan(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang Dipilih!');
        }

        foreach ($ids as $id) {
            $jurusan = Jurusan::find($id);

            $jurusan->delete();
        }
        return redirect()->back()->with('success', 'Jurusan Berhasil Dihapus');
    }

    // umum mapel
    public function index_mapel(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = MataPelajaran::query();

        if ($search) {
            $searchTerm = strtolower(trim($search));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw('LOWER(REPLACE(nama_mapel, " ", "")) LIKE ?', ['%' . $searchTerm . '%'])
                    ->orWhereRaw('LOWER(REPLACE(kode_mapel, " ", "")) LIKE ?', ['%' . $searchTerm . '%']);
            });
        }

        if ($sortBy) {
            $columnMap = [
                'Nama Mapel' => 'nama_mapel',
                'Kode Mapel' => 'kode_mapel',
            ];

            // dd($sortBy, $columnMap[$sortBy] ?? null);

            if (isset($columnMap[$sortBy])) {
                $query->orderBy($columnMap[$sortBy], $sortDirection);
            } else {
                $query->orderBy('nama_mapel', 'asc')->orderBy('kode_mapel', 'asc');
            }
        }

        $mapel = $query->paginate($perPage)->withQueryString();

        $data = [
            'title' => 'Halaman Daftar Mapel',
            'menuAdmin' => 'active',
            // 'menu_admin_index_mapel' => 'active',
            'mapel' => $mapel,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.mapel.partials.table', $data)->render(),
                'pagination' => view('admin.mapel.partials.pagination', ['mapel' => $mapel])->render(),
            ]);
        }

        return view('admin.mapel.index', $data);
    }

    public function create_mapel()
    {
        $data = array(
            'title' => 'Halaman Tambah Mapel',
            'menuAdmin' => 'active',
            // 'menu_admin_index_mapel' => 'active',
        );
        return view('admin.mapel.create', $data);
    }

    public function store_mapel(Request $request)
    {
        $request->validate([
            'nama_mapel' => 'required|string|unique:mata_pelajaran,nama_mapel',
            'kode_mapel' => 'required|string|unique:mata_pelajaran,kode_mapel',
        ], [
            'nama_mapel.required' => 'Nama Mapel Tidak Boleh Kosong',
            'nama_mapel.unique' => 'Nama Mapel Sudah Ada',

            'kode_mapel.required' => 'Kode Mapel Tidak Boleh Kosong',
            'kode_mapel.unique' => 'Kode Mapel Sudah Ada',
        ]);

        try {
            MataPelajaran::create([
                'nama_mapel' => $request->nama_mapel,
                'kode_mapel' => $request->kode_mapel,
            ]);

            return redirect()->route('admin_mapel.index')->with('success', 'Mata Pelajaran berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['general' => 'Terjadi kesalahan, coba lagi.'])->withInput();
        }
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
        return view('admin.mapel.edit', $data);
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

        return redirect()->route('admin_mapel.index')->with('success', 'Mapel Berhasil Diedit');
    }

    public function destroy_mapel($id)
    {
        $mapel = MataPelajaran::findorfail($id);

        $mapel->delete();

        return redirect()->route('admin_mapel.index')->with('success', 'Mapel Berhasil Dihapus');
    }

    public function bulkAction_mapel(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids) {
            return redirect()->back()->with('error', 'Tidak Ada Data Yang Dipilih!');
        }

        foreach ($ids as $id) {
            $mapel = MataPelajaran::find($id);

            $mapel->delete();
        }
        return redirect()->back()->with('success', 'Mapel Berhasil Dihapus');
    }

    // presensi siswa
    public function index_presensi_siswa(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $filterBy = $request->input('filter_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = absensiSiswa::with('siswa.kelas.jurusan');

        // Filter
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->input('kelas'));
            });
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('filter_by')) {
            $query->where($filterBy, '>', 0);
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'Absen Masuk' => 'absen_masuk',
            'Absen Pulang' => 'absen_pulang',
            'Hadir' => 'hadir',
            'Izin' => 'izin',
            'Sakit' => 'sakit',
        ];

        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $presensi_raw = $query->paginate($perPage)->withQueryString();

        // Map data
        $presensi_siswa = $presensi_raw->getCollection()->transform(function ($item) {
            $kelas = optional($item->siswa->kelas);
            $jurusan = optional($kelas->jurusan);

            return [
                'id' => $item->id,
                'kelas' => $kelas ? $kelas->tingkat . ' ' . $jurusan->kode_jurusan . ' ' . $kelas->no_kelas : '-',
                'masuk' => $item->absen_masuk,
                'pulang' => $item->absen_pulang,
                'hadir' => $item->hadir,
                'izin' => $item->izin,
                'sakit' => $item->sakit,
            ];
        });

        $presensi_siswa = new \Illuminate\Pagination\LengthAwarePaginator(
            $presensi_siswa,
            $presensi_raw->total(),
            $presensi_raw->perPage(),
            $presensi_raw->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $kelasFilter = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('jurusan_id')->orderBy('no_kelas')->get();
        $tanggalFilter = absensiSiswa::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Presensi Siswa',
            'presensi_siswa' => $presensi_siswa,
            'kelasFilter' => $kelasFilter,
            'tanggalFilter' => $tanggalFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.presensi.siswa.partials.table', $data)->render(),
                'pagination' => view('admin.presensi.siswa.partials.pagination', ['presensi_siswa' => $presensi_siswa])->render(),
            ]);
        }

        return view('admin.presensi.siswa.index', $data);
    }

    // presensi guru
    public function index_presensi_guru(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = absensiGuru::with('guru');

        // Filter
        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->input('tanggal'));
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'tanggal' => 'tanggal',
            'Absen Masuk' => 'absen_masuk',
            'Absen Pulang' => 'absen_pulang',
            'Hadir' => 'hadir',
            'Izin' => 'izin',
            'Sakit' => 'sakit',
        ];

        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $presensi_raw = $query->paginate($perPage)->withQueryString();

        // Map data
        $presensi_guru = $presensi_raw->getCollection()->transform(function ($item) {

            return [
                'id' => $item->id,
                'masuk' => $item->absen_masuk,
                'pulang' => $item->absen_pulang,
                'hadir' => $item->hadir,
                'izin' => $item->izin,
                'sakit' => $item->sakit,
            ];
        });

        $presensi_guru = new \Illuminate\Pagination\LengthAwarePaginator(
            $presensi_guru,
            $presensi_raw->total(),
            $presensi_raw->perPage(),
            $presensi_raw->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $tanggalFilter = absensiGuru::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Presensi Siswa',
            'presensi_guru' => $presensi_guru,
            'tanggalFilter' => $tanggalFilter,
        ];

        if ($request->ajax()) {
            return response()->json([
                'table' => view('admin.presensi.guru.partials.table', $data)->render(),
                'pagination' => view('admin.presensi.guru.partials.pagination', ['presensi_guru' => $presensi_guru])->render(),
            ]);
        }

        return view('admin.presensi.guru.index', $data);
    }

    // presensi per mapel
    public function index_presensi_per_mapel(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $sortBy = $request->input('sort_by');
        $filterBy = $request->input('filter_by');
        $sortDirection = $request->input('sort_direction', 'asc');

        $query = absensiSiswa::with('siswa.kelas.jurusan');

        // Filter
        if ($request->filled('kelas')) {
            $query->whereHas('siswa', function ($q) use ($request) {
                $q->where('kelas_id', $request->input('kelas'));
            });
        }

        if ($request->filled('tanggal')) {
            $query->where('tanggal', $request->input('tanggal'));
        }

        if ($request->filled('filter_by')) {
            $query->where($filterBy, '>', 0);
        }

        // Sorting manual tanpa join dulu
        $columnMap = [
            'Absen Masuk' => 'absen_masuk',
            'Absen Pulang' => 'absen_pulang',
            'Hadir' => 'hadir',
            'Izin' => 'izin',
            'Sakit' => 'sakit',
        ];

        if (isset($columnMap[$sortBy])) {
            $query->orderBy($columnMap[$sortBy], $sortDirection);
        } else {
            $query->orderBy('tanggal', 'desc');
        }

        $presensi_raw = $query->paginate($perPage)->withQueryString();

        // Map data
        $presensi_siswa = $presensi_raw->getCollection()->transform(function ($item) {
            $kelas = optional($item->siswa->kelas);
            $jurusan = optional($kelas->jurusan);

            return [
                'id' => $item->id,
                'kelas' => $kelas ? $kelas->tingkat . ' ' . $jurusan->kode_jurusan . ' ' . $kelas->no_kelas : '-',
                'masuk' => $item->absen_masuk,
                'pulang' => $item->absen_pulang,
                'hadir' => $item->hadir,
                'izin' => $item->izin,
                'sakit' => $item->sakit,
            ];
        });

        $presensi_siswa = new \Illuminate\Pagination\LengthAwarePaginator(
            $presensi_siswa,
            $presensi_raw->total(),
            $presensi_raw->perPage(),
            $presensi_raw->currentPage(),
            ['path' => request()->url(), 'query' => request()->query()]
        );

        $kelasFilter = Kelas::with('jurusan')->orderBy('tingkat')->orderBy('jurusan_id')->orderBy('no_kelas')->get();
        $tanggalFilter = absensiSiswa::select('tanggal')->distinct()->orderBy('tanggal', 'desc')->pluck('tanggal');

        $data = [
            'title' => 'Presensi Per-Mapel',

        ];
        return view('admin.presensi.per_mapel.index', $data);
    }

    // izin siswa
    public function index_izin_siswa()
    {
        $data = array(
            'title' => 'Izin Siswa',
        );
        return view('admin.izin.siswa.index', $data);
    }

    // izin guru
    public function index_izin_guru()
    {
        $data = array(
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
//         'guru' => Guru::with('user', 'mapel_kelas.mapel')->get(),
//     );
//     return view('admin.guru.index', $data);
// }
