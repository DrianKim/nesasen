<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajarController extends Controller
{
    public function index_jadwal_mengajar()
    {
        $user = Auth::user();
        $guruId = $user->guru_id;
        $data = array (
            'title' => 'Halaman Jadwal Mengajar',
            'jadwalList' => Jadwal::wherehas('mapelKelas', function($query) use ($guruId) {
                $query->where('guru_id', $guruId);
            })
            ->with(['mapelKelas.mapel', 'mapelKelas.kelas'])
            ->orderby('hari')->orderby('jam_mulai')->get(),
        );

        return view('pengajar.jadwal_mengajar', $data);
    }
}
