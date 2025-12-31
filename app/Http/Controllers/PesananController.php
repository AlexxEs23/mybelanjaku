<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PesananController extends Controller
{
    /**
     * Display all orders for admin
     */
    public function adminIndex()
    {
        $pesanan = Pesanan::with(['user', 'produk.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.pesanan.index', compact('pesanan'));
    }

    /**
     * Display orders for seller (only their products)
     */
    public function penjualIndex()
    {
        $pesanan = Pesanan::with(['user', 'produk'])
            ->whereHas('produk', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('penjual.pesanan.index', compact('pesanan'));
    }

    /**
     * Display orders for pembeli (user's own orders)
     */
    public function pembeliIndex()
    {
        $pesanan = Pesanan::with(['produk.user'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('pembeli.pesanan.index', compact('pesanan'));
    }

    /**
     * Update status by Admin (to 'di proses')
     */
    public function updateStatusByAdmin($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->status = 'di proses';
        $pesanan->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Di Proses');
    }

    /**
     * Update status by Penjual (to 'di kirim')
     */
    public function updateStatusByPenjual($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Check if this order belongs to seller's product
        if ($pesanan->produk->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        $pesanan->status = 'di kirim';
        $pesanan->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Di Kirim');
    }

    /**
     * Update status by Pembeli (to 'di terima')
     */
    public function updateStatusByPembeli($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Check if this order belongs to the user
        if ($pesanan->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        $pesanan->status = 'di terima';
        $pesanan->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diubah menjadi Di Terima');
    }
}
