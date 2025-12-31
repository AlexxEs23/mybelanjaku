<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembeliController extends Controller
{
    /**
     * Display pembeli dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        // Get latest orders for preview
        $recentOrders = Pesanan::with(['produk.user', 'produk.kategori'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        
        // Get order statistics
        $totalOrders = Pesanan::where('user_id', Auth::id())->count();
        $activeOrders = Pesanan::where('user_id', Auth::id())
            ->whereIn('status', ['menunggu', 'diproses', 'dikirim'])
            ->count();
        $completedOrders = Pesanan::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->count();
        $totalSpent = Pesanan::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->sum('total');
        
        return view('pembeli.dashboard', compact('user', 'recentOrders', 'totalOrders', 'activeOrders', 'completedOrders', 'totalSpent'));
    }
    
    /**
     * Display all orders for pembeli
     */
    public function pesanan()
    {
        $pesanan = Pesanan::with(['produk.user', 'produk.kategori'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('pembeli.pesanan.index', compact('pesanan'));
    }
}
