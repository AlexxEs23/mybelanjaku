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
            ->take(5)
            ->get();
        
        // Get order statistics
        $totalOrders = Pesanan::where('user_id', Auth::id())->count();
        
        // Status pesanan (pending, diproses, dikirim, selesai, dibatalkan sesuai migration)
        $pendingOrders = Pesanan::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->count();
            
        $diprosesOrders = Pesanan::where('user_id', Auth::id())
            ->where('status', 'diproses')
            ->count();
            
        $dikirimOrders = Pesanan::where('user_id', Auth::id())
            ->where('status', 'dikirim')
            ->count();
            
        $completedOrders = Pesanan::where('user_id', Auth::id())
            ->where('status', 'selesai')
            ->count();
        
        // Total pengeluaran - SEMUA pesanan (termasuk yang sedang diproses)
        // Ini yang akan update real-time sesuai dengan setiap pembelian baru
        $totalSpent = Pesanan::where('user_id', Auth::id())
            ->whereNotIn('status', ['dibatalkan']) // Exclude hanya yang dibatalkan
            ->sum('total');
        
        return view('pembeli.dashboard', compact(
            'user', 
            'recentOrders', 
            'totalOrders', 
            'pendingOrders',
            'diprosesOrders',
            'dikirimOrders',
            'completedOrders', 
            'totalSpent'
        ));
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
