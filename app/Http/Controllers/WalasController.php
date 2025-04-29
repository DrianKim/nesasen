<?php

namespace App\Http\Controllers;

use id;
use App\Models\Murid;
use App\Models\Walas;
use Illuminate\Http\Request;
use auth;

class WalasController extends Controller
{
    public function data_kelas_index()
    {
        $walas = Walas::where('user_id', auth()->id())->firstorfail();
        $data = array(
            'title' => 'Halaman Data Kelas XI RPL 1',
            'menuWalas' => 'active',
            'muridList' => Murid::where('kelas_id', $walas->kelas_id)->orderby('nama', 'asc')->get(),
            // 'menu_admin_data_walas' => 'active',
        );

        return view('walas.data_kelas.index', $data);
    }

    public function rekap_presensi_index()
    {
        $data = array(
            'title' => 'Halaman Rekap Presensi',
            'menuWalas' => 'active',
            // 'menu_admin_data_walas' => 'active',
        );

        return view('walas.rekap_presensi.index', $data);
    }
}
