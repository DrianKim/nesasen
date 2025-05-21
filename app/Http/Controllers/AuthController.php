<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Guru;

class AuthController extends Controller
{
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
            'role'     => 'required', // Tambahan validasi role
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 3 karakter',
            'role.required'     => 'Role tidak valid',
        ]);

        $credentials = $request->only('username', 'password');

        // Cek apakah role user sesuai dengan yang dipilih

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cek apakah role user cocok dengan role dari URL/form
            if ($user->role->nama_role === $request->role) {
                 return redirect()->route('dashboard')->with('success', 'Login berhasil');
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Role tidak sesuai, silakan login sesuai peran');
            }
        } else {
            return redirect()->back()->with('error', 'Username atau Password salah');
        }
    }

    //PROSES REGISTRASI

//     public function simpanAkun(Request $request)
// {
//     $request->validate([
//         'username' => 'required|unique:users,username',
//         'password' => 'required|min:3',
//         'confirm_password' => 'required|same:password',
//     ]);

//     $role = Session::get('register_role');
//     $nama = Session::get('register_nama');
//     $email = Session::get('register_email');
//     $tanggal_lahir = Session::get('register_tanggal_lahir');
//     $no_hp = Session::get('register_no_hp');

//     if (!$role || !$email || !$nama) {
//         return redirect()->back()->with('error', 'Data session tidak lengkap. Silakan ulangi proses registrasi.');
//     }

//     if ($role === 'siswa') {
//         $siswa = Siswa::create([
//             'nama' => $nama,
//             'email' => $email,
//             'tanggal_lahir' => $tanggal_lahir,
//             'no_hp' => $no_hp,
//         ]);

//         User::create([
//             'username' => $request->username,
//             'password' => Hash::make($request->password),
//             'role_id' => 4,
//             'siswa_id' => $siswa->id,
//         ]);

//     } elseif ($role === 'guru') {
//         $guru = Guru::create([
//             'nama' => $nama,
//             'email' => $email,
//             'tanggal_lahir' => $tanggal_lahir,
//             'no_hp' => $no_hp,
//         ]);

//         User::create([
//             'username' => $request->username,
//             'password' => Hash::make($request->password),
//             'role_id' => 3,
//             'guru_id' => $guru->id,
//         ]);
//     }

//     // Bersihkan session registrasi
//     Session::forget([
//         'register_email',
//         'register_nama',
//         'register_tanggal_lahir',
//         'register_no_hp',
//         'register_role',
//     ]);

//     return redirect()->route('login', ['role' => $role])->with('success', 'Akun berhasil dibuat, silakan login.');
// }

    // LOGOUT
    public function logout()
    {
        Auth::logout();
        return redirect()->route('selectRole')->with('success', 'Anda Berhasil Logout');
    }
}
