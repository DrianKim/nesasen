<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\izinSiswa;
use App\Models\MapelKelas;
use App\Models\absensiSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SiswaController extends Controller
{
    public function beranda_index()
    {
        $data = array(
            'title' => 'Data Siswa',
            'siswa' => Siswa::all(),
        );
        return view('siswa.beranda', $data);
    }

    public function presensi_index()
    {
        $data = array(
            'title' => 'Presensi Siswa',
            'siswa' => Siswa::all(),
            'absensiSiswa' => absensiSiswa::where('siswa_id', Auth::user()->id)->get(),
        );
        return view('siswa.presensi', $data);
    }

    public function izin_index()
    {
        $data = array(
            'title' => 'Izin Siswa',
            'siswa' => Siswa::all(),
            'absensiSiswa' => absensiSiswa::where('siswa_id', Auth::user()->id)->get(),
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
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ], [
            'jenis_izin.required' => 'Jenis izin harus diisi',
            'tanggal.required' => 'Tanggal izin harus diisi',
            'keterangan.required' => 'Keterangan harus diisi',
            'lampiran.file' => 'Lampiran harus berupa file',
            'lampiran.mimes' => 'Lampiran harus berupa file dengan format jpg, jpeg, png, atau pdf',
            'lampiran.max' => 'Lampiran tidak boleh lebih dari 2MB',
        ]);

        izinSiswa::create([
            'tanggal' => $request->tanggal,
            'siswa_id' => Auth::user()->siswa->id,
            'jenis_izin' => $request->jenis_izin,
            'keterangan' => $request->keterangan,
            'lampiran' => $request->file('lampiran') ? $request->file('lampiran')->store('lampiran_izin') : null,
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
