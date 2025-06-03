<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('role')) {
            session(['login_role' => $request->role]);
        }

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

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('selectRole')->with('success', 'Anda Berhasil Logout');
    }
}
