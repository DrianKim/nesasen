<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PharIo\Manifest\RequiresElement;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->role_id == 4) { 
            $profil = $user->murid; 
        } elseif ($user->role_id == 3 || $user->role_id == 2) { 
            $profil = $user->guru; 
        } else {
            $profil = null; 
        }

        $data = array(
            'title' => 'Halaman Profil',
            'menuProfil' => 'active',
            'profil' => $profil,
        );

        return view('pengguna.index', $data);
    }

    public function edit()
    {
        $user = User::find(Auth::id());
        $profil = $user->role_id == 4 ? $user->murid : $user->guru;

        $data = array(
            'title' => 'Edit Profil',
            'menuProfil' => 'active',
            'user' => $user,
            'profil' => $profil,
        );

        return view('pengguna.index', $data);
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        $profil = $user->role_id == 4 ? $user->murid : $user->guru;

        $request->validate([
            'nama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $profil->nama = $request->nama;
        $profil->tanggal_lahir = $request->tanggal_lahir;
        $profil->no_hp = $request->no_hp;
        $profil->jenis_kelamin = $request->jenis_kelamin;
        $profil->alamat = $request->alamat;

        if ($request->hasFile('foto_profil')) {
            $file = $request->file('foto_profil');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('foto_profil'), $filename);
            $profil->foto_profil = $filename;
        }

        $profil->save();

        $user->email = $request->email;
        $user->save();

        return redirect()->route('profil')->with('success', 'Profil Berhasil Diupdate');
    }
}
