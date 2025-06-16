<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
public function index()
    {
        $totalSiswa = Siswa::count();
        $totalPerempuan = Siswa::where('jenis_kelamin', 'Perempuan')->count();
        $totalLaki = Siswa::where('jenis_kelamin', 'Laki-laki')->count();

        $persenPerempuan = $totalSiswa > 0 ? ($totalPerempuan / $totalSiswa) * 100 : 0;
        $persenLaki = $totalSiswa > 0 ? ($totalLaki / $totalSiswa) * 100 : 0;

        $data = [
            'title' => 'Beranda',
            'menuDashboard' => 'active',
            'totalSiswa' => $totalSiswa,
            'totalPerempuan' => $totalPerempuan,
            'totalLaki' => $totalLaki,
            'persenPerempuan' => $persenPerempuan,
            'persenLaki' => $persenLaki,
        ];
        return view('admin.beranda', $data);
    }

    public function index_pengumuman() {
        $data = array(
            'title' => 'Pengumuman',
            'menuPengumuman' => 'active',
        );

        return view('admin.pengumuman', $data);
    }
}
