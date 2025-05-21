<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;

class RegisterController extends Controller
{
    public function simpanData(Request $request)
    {
        $role = $request->role === 'murid' ? 'siswa' : $request->role;

        $request->merge(['role' => $role]);

        $request->validate([
            'nama' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|string',
            'email' => 'required|email',
            'role' => 'required|in:siswa,guru',
        ]);

            Session::put('register_nama', $request->nama);
            Session::put('register_tanggal_lahir', $request->tanggal_lahir);
            Session::put('register_no_hp', $request->no_hp);
            Session::put('register_email', $request->email);
            Session::put('register_role', $role);

            return redirect()->route('register.user', [
                'role' => $role,
                'email' => $request->email,
        ]);
    }

    public function showRegisterUser(Request $request)
    {
        $role = $request->query('role');
        $email = $request->query('email');

        return view('auth.regist-user', compact('role', 'email'));
    }

    public function simpanAkun(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required|confirmed|min:6',
        ]);

        $role = Session::get('register_role');
        $email = Session::get('register_email');
        $nama = Session::get('register_nama');
        $tanggal_lahir = Session::get('register_tanggal_lahir');
        $no_hp = Session::get('register_no_hp');

        if (!$role || !$email || !$nama) {
            return back()->with('error', 'Data tidak lengkap.');
        }

        if ($role === 'siswa') {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => 4,
            ]);

            $user->siswa()->create([
                'nama' => $nama,
                'email' => $email,
                'tanggal_lahir' => $tanggal_lahir,
                'no_hp' => $no_hp,
                // tambahin data lain kalau ada
            ]);
        } elseif ($role === 'guru') {
            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role_id' => 3,
            ]);

            $user->guru()->create([
                'nama' => $nama,
                'email' => $email,
                'tanggal_lahir' => $tanggal_lahir,
                'no_hp' => $no_hp,
                // data lainnya
            ]);
        }

        // Hapus session
        Session::forget([
            'register_nama',
            'register_email',
            'register_tanggal_lahir',
            'register_no_hp',
            'register_role'
        ]);

        return redirect()->route('login', ['role' => $role])
            ->with('success', 'Akun berhasil dibuat. Silakan login!');
    }
}
