<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    // TAMPILKAN HALAMAN INPUT EMAIL + OTP
    public function showOtpPage(Request $request)
    {
        return view('auth.forgot-otp', ['role' => $request->role]);
    }

    // KIRIM OTP KE EMAIL
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'role' => 'required|in:siswa,guru',
        ]);

        if ($request->role === 'siswa') {
            $user = User::whereHas('siswa', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->first();
        } elseif ($request->role === 'guru') {
            $user = User::whereHas('guru', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->first();
        } else {
            $user = null;
        }


        if (!$user) {
            return response()->json(['status' => 'not_found']);
        }

        $otp = rand(100000, 999999);
        Session::put('forgot_otp_' . $request->email, $otp);
        Session::put('forgot_otp_exp_' . $request->email, now()->addMinutes(5));

        try {
            Mail::raw("Kode OTP reset password kamu: $otp", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Password - OTP');
            });

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // VERIFIKASI OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|size:6',
        ]);

        $otp = Session::get('forgot_otp_' . $request->email);
        $expired = Session::get('forgot_otp_exp_' . $request->email);

        if (!$otp || !$expired || now()->gt($expired)) {
            return response()->json(['status' => 'expired']);
        }

        if ((string) $request->otp !== (string) $otp) {
            return response()->json(['status' => 'invalid']);
        }

        Session::forget('forgot_otp_' . $request->email);
        Session::forget('forgot_otp_exp_' . $request->email);

        return response()->json([
            'status' => 'success',
            'username' => $user->username ?? null,
            'redirect' => route('forgot.reset', [
                'email' => $request->email,
                'role' => $request->role
            ])
        ]);
    }


    // HALAMAN RESET PASSWORD
    public function showResetForm(Request $request)
    {
        $email = $request->email;
        $role = $request->role;

        // Validasi untuk role kosong
        if (!$role) {
            return redirect()->route('selectRole')->with('error', 'Role tidak ditemukan.');
        }

        $username = null;

        // Cek berdasarkan role
        if ($role === 'admin') {
            return view('auth.reset-password', [
                'email' => null,
                'role' => 'admin',
                'username' => null
            ]);
        } elseif ($role === 'siswa') {
            if (!$email) {
                return redirect()->route('forgot.otp', ['role' => 'siswa'])->with('error', 'Email tidak ditemukan.');
            }

            $user = \App\Models\User::whereHas('siswa', function ($query) use ($email) {
                $query->where('email', $email);
            })->first();

            $username = $user?->username;
        } elseif ($role === 'guru') {
            if (!$email) {
                return redirect()->route('forgot.otp', ['role' => 'guru'])->with('error', 'Email tidak ditemukan.');
            }

            $user = \App\Models\User::whereHas('guru', function ($query) use ($email) {
                $query->where('email', $email);
            })->first();

            $username = $user?->username;
        }

        return view('auth.reset-password', [
            'email' => $email,
            'role' => $role,
            'username' => $username
        ]);
    }

    // PROSES RESET PASSWORD
    public function resetPassword(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'role' => 'required|in:admin,guru,siswa',
            'email' => 'required_if:role,guru,siswa|email',
            'username' => 'required_if:role,admin',
            'new_password' => 'required|confirmed|min:6',
        ]);

        $role = $request->role;
        $email = $request->email;
        $user = null;

        $password = Hash::make($request->new_password);

        if ($role === 'siswa') {
            $user = User::whereHas('siswa', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->first();
        } elseif ($role === 'guru') {
            $user = User::whereHas('guru', function ($q) use ($request) {
                $q->where('email', $request->email);
            })->first();
        } elseif ($role === 'admin') {
            $user = User::where('username', $request->username)->where('role_id', 1)->first();
        }

        if (!$user) {
            return back()->with('error', 'Data tidak ditemukan.');
        }

        $user->password = $password;
        $user->save();

        return redirect()->route('login', ['role' => $role])
            ->with('success', 'Password berhasil direset.');
    }
}
