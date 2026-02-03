<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        $user = Auth::user(); // ambil user SETELAH login

        return match ($user->role) {
            'admin'   => redirect()->route('admin.dashboard')
                          ->with('success', 'Login berhasil sebagai Admin'),
            'penjual' => redirect()->route('penjual.dashboard')
                          ->with('success', 'Login berhasil sebagai Penjual'),
            'pembeli' => redirect()->route('home')
                          ->with('success', 'Login berhasil! Selamat datang'),
            'user'    => redirect()->route('home')
                          ->with('success', 'Login berhasil! Selamat datang'),
            default   => redirect()->route('home')
                          ->with('success', 'Login berhasil! Selamat datang'),
        };
    }

    // login gagal
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

        return redirect()->route('home')->with('success', 'Registrasi berhasil! Selamat datang di CheckoutAja.');
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

    public function handleGoogleCallback(Request $request)
    {
        try {
            Log::info('=== Google Callback Started ===');
            
            $googleUser = Socialite::driver('google')->user();
            
            Log::info('Google User Data', [
                'email' => $googleUser->email,
                'name' => $googleUser->name,
                'id' => $googleUser->id
            ]);
            
            // Cari user berdasarkan email atau google_id
            $user = User::where('email', $googleUser->email)
                ->orWhere('google_id', $googleUser->id)
                ->first();
            
            if ($user) {
                // User sudah ada
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->id]);
                }
                Log::info('Existing user found', ['user_id' => $user->id]);
            } else {
                // User belum ada, buat akun baru
                // Generate unique no_hp untuk Google user
                $uniquePhone = 'google_' . $googleUser->id;
                
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(24)),
                    'no_hp' => $uniquePhone, // Unique per Google user
                    'alamat' => 'Belum diisi',
                    'role' => 'user',
                    'status' => 'aktif',
                    'status_approval' => 'approved',
                    'google_id' => $googleUser->id,
                ]);
                Log::info('New user created', ['user_id' => $user->id]);
            }
            
            // Login dengan remember me
            Auth::login($user, true);
            
            // Regenerate session
            $request->session()->regenerate();
            
            Log::info('Auth check after login', [
                'authenticated' => Auth::check(),
                'user_id' => Auth::id()
            ]);
            
            // Redirect berdasarkan role
            return match ($user->role) {
                'admin'   => redirect()->route('admin.dashboard')
                              ->with('success', 'Login dengan Google berhasil!'),
                'penjual' => redirect()->route('penjual.dashboard')
                              ->with('success', 'Login dengan Google berhasil!'),
                'pembeli' => redirect()->route('home')
                              ->with('success', 'Login dengan Google berhasil!'),
                'user'    => redirect()->route('home')
                              ->with('success', 'Login dengan Google berhasil!'),
                default   => redirect()->route('home')
                              ->with('success', 'Login dengan Google berhasil!'),
            };
            
        } catch (\Exception $e) {
            Log::error('Google Login Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/login')->withErrors(['error' => 'Gagal login dengan Google: ' . $e->getMessage()]);
        }
    }

    public function forgotPassword()
    {
        return view('auth.forgotpassword');
    }
}
