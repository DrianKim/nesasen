<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;


class OtpController extends Controller
{
    public function kirimOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $otp = rand(100000, 999999);

        // Simpan OTP dan waktu kadaluarsa
        Session::put('otp_' . $request->email, $otp);
        Session::put('otp_expired_' . $request->email, Carbon::now()->addMinutes(5));
        Session::save(); 
        try {
            Mail::raw("Kode OTP Anda adalah: $otp. Jangan bagikan ke siapapun kode ini", function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Kode OTP Pendaftaran');
            });

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


    public function verifikasiOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|size:6',
        ]);

        $email = $request->email;
        $otp = $request->otp;

        $storedOtp = session('otp_' . $email);
        $otpExpired = session('otp_expired_' . $email);

        Session::put('register_email', $request->email);

    // LOG untuk debugging
        Log::info('OTP INPUT: ' . $otp);
        Log::info('OTP SESSION: ' . $storedOtp);
        Log::info('OTP EXPIRED: ' . $otpExpired);

        // Cek apakah OTP ada dan masih berlaku
        if (!$storedOtp || !$otpExpired || \Carbon\Carbon::now()->gt($otpExpired)) {
            return response()->json(['status' => 'expired']);
        }

        if ((string) $otp !== (string) $storedOtp) {
            return response()->json(['status' => 'invalid']);
        }

        session()->forget('otp_' . $email);
        session()->forget('otp_expired_' . $email);

        return response()->json(['status' => 'success']);
    }
}