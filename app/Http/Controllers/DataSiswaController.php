<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataSiswaController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Data Siswa',
            'menu_admin_data_siswa' => 'active',
        );
        return view('admin.kurikulum.data_siswa', $data);
    }
}
