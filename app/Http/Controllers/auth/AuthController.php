<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function loginPost(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Login berhasil! Selamat datang di UMKM Market.');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput($request->only('email'));
    }

    public function register()
    {
        return view('auth.register');
    }

    public function registerPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|unique:users,no_hp',
            'alamat' => 'required|string',
            'password' => 'required|min:6|confirmed'
        ], [
            'name.required' => 'Nama lengkap wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'no_hp.unique' => 'Nomor HP sudah terdaftar',
            'alamat.required' => 'Alamat wajib diisi',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'user', // Default role selalu user
            'status_approval' => 'approved', // User biasa langsung approved
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard')->with('success', 'Registrasi berhasil! Selamat datang di UMKM Market.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    // Google OAuth Methods
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->email)->first();
            
            if ($user) {
                // User sudah ada, langsung login
                Auth::login($user);
            } else {
                // User belum ada, buat akun baru
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(24)), // Random password karena login via Google
                    'no_hp' => '-', // Default karena Google tidak memberikan no HP
                    'alamat' => '-', // Default
                    'role' => 'user', // Default role untuk Google login
                    'status' => 'aktif',
                    'status_approval' => 'approved', // Auto approved untuk user biasa
                ]);
                
                Auth::login($user);
            }
            
            return redirect('/dashboard')->with('success', 'Login dengan Google berhasil!');
            
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google. Silakan coba lagi.');
        }
    }

    public function forgotPassword()
    {
        return view('auth.forgotpassword');
    }
}
