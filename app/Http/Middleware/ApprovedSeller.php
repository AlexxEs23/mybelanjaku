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
        
        // Jika bukan penjual, lanjutkan
        if ($user->role !== 'penjual') {
            return $next($request);
        }
        
        // Jika penjual tapi belum approved
        if ($user->status_approval !== 'approved') {
            return redirect('/dashboard')->with('error', 'Akun Anda masih menunggu persetujuan admin. Anda belum dapat mengelola produk.');
        }
        
        return $next($request);
    }
}
