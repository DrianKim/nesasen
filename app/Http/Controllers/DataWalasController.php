<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataWalasController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Data Guru Wali Kelas',
            'menu_admin_data_walas' => 'active',
        );
        return view('admin.kurikulum.data_walas', $data);
    }
}
