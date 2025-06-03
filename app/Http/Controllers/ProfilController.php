<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function index()
    {
        $user = User::find(Auth::id());

        if ($user->role_id == 4) {
            $profil = $user->siswa;
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

        return view('profil.index', $data);
    }

    public function edit()
    {
        $user = User::find(Auth::id());
        $profil = $user->role_id == 4 ? $user->siswa : $user->guru;

        $data = array(
            'title' => 'Edit Profil',
            'menuProfil' => 'active',
            'user' => $user,
            'profil' => $profil,
        );

        return view('profil.index', $data);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $profil = $user->role_id == 4 ? $user->siswa : $user->guru;

        $validationRules = [
            'email' => 'required|email',
            'no_hp' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($request->filled('current_password')) {
            $validationRules['current_password'] = 'required|current_password';
            $validationRules['new_password'] = 'required|min:8|confirmed';
        }

        if ($user->role_id == 4) {
            $validationRules['nis'] = 'required|string';
        } else {
            $validationRules['nip'] = 'required|string';
        }

        $request->validate($validationRules);

        if ($request->filled('new_password') && $request->filled('current_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                if ($request->current_password === $request->new_password) {
                    return back()->withErrors(['new_password' => 'Password baru tidak boleh sama dengan password lama'])->withInput();
                }
            } else {
                return back()->withErrors(['current_password' => 'Password lama salah'])->withInput();
            }
        }

        $profil->email = $request->email;
        $profil->no_hp = $request->no_hp;
        $profil->tanggal_lahir = $request->tanggal_lahir;
        $profil->jenis_kelamin = $request->jenis_kelamin;
        $profil->alamat = $request->alamat;

        if ($user->role_id == 4) {
            $profil->nis = $request->nis;
        } else {
            $profil->nip = $request->nip;
        }

        if ($request->hasFile('foto_profil')) {
            if ($profil->foto_profil && file_exists(public_path('foto_profil/' . $profil->foto_profil))) {
                unlink(public_path('foto_profil/' . $profil->foto_profil));
            }

            $file = $request->file('foto_profil');
            $filename = time() . '.' . $file->getClientOriginalExtension();

            if (!file_exists(public_path('foto_profil'))) {
                mkdir(public_path('foto_profil'), 0775, true);
            }

            $file->move(public_path('foto_profil'), $filename);
            $profil->foto_profil = $filename;
        }

        $profil->save();

        if ($request->filled('new_password')) {
            DB::table('users')->where('id', $user->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Foto profil berhasil diperbarui']);
        }

        return redirect()->route('profil.index')->with('success', 'Profil Berhasil Diupdate');
    }
}
