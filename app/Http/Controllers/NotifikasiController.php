<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * ✅ TAMPILKAN SEMUA NOTIFIKASI USER
     */
    public function index()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Hitung notifikasi belum dibaca
        $unreadCount = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();
        
        return view('notifikasi.index', compact('notifikasis', 'unreadCount'));
    }

    /**
     * ✅ TANDAI NOTIFIKASI SEBAGAI SUDAH DIBACA
     */
    public function markAsRead($id)
    {
        try {
            $notifikasi = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $notifikasi->update(['dibaca' => true]);
            
            // Redirect sesuai tipe notifikasi
            if ($notifikasi->tipe === 'pesanan' && $notifikasi->referensi_id) {
                return redirect()->route('pesanan.detail', $notifikasi->referensi_id);
            }
            
            if ($notifikasi->tipe === 'chat' && $notifikasi->referensi_id) {
                return redirect()->route('chat.show', $notifikasi->referensi_id);
            }
            
            return redirect()->back();
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Notifikasi tidak ditemukan');
        }
    }

    /**
     * ✅ TANDAI SEMUA NOTIFIKASI SEBAGAI SUDAH DIBACA
     */
    public function markAllAsRead()
    {
        Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->update(['dibaca' => true]);
        
        return redirect()->back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
    }

    /**
     * ✅ HAPUS NOTIFIKASI
     */
    public function delete($id)
    {
        try {
            $notifikasi = Notifikasi::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();
            
            $notifikasi->delete();
            
            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus notifikasi');
        }
    }

    /**
     * ✅ AMBIL NOTIFIKASI BELUM DIBACA (untuk API/AJAX)
     */
    public function getUnread()
    {
        $notifikasis = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return response()->json([
            'count' => $notifikasis->count(),
            'data' => $notifikasis
        ]);
    }

    /**
     * ✅ GET NOTIFICATION COUNT (untuk badge counter real-time)
     */
    public function getCount()
    {
        $count = Notifikasi::where('user_id', Auth::id())
            ->where('dibaca', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
}
