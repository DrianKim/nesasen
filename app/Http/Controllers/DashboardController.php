<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSiswa = Siswa::count();
        $totalPerempuan = Siswa::where('jenis_kelamin', 'Perempuan')->count();
        $totalLaki = Siswa::where('jenis_kelamin', 'Laki-laki')->count();

        $persenPerempuan = $totalSiswa > 0 ? ($totalPerempuan / $totalSiswa) * 100 : 0;
        $persenLaki = $totalSiswa > 0 ? ($totalLaki / $totalSiswa) * 100 : 0;

        // Ambil role user buat filter pengumuman
        $role = Auth::user()->role;
        $today = now();

        if (Auth::user()->role_id === 1) {
            $pengumumen = \App\Models\Pengumuman::where('kadaluarsa_sampai', '>=', $today)->orderByDesc('tanggal')->limit(3)->get();
        } else {
            $pengumumen = \App\Models\Pengumuman::where(function ($q) use ($role) {
                $q->where('ditujukan_untuk', $role)->orWhere('ditujukan_untuk', 'semua');
            })
                ->where('kadaluarsa_sampai', '>=', $today)
                ->orderByDesc('tanggal')
                ->limit(3)
                ->get();
        }

        $data = [
            'title' => 'Beranda',
            'menuDashboard' => 'active',
            'totalSiswa' => $totalSiswa,
            'totalPerempuan' => $totalPerempuan,
            'totalLaki' => $totalLaki,
            'persenPerempuan' => $persenPerempuan,
            'persenLaki' => $persenLaki,
            'pengumumen' => $pengumumen,
        ];

        return view('admin.beranda', $data);
    }

    public function index_pengumuman()
    {
        $pengumumen = Pengumuman::where('user_id', Auth::id())->orderByDesc('created_at')->get();

        return view('admin.pengumuman', compact('pengumumen'))->with('title', 'Buat Pengumuman');
    }

    public function store_pengumuman(Request $request)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
            'durasi' => 'required|in:1,3,7',
            'ditujukan_untuk' => 'required|in:siswa,guru,semua',
        ]);

        $tanggal = now();
        $kadaluarsa = $tanggal->copy()->addDays((int) $request->durasi);

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'ditujukan_untuk' => $request->ditujukan_untuk,
            'tanggal' => $tanggal,
            'kadaluarsa_sampai' => $kadaluarsa,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin_pengumuman.index')->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit_pengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $durasi = Carbon::parse($pengumuman->tanggal)->diffInDays(Carbon::parse($pengumuman->kadaluarsa_sampai));

        return response()->json([
            'judul' => $pengumuman->judul,
            'isi' => $pengumuman->isi,
            'durasi' => $durasi,
        ]);
    }

    public function update_pengumuman(Request $request, $id)
    {
        $request->validate([
            'judul' => 'required|max:255',
            'isi' => 'required',
        ]);

        $pengumuman = Pengumuman::findOrFail($id);

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

        return redirect()->route('admin_pengumuman.index')->with('success', 'Pengumuman berhasil diubah.');
    }

    public function destroy_pengumuman($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();

        return response()->json(['success' => true]);
    }
}
