<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ApprovedSeller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Pastikan user sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }
        
        $user = Auth::user();
        
        // Admin bisa akses semua
        if ($user->role === 'admin') {
            return $next($request);
        }
        
        // Jika bukan penjual dan bukan admin, redirect
        if ($user->role !== 'penjual') {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
        }
        
        // Jika penjual tapi belum approved, tampilkan pesan
        if ($user->status_approval === 'pending') {
            return redirect('/dashboard')->with('info', 'Akun Anda masih menunggu persetujuan admin. Anda belum dapat mengelola produk.');
        }
        
        // Jika penjual ditolak
        if ($user->status_approval === 'rejected') {
            return redirect('/dashboard')->with('error', 'Akun Anda tidak disetujui. Silakan hubungi admin untuk informasi lebih lanjut.');
        }
        
        return $next($request);
    }
}
