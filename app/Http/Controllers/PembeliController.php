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
        
        // Get monthly spending data for chart (last 6 months)
        $monthlySpending = [];
        $monthlyOrders = [];
        $monthLabels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthLabels[] = $date->format('M Y');
            
            $spending = Pesanan::where('user_id', Auth::id())
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'selesai')
                ->sum('total');
            $monthlySpending[] = $spending;
            
            $orders = Pesanan::where('user_id', Auth::id())
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $monthlyOrders[] = $orders;
        }
        
        // Get order status distribution
        $statusData = [
            'menunggu' => Pesanan::where('user_id', Auth::id())->where('status', 'menunggu')->count(),
            'diproses' => Pesanan::where('user_id', Auth::id())->where('status', 'diproses')->count(),
            'dikirim' => Pesanan::where('user_id', Auth::id())->where('status', 'dikirim')->count(),
            'selesai' => Pesanan::where('user_id', Auth::id())->where('status', 'selesai')->count(),
            'dibatalkan' => Pesanan::where('user_id', Auth::id())->where('status', 'dibatalkan')->count(),
        ];
        
        return view('pembeli.dashboard', compact(
            'user', 
            'recentOrders', 
            'totalOrders', 
            'activeOrders', 
            'completedOrders', 
            'totalSpent',
            'monthlySpending',
            'monthlyOrders',
            'monthLabels',
            'statusData'
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
