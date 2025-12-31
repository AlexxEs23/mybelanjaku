<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form forgot password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password ke email user
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        // Cek apakah user login via Google
        $user = User::where('email', $request->email)->first();
        
        // Jika user memiliki google_id (atau field lain yang mengidentifikasi login Google)
        // Anda bisa menambahkan field 'google_id' di tabel users untuk identifikasi
        // Untuk saat ini kita asumsikan user yang password-nya null adalah user Google
        if (empty($user->password)) {
            return back()->withErrors([
                'email' => 'Akun Anda menggunakan login Google. Silakan login menggunakan tombol "Login dengan Google".'
            ]);
        }

        // Generate token
        $token = Str::random(64);

        // Hapus token lama jika ada
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Simpan token baru
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => Hash::make($token),
            'created_at' => Carbon::now()
        ]);

        // Kirim email
        try {
            Mail::send('auth.emails.reset-password', [
                'token' => $token,
                'email' => $request->email,
                'name' => $user->name
            ], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Reset Password - ' . config('app.name'));
            });

            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Gagal mengirim email. Silakan coba lagi atau hubungi administrator.'
            ])->withInput();
        }
    }
}
