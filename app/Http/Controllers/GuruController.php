<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Guru;
use App\Models\Jadwal;
use App\Models\izinGuru;
use App\Models\MapelKelas;
use App\Models\presensiGuru;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    public function beranda_index()
    {
        $pengumumen = Pengumuman::where(function ($q) {
            $q->where('ditujukan_untuk', 'guru')->orWhere('ditujukan_untuk', 'semua');
        })
            ->where('kadaluarsa_sampai', '>=', now())
            ->orderByDesc('tanggal')
            ->take(3)
            ->get();

        return view('guru.beranda', [
            'title' => 'Beranda',
            'menuBeranda' => 'active',
            'pengumumen' => $pengumumen,
        ]);
    }

    public function profil_index()
    {
        $user = auth()->user();
        $guru = $user->guru;

        $hadir = PresensiGuru::where('guru_id', $guru->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->whereIn('status_kehadiran', ['hadir', 'terlambat'])
            ->count();

        $izin = izinGuru::where('guru_id', $guru->id)
            ->whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->whereIn('jenis_izin', ['Izin', 'Keperluan Keluarga', 'Keperluan Sekolah', 'Sakit'])
            ->count();

        $kelasDiampu = MapelKelas::where('guru_id', $guru->id)->count();

        $walasKelas = auth()->user()->walas->kelas ?? null;

        $data = [
            'title' => 'Profil Guru',
            'menuProfil' => 'active',
            'user' => $user,
            'guru' => $guru,
            'hadir' => $hadir,
            'izin' => $izin,
            'kelasDiampu' => $kelasDiampu,
            'walasKelas' => $walasKelas,
        ];
        return view('guru.profil', $data);
    }

    public function profil_update(Request $request)
    {
        try {
            $user = auth()->user();
            $guru = $user->guru;

            $request->validate(
                [
                    'nama' => 'required|string|max:255',
                    'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                    'nip' => 'nullable|string|max:20',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'tanggal_lahir' => 'nullable|date',
                    'no_hp' => 'nullable|string|max:20',
                    'email' => 'required|email|max:255',
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

            $guru->update([
                'nama' => $request->nama,
                'nip' => $request->nip,
                'jenis_kelamin' => $request->jenis_kelamin,
                'tanggal_lahir' => $request->tanggal_lahir,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
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

        return redirect()->route('guru.profil')->with('success', 'Profil berhasil diperbarui!');
    }

    public function presensi_index(Request $request)
    {
        $data = [
            'title' => 'Presensi Guru',
            'menuPresensi' => 'active',
            'guru' => Guru::all(),
            'presensiGuru' => presensiGuru::where('guru_id', Auth::user()->id)->get(),
        ];
        return view('guru.presensi', $data);
    }

    public function presensi_hari_ini()
    {
        $guruId = auth()->user()->guru->id;
        $tanggal = now()->toDateString();

        $presensi = presensiGuru::where('guru_id', $guruId)->where('tanggal', $tanggal)->first();

        return response()->json([
            'jam_masuk' => optional($presensi)->jam_masuk,
            'jam_keluar' => optional($presensi)->jam_keluar,
        ]);
    }

    public function presensi_reminder()
    {
        $user = auth()->user();

        if ($user->role_id != 3 || !$user->guru) {
            return response()->json(['error', 'Akses Ditolak'], 403);
        }

        $guruId = $user->guru->id;
        $tanggal = now()->toDateString();

        $presensi = presensiGuru::where('guru_id', $guruId)->where('tanggal', $tanggal)->first();

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

        $guruId = auth()->user()->guru->id;
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

        $presensi = presensiGuru::where('guru_id', $guruId)->where('tanggal', $tanggal)->first();

        $status_kehadiran = $request->status_kehadiran;
        $status_lokasi = $request->status_lokasi;

        if ($request->filled('alasan')) {
            $status_lokasi = 'di_luar_area';
        }

        if (!$presensi) {
            presensiGuru::create([
                'guru_id' => $guruId,
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
            'title' => 'Izin Guru',
            'guru' => Guru::all(),
            'izinGuru' => izinGuru::where('guru_id', Auth::user()->id)->get(),
        ];
        return view('guru.izin', $data);
    }

    public function izin_store(Request $request)
    {
        $request->validate(
            [
                'jenis_izin' => 'required',
                'tanggal' => 'required|date|after_or_equal:today',
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
        // $tanggal = Carbon::createFromFormat('d-m-Y', $request->tanggal);
        $tanggal = Carbon::parse($request->tanggal);

        $sudahizin = izinGuru::where('guru_id', Auth::user()->guru->id)
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

        izinGuru::create([
            'tanggal' => $tanggal,
            'guru_id' => Auth::user()->guru->id,
            'jenis_izin' => $request->jenis_izin,
            'keterangan' => $request->keterangan,
            'lampiran' => $pathLampiran,
        ]);

        return redirect()->back()->with('success', 'Izin berhasil diajukan');
    }

    public function jadwal_index(Request $request)
    {
        $guru = auth()->user()->guru;
        $selectedDate = $request->input('tanggal') ? Carbon::parse($request->input('tanggal')) : Carbon::now();

        $mapelKelasIds = MapelKelas::where('guru_id', $guru->id)->pluck('id');

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
            'guru' => $guru,
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

        return view('guru.jadwal', $data);
    }

    public function jadwal_perhari(Request $request)
    {
        $guru = auth()->user()->guru;
        $selectedDate = $request->input('tanggal') ? Carbon::parse($request->input('tanggal')) : Carbon::now();

        $mapelKelasIds = MapelKelas::where('guru_id', $guru->kelas->id)->pluck('id');

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

        $html = view('guru.partials.days_perminggu', compact('daysOfWeek', 'selectedDate'))->render();

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

        $html = view('guru.partials.days_perbulan', compact('daysOfMonth', 'selectedDate'))->render();

        return response()->json([
            'daysHtml' => $html,
        ]);
    }

    public function pengumuman_index()
    {
        $pengumumen = Pengumuman::where('user_id', Auth::id())->orderByDesc('tanggal')->get();

        return view('guru.pengumuman', [
            'title' => 'Pengumuman',
            'pengumumen' => $pengumumen,
        ]);
    }

    public function pengumuman_store(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'durasi' => 'required|in:1,3,7',
        ]);

        $tanggal = now();
        $kadaluarsa = $tanggal->copy()->addDays((int) $request->durasi);

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'ditujukan_untuk' => 'siswa', // fix ke siswa, karena guru ga bisa ubah
            'tanggal' => $tanggal,
            'kadaluarsa_sampai' => $kadaluarsa,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('guru.pengumuman')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function pengumuman_destroy($id)
    {
        Log::info("Hapus pengumuman id $id oleh user " . Auth::id());

        $pengumuman = Pengumuman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $pengumuman->delete();

        return response()->json(['success' => true]);
    }

    public function pengumuman_edit($id)
    {
        $pengumuman = Pengumuman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $durasi = Carbon::parse($pengumuman->tanggal)->diffInDays(Carbon::parse($pengumuman->kadaluarsa_sampai));

        return response()->json([
            'judul' => $pengumuman->judul,
            'isi' => $pengumuman->isi,
            'durasi' => $durasi,
        ]);
    }

    public function pengumuman_update(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
        ]);

        $pengumuman = Pengumuman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($request->filled('durasi')) {
            $durasi = (int) $request->durasi;
        } else {
            $durasi = Carbon::parse($pengumuman->tanggal)->diffInDays(Carbon::parse($pengumuman->kadaluarsa_sampai));
        }

        $tanggal = now();
        $kadaluarsa = $tanggal->copy()->addDays($durasi);

        $pengumuman->update([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'tanggal' => $tanggal,
            'kadaluarsa_sampai' => $kadaluarsa,
        ]);

        return redirect()->route('guru.pengumuman')->with('success', 'Pengumuman berhasil diubah.');
    }
}
