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
     * Show form for seller to input resi number
     */
    public function showResiForm($id)
    {
        $pesanan = Pesanan::with(['produk', 'user'])->findOrFail($id);
        
        // Check if this order belongs to seller's product
        if ($pesanan->produk->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke pesanan ini');
        }

        // Check if order status is 'diproses' or 'di proses'
        if (!in_array($pesanan->status, ['diproses', 'di proses'])) {
            return redirect()->route('penjual.pesanan.index')
                ->with('error', 'Pesanan ini tidak dapat diproses. Status: ' . $pesanan->status);
        }

        return view('penjual.pesanan.resi-form', compact('pesanan'));
    }

    /**
     * Update status by Penjual (to 'dikirim') with resi number
     */
    public function updateStatusByPenjual(Request $request, $id)
    {
        $request->validate([
            'resi' => 'required|string|max:100',
        ]);

        $pesanan = Pesanan::findOrFail($id);
        
        // Check if this order belongs to seller's product
        if ($pesanan->produk->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        $pesanan->status = 'dikirim';
        $pesanan->resi = $request->resi;
        $pesanan->save();

        return redirect()->route('penjual.pesanan.index')
            ->with('success', 'Pesanan berhasil dikirim dengan nomor resi: ' . $request->resi);
    }

    /**
     * Update status by Pembeli (to 'selesai')
     */
    public function updateStatusByPembeli($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Check if this order belongs to the user
        if ($pesanan->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini');
        }

        $pesanan->status = 'selesai';
        $pesanan->save();

        return redirect()->back()->with('success', 'Terima kasih! Pesanan berhasil dikonfirmasi sebagai diterima.');
    }
}
