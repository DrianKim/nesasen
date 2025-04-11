<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    // TAMPILAN LOGIN + REGISTER (gabung satu halaman)
    public function index()
    {
        return view('auth.login');
    }

    // PROSES LOGIN
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
            return redirect()->route('dashboard')->with('success', 'Anda Berhasil Login');
        } else {
            return redirect()->back()->with('error', 'Username atau Password Salah');
        }
    }

    // PROSES REGISTER
    public function registerProses(Request $request)
    {
        // Validasi pake custom error bag: 'register'
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

        // Simpan user ke database
        $user = User::create([
            'username' => $request->username,
            'nama' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Auto login langsung
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi dan Login Berhasil!');
    }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda Berhasil Logout');
    }
}
