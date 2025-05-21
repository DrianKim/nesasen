<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\izinSiswa;
use App\Models\MapelKelas;
use Illuminate\Support\Str;
use App\Models\absensiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    public function beranda_index()
    {
        $data = array(
            'title' => 'Data Siswa',
            'menuBeranda' => 'active',
            'siswa' => Siswa::all(),
        );
        return view('siswa.beranda', $data);
    }

    public function presensi_index()
    {
        $data = array(
            'title' => 'Presensi Siswa',
            'menuPresensi' => 'active',
            'siswa' => Siswa::all(),
            'absensiSiswa' => absensiSiswa::where('siswa_id', Auth::user()->id)->get(),
        );
        return view('siswa.presensi', $data);
    }

    public function presensi_store(Request $request)
    {
        $request->validate([
            'foto_absen' => 'required|string',
            'lokasi_absen' => 'required|string',
            'keterangan' => 'required|string|max:255',
        ], [
            'foto_absen.required' => 'Foto absen harus diisi',
            'lokasi_absen.required' => 'Lokasi absen harus diisi',
            'keterangan.required' => 'Keterangan harus diisi',
            'keterangan.max' => 'Keterangan tidak boleh lebih dari 255 karakter',
        ]);

        $siswaId = Auth::user()->siswa->id;
        $today = now()->toDateString();

        $presensi = absensiSiswa::where('siswa_id', $siswaId)
            ->whereDate('tanggal', $today)
            ->first();

        $base64String = $request->input('foto_absen');
        $image = str_replace('data:image/png;base64,', '', $base64String);
        $image = str_replace(' ', '+', $image);
        $imageName = 'presensi_' . Str::random(10) . '_' . now()->format('Ymd_His') . '.png';
        Storage::disk('public')->put('presensi/' . $imageName, base64_decode($image));

        if ($presensi) {
            $presensi->update([
                'status' => 'pulang',
                'waktu_absen' => now()->format('H:i:s'),
                'foto_absen' => 'presensi/' . $imageName,
                'lokasi_absen' => $request->lokasi_absen,
                'keterangan' => $request->keterangan,
            ]);

            $message = 'Presensi pulang berhasil diajukan';
        } else {
            absensiSiswa::create([
                'tanggal' => $today,
                'siswa_id' => $siswaId,
                'status' => 'masuk',
                'waktu_absen' => now()->format('H:i:s'),
                'foto_absen' => 'presensi/' . $imageName,
                'lokasi_absen' => $request->lokasi_absen,
                'keterangan' => $request->keterangan,
            ]);

            $message = 'Presensi masuk berhasil diajukan';
        }

        return redirect()->back()->with('success', 'Presensi berhasil diajukan');
    }

    public function izin_index()
    {
        $data = array(
            'title' => 'Izin Siswa',
            'siswa' => Siswa::all(),
            'izinSiswa' => izinSiswa::where('siswa_id', Auth::user()->id)->get(),
        );
        return view('siswa.izin', $data);
    }

    public function izin_store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'jenis_izin' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:255',
            'lampiran' => 'nullable|image|file|mimes:jpg,jpeg,png,pdf',
        ], [
            'jenis_izin.required' => 'Jenis izin harus diisi',
            'tanggal.required' => 'Tanggal izin harus diisi',
            'keterangan.required' => 'Keterangan harus diisi',
            'lampiran.image' => 'Lampiran harus berupa gambar',
            'lampiran.file' => 'Lampiran harus berupa file',
            'lampiran.mimes' => 'Lampiran harus berupa file dengan format jpg, jpeg, png, atau pdf',
        ]);

        $pathLampiran = null;

        $tanggal = Carbon::createFromFormat('d-m-Y', $request->tanggal);

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
            // 'tanggal',
            // 'siswa_id',
            // 'jenis_absen',
            // 'keterangan',
            // 'lampiran'
        ]);

        return redirect()->back()->with('success', 'Izin berhasil diajukan');
    }

    public function index_kelasKu()
    {
        $siswa = Siswa::with('kelas')->find(Auth::user()->siswa->id);

        $mapelKelasList = MapelKelas::with(['mata_pelajaran', 'guru.user', 'tugas.pengumpulan_tugas'])
            ->where('kelas_id', $siswa->kelas_id)
            ->get()
            ->map(function ($item) use ($siswa) {
                $jumlahTugas = $item->tugas->count();

                $tugasSelesai = $item->tugas->flatMap(function ($tugas) use ($siswa) {
                    return $tugas->pengumpulan_tugas->where('siswa_id', $siswa->id)->where('status', 'Sudah Dikerjakan');
                })->count();

                $item->jumlahTugas = $jumlahTugas;
                $item->tugasSelesai = $tugasSelesai;
                $item->progress = $jumlahTugas > 0 ? round(($tugasSelesai / $jumlahTugas) * 100) : 0;

                return $item;
            });


        $data = array(
            'title' => 'Kelas Saya',
            'siswa' => $siswa,
            'kelasKu' => $mapelKelasList,
            // 'kelasKu' => MapelKelas::where('kelas_id', Auth::user()->siswa->kelas_id)->get(),
        );
        return view('siswa.kelasKu', $data);
    }

    public function index_jadwal(Request $request)
    {
        $siswa = auth()->user()->siswa;

        $selectedDate = $request->input('tanggal')
            ? Carbon::parse($request->input('tanggal'))
            : Carbon::now();

        $namaHari = $selectedDate->locale('id')->isoFormat('dddd');

        $mapelKelasIds = MapelKelas::where('kelas_id', $siswa->kelas->id)->pluck('id');

        $jadwalHariIni = Jadwal::whereIn('mapel_kelas_id', $mapelKelasIds)
            ->where('hari', $namaHari)
            ->with(['mapelKelas.mata_pelajaran', 'mapelKelas.kelas'])
            ->orderBy('jam_mulai')
            ->get();

        // ✅ INI bagian yang penting: bikin array 7 hari
        $startOfWeek = $selectedDate->copy()->startOfWeek(Carbon::MONDAY);
        $daysOfWeek = [];
        for ($i = 0; $i < 7; $i++) {
            $daysOfWeek[] = $startOfWeek->copy()->addDays($i);
        }

        // ✅ Pindahin return view ke luar loop
        $data = [
            'title' => 'Jadwal Pelajaran',
            'siswa' => $siswa,
            'jadwalHariIni' => $jadwalHariIni,
            'selectedDate' => $selectedDate,
            'daysOfWeek' => $daysOfWeek
        ];

        return view('siswa.jadwal', $data);
    }
}
