<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function loginProses(Request $request)
    {
        // dd($request);
        $request->validate([
            'username' => 'required',
            'password' => 'required|min:3',
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter',
        ]);

        $data = array(
            'username' => $request->username,
            'password' => $request->password,
        );

        if (Auth::attempt($data)) {
            // dd(Auth::user());
            return redirect()->route('dashboard')
                ->with('success', 'Anda Berhasil Login');
        } else {
            return redirect()->back()
                ->with('error', 'Username atau Password Salah');
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')
                                ->with('success', 'Anda Berhasil Logout');
    }
}
