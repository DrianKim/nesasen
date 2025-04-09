<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function data_walas()
    {
        $data = array(
            'title' => 'Data Guru Wali Kelas',
            'menu_admin_data_walas' => 'active',
        );
        return view('admin.kurikulum.data_walas', $data);
    }

    public function data_siswa()
    {
        $data = array(
            'title' => 'Data Siswa',
            'menu_admin_data_siswa' => 'active',
        );
        return view('admin.kurikulum.data_siswa', $data);
    }

    public function data_guru()
    {
        $data = array(
            'title' => 'Data Guru',
            'menu_admin_data_guru' => 'active',
        );
        return view('admin.kurikulum.data_guru', $data);
    }
}
