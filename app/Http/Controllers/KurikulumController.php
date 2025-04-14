<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Murid;
use Illuminate\Http\Request;

class KurikulumController extends Controller
{
    public function data_walas()
    {
        $data = array(
            'title' => 'Halaman Daftar Wali Kelas',
            'menu_admin_data_walas' => 'active',
            'user' => User::get()->where('role_id', 3),
        );
        return view('admin.kurikulum.walas.index', $data);
    }

    public function data_murid()
    {
        $data = array(
            'title' => 'Halaman Daftar Murid',
            'menu_admin_data_murid' => 'active',
            'murid' => Murid::with('user', 'kelas.jurusan')->get(),
        );
        return view('admin.kurikulum.murid.index', $data);
    }

    public function create_murid()
    {
        $data = array(
            'title' => 'Halaman Tambah Murid',
            'menu_admin_data_murid' => 'active',
        );
        return view('admin.kurikulum.murid.create', $data);
    }

    public function data_guru()
    {
        $data = array(
            'title' => 'Halaman Daftar Guru',
            'menu_admin_data_guru' => 'active',
            'guru' => Guru::with('user', 'mapelKelas.mataPelajaran')->get(),
        );
        return view('admin.kurikulum.guru.index', $data);
    }
}
