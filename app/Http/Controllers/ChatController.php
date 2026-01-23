<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\PesanChat;
use App\Models\Notifikasi;
use App\Events\MessageSent;
use App\Events\NotificationSent;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * ✅ TAMPILKAN DAFTAR CHAT
     * - Admin: chat dengan admin_id = user_id
     * - Penjual: chat dengan penjual_id = user_id
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            // Admin melihat semua chat yang dia handle
            $chats = Chat::with(['penjual', 'pesanan.produk', 'lastMessage'])
                ->where('admin_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->paginate(20);
                
        } elseif ($user->role === 'penjual') {
            // Penjual melihat chat yang dia ikuti
            $chats = Chat::with(['admin', 'pesanan.produk', 'lastMessage'])
                ->where('penjual_id', $user->id)
                ->orderBy('updated_at', 'desc')
                ->paginate(20);
                
        } else {
            return redirect()->back()->with('error', 'Akses ditolak. Fitur chat hanya untuk admin dan penjual');
        }
        
        return view('chat.index', compact('chats'));
    }

    /**
     * ✅ BUKA CHAT DAN TAMPILKAN PESAN
     * - Pastikan user adalah admin atau penjual dari chat tersebut
     * - Tandai pesan lawan bicara sebagai sudah dibaca
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $chat = Chat::with(['admin', 'penjual', 'pesanan.produk'])->findOrFail($id);
        
        // Validasi akses: hanya admin atau penjual dari chat ini yang boleh buka
        if ($user->role === 'admin' && $chat->admin_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke chat ini');
        }
        
        if ($user->role === 'penjual' && $chat->penjual_id !== $user->id) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke chat ini');
        }
        
        // Ambil semua pesan
        $messages = PesanChat::with('pengirim')
            ->where('chat_id', $chat->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Tentukan partner (lawan bicara)
        $partner = $user->role === 'admin' ? $chat->penjual : $chat->admin;
        
        // ✅ TANDAI PESAN LAWAN BICARA SEBAGAI SUDAH DIBACA
        PesanChat::where('chat_id', $chat->id)
            ->where('pengirim_id', '!=', $user->id)
            ->where('dibaca', false)
            ->update(['dibaca' => true]);
        
        return view('chat.show', compact('chat', 'messages', 'partner'));
    }

    /**
     * ✅ KIRIM PESAN DALAM CHAT
     * - Simpan pesan ke database
     * - Kirim notifikasi ke penerima
     */
    public function kirimPesan(Request $request, $id)
    {
        try {
            $request->validate([
                'pesan' => 'required|string|max:1000'
            ]);
            
            $user = Auth::user();
            $chat = Chat::findOrFail($id);
            
            // Validasi akses
            if ($user->role === 'admin' && $chat->admin_id !== $user->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses');
            }
            
            if ($user->role === 'penjual' && $chat->penjual_id !== $user->id) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses');
            }
            
            // Simpan pesan
            $pesanChat = PesanChat::create([
                'chat_id' => $chat->id,
                'pengirim_id' => $user->id,
                'pesan' => $request->pesan,
                'dibaca' => false
            ]);
            
            // 🔥 BROADCAST EVENT REAL-TIME
            broadcast(new MessageSent($pesanChat))->toOthers();
            
            // Update timestamp chat
            $chat->touch();
            
            // ✅ TENTUKAN PENERIMA PESAN
            $penerima = null;
            $senderRole = '';
            
            if ($user->role === 'admin') {
                // Jika pengirim admin, penerima adalah penjual
                $penerima = $chat->penjual;
                $senderRole = 'Admin';
            } elseif ($user->role === 'penjual') {
                // Jika pengirim penjual, penerima adalah admin
                $penerima = $chat->admin;
                $senderRole = 'Penjual';
            }
            
            // ✅ KIRIM NOTIFIKASI KE PENERIMA
            if ($penerima) {
                $notification = Notifikasi::create([
                    'user_id' => $penerima->id,
                    'judul' => 'Pesan Baru dari ' . $senderRole,
                    'pesan' => $user->name . ': ' . \Illuminate\Support\Str::limit($request->pesan, 50),
                    'tipe' => 'chat',
                    'referensi_id' => $chat->id,
                    'link' => route('chat.show', $chat->id),
                    'dibaca' => false
                ]);
                
                // 🔥 BROADCAST NOTIFICATION REAL-TIME
                broadcast(new NotificationSent($notification))->toOthers();
                
                // Firebase push notification
                if ($penerima->fcm_token) {
                    $firebaseService = app(FirebaseService::class);
                    $firebaseService->sendNotification(
                        $penerima->fcm_token,
                        '💬 Pesan Baru dari ' . $senderRole,
                        $user->name . ': ' . \Illuminate\Support\Str::limit($request->pesan, 50),
                        ['type' => 'chat', 'chat_id' => $chat->id]
                    );
                }
            }
            
            return redirect()->back()->with('success', 'Pesan berhasil dikirim');
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim pesan: ' . $e->getMessage());
        }
    }

    /**
     * ✅ BUAT CHAT BARU (Optional - jika admin ingin buat chat manual)
     */
    public function create(Request $request)
    {
        try {
            $request->validate([
                'penjual_id' => 'required|exists:users,id',
                'pesanan_id' => 'nullable|exists:pesanans,id'
            ]);
            
            $admin = Auth::user();
            
            if ($admin->role !== 'admin') {
                return redirect()->back()->with('error', 'Hanya admin yang dapat membuat chat');
            }
            
            // Cek apakah chat sudah ada
            $existingChat = Chat::where('admin_id', $admin->id)
                ->where('penjual_id', $request->penjual_id)
                ->first();
            
            if ($existingChat) {
                return redirect()->route('chat.show', $existingChat->id)
                    ->with('info', 'Chat sudah ada');
            }
            
            // Buat chat baru
            $chat = Chat::create([
                'admin_id' => $admin->id,
                'penjual_id' => $request->penjual_id,
                'pesanan_id' => $request->pesanan_id
            ]);
            
            // Kirim notifikasi ke penjual
            $notifikasi = Notifikasi::create([
                'user_id' => $request->penjual_id,
                'judul' => 'Chat Baru',
                'pesan' => 'Admin membuat chat dengan Anda',
                'tipe' => 'chat',
                'referensi_id' => $chat->id,
                'is_read' => false
            ]);
            
            // Firebase push notification
            $penjual = \App\Models\User::find($request->penjual_id);
            if ($penjual && $penjual->fcm_token) {
                $firebaseService = app(FirebaseService::class);
                $firebaseService->sendNotification(
                    $penjual->fcm_token,
                    '💬 Chat Baru',
                    'Admin membuat chat dengan Anda',
                    ['type' => 'chat', 'chat_id' => $chat->id]
                );
            }
            
            return redirect()->route('chat.show', $chat->id)
                ->with('success', 'Chat berhasil dibuat');
                
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuat chat: ' . $e->getMessage());
        }
    }
}
