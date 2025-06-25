<?php

namespace App\Http\Controllers;

use Svg\Tag\Rect;
use Carbon\Carbon;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\izinSiswa;
use App\Models\MapelKelas;
use App\Models\Pengumuman;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\presensiSiswa;
use App\Http\Middleware\isLogin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SiswaController extends Controller
{
    public function beranda_index()
    {
        $data = [
            'title' => 'Beranda Siswa',
            'menuBeranda' => 'active',
            'siswa' => Siswa::all(),
        ];

        $today = Carbon::now();

        $pengumumen = Pengumuman::where(function ($query) {
            $query->where('ditujukan_untuk', 'siswa')->orWhere('ditujukan_untuk', 'semua');
        })
            ->whereDate('kadaluarsa_sampai', '>=', $today)
            ->orderByDesc('tanggal')
            ->take(3)
            ->get();

        $data['pengumumen'] = $pengumumen;
        return view('siswa.beranda', $data);
    }

    public function profil_index()
    {
        $user = auth()->user();
        $siswa = $user->siswa;

        $hadir = PresensiSiswa::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->whereIn('status_kehadiran', ['hadir', 'terlambat'])
            ->count();

        $izin = IzinSiswa::where('siswa_id', $siswa->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->whereIn('jenis_izin', ['Izin', 'Keperluan Keluarga', 'Keperluan Sekolah', 'Sakit'])
            ->count();

        $daftarKelas = Kelas::with('siswa.jurusan')->orderBy('tingkat', 'asc')->orderBy('jurusan_id', 'asc')->orderBy('no_kelas', 'asc')->get();

        $data = [
            'title' => 'Profil Siswa',
            'menuProfil' => 'active',
            'user' => $user,
            'siswa' => $siswa,
            'hadir' => $hadir,
            'izin' => $izin,
            'daftarKelas' => $daftarKelas,
        ];

        return view('siswa.profil', $data);
    }

    public function siswa_pilih_kelas(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        $siswa = auth()->user()->siswa;

        $siswa->kelas_id = $request->kelas_id;
        $siswa->save();

        return redirect()->route('siswa.profil')->with('success', 'Kelas berhasil dipilih!');
    }

    public function profil_update(Request $request)
    {
        try {
            $user = auth()->user();
            $siswa = $user->siswa;

            $request->validate(
                [
                    'nama' => 'required|string|max:255',
                    'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                    'nis' => 'nullable|string|max:20',
                    'nisn' => 'nullable|string|max:20',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'tanggal_lahir' => 'nullable|date',
                    'no_hp' => 'nullable|string|max:20',
                    'email' => 'required|email|max:255',
                    'alamat' => 'nullable|string|max:255',
                    'new_password' => 'nullable|min:5|same:password_confirmation',
                    'password_confirmation' => 'nullable|required_with:new_password|same:new_password',
                ],
                [
                    'nama.required' => 'Nama tidak boleh kosong.',
                    'username.required' => 'Username tidak boleh kosong.',
                    'username.unique' => 'Username sudah digunakan.',
                    'email.required' => 'Email harus diisi.',
                    'email.email' => 'Format email tidak valid.',
                    'jenis_kelamin.required' => 'Jenis kelamin harus dipilih.',
                    'jenis_kelamin.in' => 'Jenis kelamin harus L atau P.',
                    'new_password.min' => 'Password minimal 6 karakter.',
                    'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
                ],
            );

            $siswa->update([
                'nama' => $request->nama,
                'nis' => $request->nis,
                'nisn' => $request->nisn,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'alamat' => $request->alamat,
            ]);

            if ($user) {
                $userData = [
                    'username' => $request->username,
                ];

                if ($request->filled('new_password')) {
                    $userData['password'] = Hash::make($request->new_password);
                }

                $user->update($userData);
            }
        } catch (\Throwable $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }

        return redirect()->route('siswa.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function presensi_index(Request $request)
    {
        $data = [
            'title' => 'Presensi Siswa',
            'menuPresensi' => 'active',
            'siswa' => Siswa::all(),
            'presensiSiswa' => presensiSiswa::where('siswa_id', Auth::user()->id)->get(),
        ];
        return view('siswa.presensi', $data);
    }

    public function presensi_hari_ini()
    {
        $siswaId = auth()->user()->siswa->id;
        $tanggal = now()->toDateString();

        $presensi = presensiSiswa::where('siswa_id', $siswaId)->where('tanggal', $tanggal)->first();

        return response()->json([
            'jam_masuk' => optional($presensi)->jam_masuk,
            'jam_keluar' => optional($presensi)->jam_keluar,
        ]);
    }

    public function presensi_reminder()
    {
        $user = auth()->user();

        if ($user->role_id != 4 || !$user->siswa) {
            return response()->json(['error', 'Akses Ditolak'], 403);
        }

        $siswaId = $user->siswa->id;
        $tanggal = now()->toDateString();

        $presensi = presensiSiswa::where('siswa_id', $siswaId)->where('tanggal', $tanggal)->first();

        return response()->json([
            'jam_masuk' => optional($presensi)->jam_masuk,
        ]);
    }

    public function presensi_store(Request $request)
    {
        $request->validate(
            [
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'selfie' => 'nullable|image|file|max:2048',
                'status_kehadiran' => 'required|in:hadir,terlambat,alfa',
                'status_lokasi' => 'required|in:dalam_area,di_luar_area',
            ],
            [
                'latitude.required' => 'Latitude tidak ada',
                'longitude.required' => 'Longitude tidak ada',
                'selfie.image' => 'File selfie harus berupa gambar',
                'selfie.file' => 'File selfie harus berupa file',
                'selfie.max' => 'Ukuran file selfie maksimal 2MB',
                'status_kehadiran.required' => 'Status kehadiran harus diisi',
                'status_kehadiran.in' => 'Status kehadiran harus berupa salah satu dari: hadir, terlambat, alfa',
                'status_lokasi.required' => 'Status lokasi harus diisi',
                'status_lokasi.in' => 'Status lokasi harus berupa salah satu dari: dalam_area, di_luar_area',
            ],
        );

        $siswaId = auth()->user()->siswa->id;
        $tanggal = now()->toDateString();
        $waktu = now()->toTimeString();
        $alasan = $request->alasan ?? null;

        $lokasi = json_encode([
            'lat' => $request->latitude,
            'lng' => $request->longitude,
        ]);

        $fotoPath = null;
        if ($request->hasfile('selfie')) {
            $fotoPath = $request->file('selfie')->store('presensi_foto', 'public');
        }

        $presensi = presensiSiswa::where('siswa_id', $siswaId)->where('tanggal', $tanggal)->first();

        $status_kehadiran = $request->status_kehadiran;
        $status_lokasi = $request->status_lokasi;

        if ($request->filled('alasan')) {
            $status_lokasi = 'di_luar_area';
        }

        if (!$presensi) {
            presensiSiswa::create([
                'siswa_id' => $siswaId,
                'tanggal' => $tanggal,
                'jam_masuk' => $waktu,
                'foto_masuk' => $fotoPath,
                'lokasi_masuk' => $lokasi,
                'alasan' => $alasan,
                'status_kehadiran' => $status_kehadiran,
                'status_lokasi' => $status_lokasi,
            ]);
            return response()->json([
                'message' => 'Berhasil Melakukan Check In!',
                'type' => 'masuk',
                'jam' => $waktu,
            ]);
        } elseif (is_null($presensi->jam_keluar)) {
            $presensi->update([
                'jam_keluar' => $waktu,
                'foto_keluar' => $fotoPath,
                'lokasi_keluar' => $lokasi,
            ]);
            return response()->json([
                'message' => 'Berhasil Melakukan Check Out!',
                'type' => 'keluar',
                'jam' => $waktu,
            ]);
        } else {
            return response()->json(['message' => 'Kamu sudah presensi full hari ini.'], 400);
        }
    }

    public function izin_index(Request $request)
    {
        $data = [
            'title' => 'Izin Siswa',
            'siswa' => Siswa::all(),
            'izinSiswa' => izinSiswa::where('siswa_id', Auth::user()->id)->get(),
        ];
        return view('siswa.izin', $data);
    }

    public function izin_store(Request $request)
    {
        $request->validate(
            [
                'jenis_izin' => 'required',
                'tanggal' => 'required|date',
                'keterangan' => 'required|string|max:255',
                'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            ],
            [
                'jenis_izin.required' => 'Jenis izin harus diisi',
                'tanggal.required' => 'Tanggal izin harus diisi',
                'keterangan.required' => 'Keterangan harus diisi',
                'lampiran.image' => 'Lampiran harus berupa gambar',
                'lampiran.file' => 'Lampiran harus berupa file',
                'lampiran.mimes' => 'Lampiran harus berupa file dengan format jpg, jpeg, png, atau pdf',
            ],
        );
        $tanggal = Carbon::parse($request->tanggal);

        $sudahizin = izinSiswa::where('siswa_id', Auth::user()->siswa->id)
            ->whereDate('tanggal', $tanggal)
            ->exists();

        if ($sudahizin) {
            throw ValidationException::withMessages([
                'tanggal' => 'Kamu sudah mengajukan izin di tanggal ini.',
            ]);
        }

        $pathLampiran = null;

        if ($request->hasFile('lampiran')) {
            $lampiran = $request->file('lampiran');
            $pathLampiran = $lampiran->store('lampiran_izin', 'public');
        }

        izinSiswa::create([
            'tanggal' => $tanggal,
            'siswa_id' => Auth::user()->siswa->id,
            'jenis_izin' => $request->jenis_izin,
            'keterangan' => $request->keterangan,
            'lampiran' => $pathLampiran,
        ]);

        return redirect()->back()->with('success', 'Izin berhasil diajukan');
    }

    public function index_kelasKu()
    {
        $siswa = Siswa::with('kelas')->find(Auth::user()->siswa->id);

        $mapelKelasList = MapelKelas::with(['mataPelajaran', 'siswa.user', 'tugas.pengumpulan_tugas'])
            ->where('kelas_id', $siswa->kelas_id)
            ->get()
            ->map(function ($item) use ($siswa) {
                $jumlahTugas = $item->tugas->count();

                $tugasSelesai = $item->tugas
                    ->flatMap(function ($tugas) use ($siswa) {
                        return $tugas->pengumpulan_tugas->where('siswa_id', $siswa->id)->where('status', 'Sudah Dikerjakan');
                    })
                    ->count();

                $item->jumlahTugas = $jumlahTugas;
                $item->tugasSelesai = $tugasSelesai;
                $item->progress = $jumlahTugas > 0 ? round(($tugasSelesai / $jumlahTugas) * 100) : 0;

                return $item;
            });

        $data = [
            'title' => 'Kelas Saya',
            'siswa' => $siswa,
            'kelasKu' => $mapelKelasList,
        ];
        return view('siswa.kelasKu', $data);
    }

    public function jadwal_index(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $selectedDate = $request->input('tanggal') ? Carbon::parse($request->input('tanggal')) : Carbon::now();

        $mapelKelasIds = MapelKelas::where('kelas_id', $siswa->kelas->id)->pluck('id');

        $jadwalHariIni = Jadwal::whereIn('mapel_kelas_id', $mapelKelasIds)
            ->where('tanggal', $selectedDate->format('Y-m-d'))
            ->with(['mapelKelas.mataPelajaran', 'mapelKelas.guru', 'mapelKelas.kelas.jurusan'])
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($jadwal) {
                $now = Carbon::now();
                $jadwalDate = Carbon::parse($jadwal->tanggal);
                $jamMulai = $jadwalDate->copy()->setTimeFromTimeString($jadwal->jam_mulai);
                $jamSelesai = $jadwalDate->copy()->setTimeFromTimeString($jadwal->jam_selesai);

                if ($jadwalDate->isToday()) {
                    $jadwal->is_selesai = $now->gt($jamSelesai);
                    $jadwal->is_berlangsung = $now->between($jamMulai, $jamSelesai);
                } else {
                    $jadwal->is_selesai = $jadwalDate->isPast();
                    $jadwal->is_berlangsung = false;
                }
                return $jadwal;
            });

        $jadwalHariIni = $jadwalHariIni
            ->sortby(function ($jadwal) {
                if ($jadwal->is_berlangsung) {
                    return 0;
                }
                if (!$jadwal->is_selesai) {
                    return 1;
                }
                return 2;
            })
            ->values();

        $startOfWeek = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
        $daysOfWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $tanggal = $startOfWeek->copy()->addDays($i);
            $daysOfWeek[] = [
                'tanggal' => $tanggal,
                'nama_hari' => $tanggal->locale('id')->isoFormat('ddd'),
            ];
        }

        $startOfMonth = $selectedDate->copy()->startOfMonth();
        $endOfMonth = $selectedDate->copy()->endOfMonth();
        $daysOfMonth = [];
        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            $daysOfMonth[] = [
                'nama_hari' => $date->translatedFormat('D'),
                'tanggal' => $date->copy(),
            ];
        }

        $monthName = $selectedDate->locale('id')->isoFormat('MMMM Y');

        $data = [
            'title' => 'Jadwal Pelajaran',
            'siswa' => $siswa,
            'jadwalHariIni' => $jadwalHariIni,
            'selectedDate' => $selectedDate,
            'daysOfWeek' => $daysOfWeek,
            'daysOfMonth' => $daysOfMonth,
            'monthName' => $monthName,
        ];

        if ($request->ajax()) {
            return response()->json([
                'jadwal' => $jadwalHariIni,
                'selectedDate' => $selectedDate->translatedFormat('l, d F Y'),
                'isToday' => $selectedDate->isToday(),
                'monthName' => $monthName,
            ]);
        }

        return view('siswa.jadwal', $data);
    }

    public function jadwal_perhari(Request $request)
    {
        $siswa = auth()->user()->siswa;
        $selectedDate = $request->input('tanggal') ? Carbon::parse($request->input('tanggal')) : Carbon::now();

        $mapelKelasIds = MapelKelas::where('kelas_id', $siswa->kelas->id)->pluck('id');

        $jadwalHariIni = Jadwal::whereIn('mapel_kelas_id', $mapelKelasIds)
            ->where('tanggal', $selectedDate->format('Y-m-d'))
            ->with(['mapelKelas.mataPelajaran', 'mapelKelas.guru', 'mapelKelas.kelas.jurusan'])
            ->orderBy('jam_mulai')
            ->get()
            ->map(function ($jadwal) {
                $now = Carbon::now();
                $jadwalDate = Carbon::parse($jadwal->tanggal);
                $jamMulai = $jadwalDate->copy()->setTimeFromTimeString($jadwal->jam_mulai);
                $jamSelesai = $jadwalDate->copy()->setTimeFromTimeString($jadwal->jam_selesai);

                if ($jadwalDate->isToday()) {
                    $jadwal->is_selesai = $now->gt($jamSelesai);
                    $jadwal->is_berlangsung = $now->between($jamMulai, $jamSelesai);
                } else {
                    $jadwal->is_selesai = $jadwalDate->isPast();
                    $jadwal->is_berlangsung = false;
                }

                return $jadwal;
            });

        return response()->json([
            'jadwal' => $jadwalHariIni,
            'currentTime' => Carbon::now()->format('H:i:s'),
        ]);
    }

    public function jadwal_perminggu(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal);
        $startOfWeek = $tanggal->startOfWeek(Carbon::MONDAY);
        $daysOfWeek = [];

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $daysOfWeek[] = [
                'nama_hari' => $day->locale('id')->translatedFormat('D'),
                'tanggal' => $day,
            ];
        }

        $selectedDate = $tanggal;

        $html = view('siswa.partials.days_perminggu', compact('daysOfWeek', 'selectedDate'))->render();

        return response()->json([
            'daysHtml' => $html,
        ]);
    }

    public function jadwal_perbulan(Request $request)
    {
        $tanggal = Carbon::parse($request->tanggal);
        $startOfMonth = $tanggal->copy()->startOfMonth();
        $startOfCalendar = $startOfMonth->startOfWeek(Carbon::MONDAY);
        $endOfMonth = $tanggal->copy()->endOfMonth();

        $daysOfMonth = [];
        for ($i = 0; $i < 42; $i++) {
            $date = $startOfCalendar->copy()->addDays($i);
            $daysOfMonth[] = ['tanggal' => $date];
        }

        $selectedDate = $tanggal;

        $html = view('siswa.partials.days_perbulan', compact('daysOfMonth', 'selectedDate'))->render();

        return response()->json([
            'daysHtml' => $html,
        ]);
    }
}
