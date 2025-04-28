<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalasController extends Controller
{
    public function data_kelas_index()
    {
        $data = array(
            'title' => 'Halaman Data Kelas',
            'menuWalas' => 'active',
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
