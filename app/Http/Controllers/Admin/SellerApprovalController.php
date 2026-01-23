<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileUmkm;
use App\Models\Notifikasi;
use App\Services\FirebaseService;
use Illuminate\Http\Request;

class SellerApprovalController extends Controller
{
    public function index()
    {
        $pendingSellers = ProfileUmkm::with(['user', 'kategori'])
            ->where('status_verifikasi', 'pending')
            ->latest()
            ->get();
            
        $approvedSellers = ProfileUmkm::with(['user', 'kategori'])
            ->where('status_verifikasi', 'verified')
            ->latest()
            ->get();
            
        $rejectedSellers = ProfileUmkm::with(['user', 'kategori'])
            ->where('status_verifikasi', 'rejected')
            ->latest()
            ->get();
        
        return view('admin.seller-approval', compact('pendingSellers', 'approvedSellers', 'rejectedSellers'));
    }
    
    public function approve($id)
    {
        $profileUmkm = ProfileUmkm::with('user')->findOrFail($id);
        
        $profileUmkm->update([
            'status_verifikasi' => 'verified'
        ]);
        
        // Update role user jadi penjual jika masih user biasa
        if ($profileUmkm->user->role === 'user') {
            $profileUmkm->user->update([
                'role' => 'penjual',
                'status_approval' => 'approved'
            ]);
        }
        
        // Kirim notifikasi ke penjual
        $firebaseService = app(FirebaseService::class);
        
        // Notifikasi database
        Notifikasi::create([
            'user_id' => $profileUmkm->user_id,
            'judul' => '✅ UMKM Anda Disetujui!',
            'pesan' => 'Selamat! UMKM "' . $profileUmkm->nama_umkm . '" telah diverifikasi. Anda sekarang dapat mulai berjualan dan upload produk.',
            'tipe' => 'penjual',
            'referensi_id' => $profileUmkm->id,
            'link' => route('dashboard'),
            'dibaca' => false
        ]);
        
        // Push notification via Firebase
        if ($profileUmkm->user->fcm_token) {
            $firebaseService->sendNotification(
                $profileUmkm->user->fcm_token,
                '✅ UMKM Anda Disetujui!',
                'UMKM "' . $profileUmkm->nama_umkm . '" telah diverifikasi. Mulai berjualan sekarang!',
                ['type' => 'penjual_approved', 'profile_id' => $profileUmkm->id]
            );
        }
        
        return back()->with('success', 'UMKM ' . $profileUmkm->nama_umkm . ' berhasil diverifikasi!');
    }
    
    public function reject($id)
    {
        $profileUmkm = ProfileUmkm::with('user')->findOrFail($id);
        
        $profileUmkm->update([
            'status_verifikasi' => 'rejected'
        ]);
        
        // Kirim notifikasi ke penjual
        $firebaseService = app(FirebaseService::class);
        
        // Notifikasi database
        Notifikasi::create([
            'user_id' => $profileUmkm->user_id,
            'judul' => '❌ Pendaftaran UMKM Ditolak',
            'pesan' => 'Maaf, pendaftaran UMKM "' . $profileUmkm->nama_umkm . '" tidak dapat diverifikasi. Silakan hubungi admin untuk informasi lebih lanjut.',
            'tipe' => 'penjual',
            'referensi_id' => $profileUmkm->id,
            'link' => route('profile-umkm.index'),
            'dibaca' => false
        ]);
        
        // Push notification via Firebase
        if ($profileUmkm->user->fcm_token) {
            $firebaseService->sendNotification(
                $profileUmkm->user->fcm_token,
                '❌ Pendaftaran UMKM Ditolak',
                'Pendaftaran UMKM "' . $profileUmkm->nama_umkm . '" tidak dapat diverifikasi.',
                ['type' => 'penjual_rejected', 'profile_id' => $profileUmkm->id]
            );
        }
        
        return back()->with('success', 'UMKM ' . $profileUmkm->nama_umkm . ' ditolak.');
    }
}

