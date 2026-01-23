<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ProfileUmkm;
use Illuminate\Support\Facades\Auth;

class CheckSellerVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user login sebagai penjual
        if (Auth::check() && Auth::user()->role === 'penjual') {
            // Cek apakah penjual sudah terverifikasi
            $profileUmkm = ProfileUmkm::where('user_id', Auth::id())->first();
            
            if (!$profileUmkm) {
                return redirect()->route('profile-umkm.index')
                    ->with('error', 'Anda harus mendaftar sebagai penjual terlebih dahulu.');
            }
            
            if ($profileUmkm->status_verifikasi !== 'verified') {
                return redirect()->route('dashboard')
                    ->with('error', 'Akun Anda belum diverifikasi oleh admin. Mohon tunggu proses verifikasi.');
            }
        }
        
        return $next($request);
    }
}

