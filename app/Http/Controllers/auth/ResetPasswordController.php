<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Reset password user
     */
    public function reset(Request $request)
    {
        // Validasi input
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'token.required' => 'Token tidak valid.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Cek token di database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Validasi token
        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Token reset password tidak valid atau sudah kadaluarsa.']);
        }

        // Cek apakah token cocok
        if (!Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Token reset password tidak valid.']);
        }

        // Cek apakah token sudah kadaluarsa (24 jam)
        $tokenCreatedAt = Carbon::parse($passwordReset->created_at);
        if (Carbon::now()->diffInHours($tokenCreatedAt) > 24) {
            // Hapus token yang sudah kadaluarsa
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['email' => 'Token reset password sudah kadaluarsa. Silakan ajukan reset password baru.']);
        }

        // Cek apakah user login via Google
        $user = User::where('email', $request->email)->first();
        if (empty($user->password)) {
            return back()->withErrors([
                'email' => 'Akun Anda menggunakan login Google. Password tidak dapat direset.'
            ]);
        }

        // Update password user
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token setelah berhasil reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru Anda.');
    }
}
