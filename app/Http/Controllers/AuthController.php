<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Log;


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
        ]);

        if (Auth::attempt($request->only('username', 'password'))) {
            return redirect()->route('dashboard')->with('success', 'Berhasil login');
        }

        return redirect()->back()->with('error', 'Username atau password salah');
    }

    public function registerProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|exists:users,username',
            'nisnip' => 'required',
            'tanggal_lahir' => [
            'required',
            'date',
            'before_or_equal:' . now()->subYears(10)->format('Y-m-d'),
            ],
            'email' => 'required|email|unique:users,email',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'password' => 'required|string|min:3|confirmed',
        ]);
        

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('username', $request->username)->firstOrFail();

        $user->tanggal_lahir = $request->tanggal_lahir;
        $user->email = $request->email;
        $user->jenis_kelamin = $request->jenis_kelamin;
        $user->password = Hash::make($request->password);

        // Simpan NIS/NIP tergantung role
        if ($user->role->nama_role === 'murid') {
            $user->nis = $request->nisnip;
        } else {
            $user->nip = $request->nisnip;
        }

        $user->save();
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil!');
    }

    public function cekUsername(Request $request)
    {
        try {
            $user = DB::table('users')
                ->join('roles', 'users.role_id', '=', 'roles.id')
                ->where('users.username', $request->username)
                ->select('users.username', 'roles.nama_role as role', 'roles.deskripsi as deskripsi')
                ->first();

            if ($user) {
                return response()->json([
                    'status' => 'valid',
                    'username' => $user->username,
                    'role' => $user->role,
                    'deskripsi' => $user->deskripsi,
                ]);
            }

            return response()->json(['status' => 'invalid']);
        } catch (\Exception $e) {
            Log::error("Cek Username Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Server error',
            ], 500);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Anda Berhasil Logout');
    }
}
