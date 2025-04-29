<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Models\Murid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MuridController extends Controller
{
    public function index_jadwal_pelajaran()
    {
        $user = Auth::user();
        $muridId = $user->murid_id;
        $kelasId = Murid::find($muridId)->kelas_id;

        $data = array(
            'title' => 'Halaman Jadwal Pelajaran',
            'jadwalList' => Jadwal::wherehas('mapelKelas.kelas', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
                ->with(['mapelKelas.mapel', 'mapelKelas.kelas'])
                ->orderby('hari')->orderby('jam_mulai')->get(),
        );

        return view('murid.jadwal_pelajaran', $data);
    }
}
