<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function loginProses(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:3',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek role dari request URL
            if ($user->role->nama_role !== $request->role) {
                Auth::logout();
                return redirect()->back()->with('error', 'Username atau Password salah');
            }

            // Redirect sesuai role_id
            if ($user->role_id == 1) {
                return redirect()->route('dashboard')->with('success', 'Anda Berhasil Login');
            } elseif ($user->role_id == 2 || $user->role_id == 3) {
                return redirect()->route('dashboard')->with('success', 'Anda Berhasil Login');
            } elseif ($user->role_id == 4) {
                return redirect()->route('siswa.beranda')->with('success', 'Anda Berhasil Login');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Role tidak dikenali');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau Password salah');
        }
    }

    public function registerProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:3|confirmed',
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'register')
                ->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi dan Login Berhasil!');
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('selectRole')->with('success', 'Anda Berhasil Logout');
    }
}
