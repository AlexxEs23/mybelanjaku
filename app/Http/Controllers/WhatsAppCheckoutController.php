<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppCheckoutController extends Controller
{
    /**
     * Show form for WhatsApp checkout
     */
    public function show($id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);
        
        // Check if product is available
        if ($produk->stok <= 0) {
            return redirect()->back()->with('error', 'Produk tidak tersedia atau stok habis.');
        }
        
        if (!$produk->status) {
            return redirect()->back()->with('error', 'Produk sedang tidak aktif.');
        }
        
        // Check if product has WhatsApp number
        if (empty($produk->nomor_whatsapp)) {
            return redirect()->back()->with('error', 'Produk ini belum memiliki nomor WhatsApp. Silakan hubungi admin.');
        }
        
        return view('whatsapp.formCheckout', compact('produk'));
    }

    /**
     * Process checkout via WhatsApp and reduce stock
     */
    public function checkout(Request $request)
    {
        try {
            $request->validate([
                'produk_id' => 'required|exists:produks,id',
                'jumlah' => 'required|integer|min:1',
                'nama_penerima' => 'required|string|max:255',
                'no_hp' => 'required|string|max:20',
                'alamat' => 'required|string|max:500',
            ]);

            $produk = Produk::findOrFail($request->produk_id);
            
            // Check if product has WhatsApp number
            if (empty($produk->nomor_whatsapp)) {
                return redirect()->back()->with('error', 'Produk ini belum memiliki nomor WhatsApp. Silakan hubungi admin.');
            }
            
            // Check if stock is sufficient
            if ($produk->stok < $request->jumlah) {
                return redirect()->back()->with('error', 'Stok tidak mencukupi. Stok tersedia: ' . $produk->stok);
            }

            // Reduce stock
            $produk->stok -= $request->jumlah;
            $produk->save();

            // Calculate total price
            $total = $produk->harga * $request->jumlah;

            // Get user name for notifications and messages
            $userName = Auth::user()->name;

            // Save order to database
            $pesanan = Pesanan::create([
                'user_id' => Auth::id(),
                'produk_id' => $produk->id,
                'jumlah' => $request->jumlah,
                'harga_satuan' => $produk->harga,
                'total' => $total,
                'status' => 'pending',
                'catatan' => 'Pesanan via WhatsApp',
                'nama_penerima' => $request->nama_penerima,
                'no_hp' => $request->no_hp,
                'alamat' => $request->alamat
            ]);

            // ✅ NOTIFIKASI KE ADMIN - Pesanan Baru
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notifikasi::create([
                    'user_id' => $admin->id,
                    'judul' => 'Pesanan Baru #' . $pesanan->id,
                    'pesan' => 'Ada pesanan baru dari ' . $userName . ' untuk produk ' . $produk->nama_produk,
                    'tipe' => 'pesanan',
                    'referensi_id' => $pesanan->id,
                    'link' => route('admin.pesanan.index'),
                    'dibaca' => false
                ]);
            }

            // Build WhatsApp message
            $message = "Halo, saya {$userName} ingin memesan:\n\n";
            $message .= "*Produk:* {$produk->nama_produk}\n";
            $message .= "*Jumlah:* {$request->jumlah} unit\n";
            $message .= "*Harga Satuan:* Rp " . number_format($produk->harga, 0, ',', '.') . "\n";
            $message .= "*Total:* Rp " . number_format($total, 0, ',', '.') . "\n\n";
            $message .= "*📍 Informasi Pengiriman:*\n";
            $message .= "*Nama Penerima:* {$request->nama_penerima}\n";
            $message .= "*No HP:* {$request->no_hp}\n";
            $message .= "*Alamat:* {$request->alamat}\n\n";
            $message .= "Mohon info lebih lanjut untuk proses pemesanan. Terima kasih!";

            // URL encode the message
            $encodedMessage = urlencode($message);
            
            // WhatsApp URL - use seller's WhatsApp number from product
            $whatsappUrl = "https://wa.me/{$produk->nomor_whatsapp}?text={$encodedMessage}";

            // Redirect with success message and WhatsApp URL
            return redirect()->away($whatsappUrl);
            
        } catch (\Exception $e) {
            // Restore stock if there's an error
            if (isset($produk)) {
                $produk->stok += $request->jumlah;
                $produk->save();
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
