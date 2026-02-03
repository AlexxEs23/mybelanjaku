<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
        
        // Jika user memiliki google_id atau password kosong (login via Google)
        if (empty($user->password)) {
            return back()->withErrors([
                'email' => 'Akun Anda menggunakan login Google. Silakan login menggunakan tombol "Login dengan Google".'
            ]);
        }

        // Cek konfigurasi email
        $mailDefault = config('mail.default');
        
        // Jika email tidak dikonfigurasi atau menggunakan 'log'
        if ($mailDefault === 'log') {
            // Alternatif: Redirect ke WhatsApp admin
            $adminWhatsApp = '6281234567890'; // Ganti dengan nomor WhatsApp admin
            $message = urlencode(
                "Halo Admin CheckoutAja,\n\n" .
                "Saya ingin reset password untuk akun:\n" .
                "Email: {$request->email}\n" .
                "Nama: {$user->name}\n\n" .
                "Mohon bantuannya. Terima kasih!"
            );
            $whatsappUrl = "https://wa.me/{$adminWhatsApp}?text={$message}";
            
            return back()->with('info', 
                'Email belum dikonfigurasi. Silakan hubungi admin melalui WhatsApp untuk reset password Anda. ' .
                '<a href="' . $whatsappUrl . '" target="_blank" class="font-bold underline text-blue-600">Hubungi Admin via WhatsApp</a>'
            );
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
            // Log error untuk debugging
            Log::error('Failed to send reset email: ' . $e->getMessage());
            
            // Tampilkan pesan error yang user-friendly
            return back()->withErrors([
                'email' => 'Gagal mengirim email. Email mungkin belum dikonfigurasi dengan benar. ' .
                         'Silakan hubungi administrator atau gunakan metode login alternatif.'
            ])->withInput();
        }
    }
}
