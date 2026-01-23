<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Notifikasi;
use App\Models\Chat;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPesananController extends Controller
{
    /**
     * Tampilkan semua pesanan untuk admin
     */
    public function index()
    {
        $pesanan = Pesanan::with(['user', 'produk.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.pesanan.index', compact('pesanan'));
    }

    /**
     * ✅ KONFIRMASI PESANAN oleh Admin
     * - Ubah status pesanan menjadi 'diproses'
     * - Kirim notifikasi ke penjual
     * - Buat/cek chat admin ↔ penjual
     */
    public function konfirmasi($id)
    {
        try {
            $pesanan = Pesanan::with('produk.user')->findOrFail($id);
            
            // Validasi: hanya admin yang bisa konfirmasi
            if (Auth::user()->role !== 'admin') {
                return redirect()->back()->with('error', 'Hanya admin yang dapat mengkonfirmasi pesanan');
            }
            
            // Validasi: pesanan harus berstatus pending
            if ($pesanan->status !== 'pending') {
                return redirect()->back()->with('error', 'Pesanan ini sudah diproses');
            }
            
            // Ubah status pesanan
            $pesanan->update(['status' => 'diproses']);
            
            // Ambil penjual dari produk
            $penjual = $pesanan->produk->user;
            
            // ✅ KIRIM NOTIFIKASI KE PENJUAL
            Notifikasi::create([
                'user_id' => $penjual->id,
                'judul' => 'Produk Anda Dipesan',
                'pesan' => 'Pesanan #' . $pesanan->id . ' untuk produk "' . $pesanan->produk->nama_produk . '" siap diproses. Segera proses pesanan ini.',
                'tipe' => 'pesanan',
                'referensi_id' => $pesanan->id,
                'link' => route('penjual.pesanan.index'),
                'dibaca' => false
            ]);
            
            // Firebase push notification
            if ($penjual->fcm_token) {
                $firebaseService = app(FirebaseService::class);
                $firebaseService->sendNotification(
                    $penjual->fcm_token,
                    '🛒 Produk Anda Dipesan',
                    'Pesanan #' . $pesanan->id . ' untuk "' . $pesanan->produk->nama_produk . '" siap diproses',
                    ['type' => 'pesanan', 'pesanan_id' => $pesanan->id]
                );
            }
            
            // ✅ CEK/BUAT CHAT ADMIN ↔ PENJUAL
            $admin = Auth::user();
            
            // Cek apakah chat sudah ada
            $chat = Chat::where('admin_id', $admin->id)
                ->where('penjual_id', $penjual->id)
                ->first();
            
            // Jika belum ada, buat chat baru
            if (!$chat) {
                $chat = Chat::create([
                    'admin_id' => $admin->id,
                    'penjual_id' => $penjual->id,
                    'pesanan_id' => $pesanan->id
                ]);
                
                // Kirim notifikasi bahwa chat dibuat
                Notifikasi::create([
                    'user_id' => $penjual->id,
                    'judul' => 'Chat Baru Dibuat',
                    'pesan' => 'Admin membuat chat terkait pesanan #' . $pesanan->id,
                    'tipe' => 'chat',
                    'referensi_id' => $chat->id,
                    'link' => route('chat.show', $chat->id),
                    'dibaca' => false
                ]);
                
                // Firebase push notification
                if ($penjual->fcm_token) {
                    $firebaseService = app(FirebaseService::class);
                    $firebaseService->sendNotification(
                        $penjual->fcm_token,
                        '💬 Chat Baru Dibuat',
                        'Admin membuat chat terkait pesanan #' . $pesanan->id,
                        ['type' => 'chat', 'chat_id' => $chat->id]
                    );
                }
            }
            
            return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi dan notifikasi terkirim ke penjual');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Batalkan pesanan
     */
    public function batalkan($id)
    {
        try {
            $pesanan = Pesanan::with('produk')->findOrFail($id);
            
            if (Auth::user()->role !== 'admin') {
                return redirect()->back()->with('error', 'Hanya admin yang dapat membatalkan pesanan');
            }
            
            if (in_array($pesanan->status, ['selesai', 'dibatalkan'])) {
                return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan');
            }
            
            // Kembalikan stok
            $pesanan->produk->increment('stok', $pesanan->jumlah);
            
            // Ubah status
            $pesanan->update(['status' => 'dibatalkan']);
            
            // Kirim notifikasi ke pembeli jika ada
            if ($pesanan->user_id) {
                Notifikasi::create([
                    'user_id' => $pesanan->user_id,
                    'judul' => 'Pesanan Dibatalkan',
                    'pesan' => 'Pesanan #' . $pesanan->id . ' telah dibatalkan oleh admin',
                    'tipe' => 'pesanan',
                    'referensi_id' => $pesanan->id,
                    'link' => route('penjual.pesanan.index'),
                    'dibaca' => false
                ]);
                
                // Firebase push notification
                $pembeli = User::find($pesanan->user_id);
                if ($pembeli && $pembeli->fcm_token) {
                    $firebaseService = app(FirebaseService::class);
                    $firebaseService->sendNotification(
                        $pembeli->fcm_token,
                        '❌ Pesanan Dibatalkan',
                        'Pesanan #' . $pesanan->id . ' telah dibatalkan oleh admin',
                        ['type' => 'pesanan', 'pesanan_id' => $pesanan->id]
                    );
                }
            }
            
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
