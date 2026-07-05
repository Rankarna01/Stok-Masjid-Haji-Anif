<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan halaman form input email (Langkah 1)
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses pengiriman kode OTP 6 digit ke email
     */
    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email tidak terdaftar di dalam sistem.'
            ], 404);
        }

        // Generate 6 digit angka acak (OTP)
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Simpan atau perbarui di tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        // Kirim email
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Kode Verifikasi Reset Password - ' . config('app.name', 'Sistem Stok Masjid'));
            });
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        // Simpan email di session untuk langkah selanjutnya
        session(['reset_email' => $request->email]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kode verifikasi telah dikirim ke email Anda.',
            'redirect' => route('password.otp.form')
        ]);
    }

    /**
     * Tampilkan halaman input kode verifikasi OTP (Langkah 2)
     */
    public function showOtpForm(Request $request)
    {
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', compact('email'));
    }

    /**
     * Verifikasi kode OTP 6 digit
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:6']
        ], [
            'otp.required' => 'Kode verifikasi wajib diisi.',
            'otp.size' => 'Kode verifikasi harus 6 digit angka.'
        ]);

        $record = DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->where('token', $request->otp)
                    ->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode verifikasi OTP salah atau tidak cocok.'
            ], 422);
        }

        // Cek kedaluwarsa (15 menit)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kode verifikasi OTP telah kedaluwarsa (lebih dari 15 menit). Silakan minta kode baru.'
            ], 422);
        }

        // Set session tanda verifikasi berhasil
        session([
            'otp_verified' => true,
            'reset_email' => $request->email
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Verifikasi kode berhasil.',
            'redirect' => route('password.reset')
        ]);
    }

    /**
     * Kirim ulang kode OTP (Resend)
     */
    public function resendOtp(Request $request)
    {
        $email = session('reset_email') ?: $request->email;

        if (!$email) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi email tidak ditemukan. Silakan mulai ulang dari halaman lupa password.'
            ], 400);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pengguna tidak ditemukan.'
            ], 404);
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $otp,
                'created_at' => Carbon::now()
            ]
        );

        try {
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email, $user->name)
                        ->subject('Kode Verifikasi Baru Reset Password - ' . config('app.name', 'Sistem Stok Masjid'));
            });
        } catch (\Exception $e) {
            \Log::error('Gagal kirim ulang email OTP: ' . $e->getMessage());
        }

        session(['reset_email' => $email]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kode verifikasi baru berhasil dikirim ke email Anda.'
        ]);
    }

    /**
     * Tampilkan halaman form reset password baru (Langkah 3)
     */
    public function showResetForm(Request $request)
    {
        if (!session('otp_verified') || !session('reset_email')) {
            return redirect()->route('password.request');
        }

        $email = session('reset_email');

        return view('auth.reset-password', compact('email'));
    }

    /**
     * Simpan password baru ke database
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.'
        ]);

        if (!session('otp_verified') || session('reset_email') !== $request->email) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi verifikasi tidak valid atau telah berakhir.'
            ], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Akun pengguna tidak ditemukan.'
            ], 404);
        }

        // Update password di tabel users
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token reset dari tabel
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Bersihkan session
        session()->forget(['reset_email', 'otp_verified']);

        return response()->json([
            'status' => 'success',
            'message' => 'Kata sandi berhasil diubah! Silakan login menggunakan kata sandi baru Anda.',
            'redirect' => route('login')
        ]);
    }
}
