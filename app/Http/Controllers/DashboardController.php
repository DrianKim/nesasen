<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = array(
            'title' => 'Beranda',
            'menuDashboard' => 'active',
        );
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
