<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataGuruController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Data Guru',
            'menu_admin_data_guru' => 'active',
        );
        return view('admin.kurikulum.data_guru', $data);
    }
}
