<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Jadwal;
use App\Models\izinSiswa;
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
}
