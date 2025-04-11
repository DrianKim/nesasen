<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function data_walas()
    {
        $data = array(
            'title' => 'Halaman Daftar Wali Kelas',
            'menu_admin_data_walas' => 'active',
        );
        return view('admin.kurikulum.walas.index', $data);
    }

    public function data_murid()
    {
        $data = array(
            'title' => 'Halaman Daftar Murid',
            'menu_admin_data_murid' => 'active',
        );
        return view('admin.kurikulum.murid.index', $data);
    }

    public function data_guru()
    {
        $data = array(
            'title' => 'Halaman Daftar Guru',
            'menu_admin_data_guru' => 'active',
        );
        return view('admin.kurikulum.guru.index', $data);
    }
}
