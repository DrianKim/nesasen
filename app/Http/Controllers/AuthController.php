<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginProses(Request $request)
    {
        // dd($request);
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:3',
        ], [
            'email.required' => 'Email wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 3 karakter',
        ]);

        $data = array(
            'email' => $request->email,
            'password' => $request->password,
        );

        if (Auth::attempt($data)) {
            return redirect()->route('dashboard')
                                    ->with('success', 'Anda Berhasil Login');
        } else {
            return redirect()->back()
                            ->with('error', 'Email atau Password salah');
        }

    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')
                                ->with('success', 'Anda Berhasil Logout');
    }
}
