<?php

namespace App\Http\Controllers;

use App\Models\ProfileUmkm;
use App\Models\Kategori;
use App\Models\Notifikasi;
use App\Models\User;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileUmkmController extends Controller
{
    /**
     * Tampilkan form pendaftaran UMKM (langsung di index)
     */
    public function index()
    {
        // Cek apakah user sudah punya profil UMKM
        $existingProfile = ProfileUmkm::where('user_id', Auth::id())->first();
        
        // Ambil semua kategori untuk dropdown
        $kategoris = Kategori::all();
        
        return view('profile.index', compact('kategoris', 'existingProfile'));
    }

    /**
     * Simpan data pendaftaran UMKM
     */
    public function store(Request $request)
    {
        // Cek apakah user sudah punya profil UMKM
        if (ProfileUmkm::where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Anda sudah memiliki profil UMKM yang terdaftar.');
        }

        $validatedData = $request->validate([
            'nama_umkm' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi_umkm' => 'nullable|string',
            'tahun_berdiri' => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'no_hp' => 'required|string|max:15',
            'nama_pemilik' => 'required|string|max:255',
            'wilayah' => 'required|string|max:255',
        ], [
            'nama_umkm.required' => 'Nama UMKM wajib diisi',
            'kategori_id.required' => 'Kategori wajib dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'tahun_berdiri.required' => 'Tahun berdiri wajib diisi',
            'tahun_berdiri.digits' => 'Tahun berdiri harus 4 digit',
            'tahun_berdiri.min' => 'Tahun berdiri tidak valid',
            'tahun_berdiri.max' => 'Tahun berdiri tidak boleh lebih dari tahun ini',
            'no_hp.required' => 'Nomor HP wajib diisi',
            'nama_pemilik.required' => 'Nama pemilik wajib diisi',
            'wilayah.required' => 'Wilayah wajib diisi',
        ]);

        // Tambahkan user_id otomatis dan status pending
        $validatedData['user_id'] = Auth::id();
        $validatedData['status_verifikasi'] = 'pending';

        $profile = ProfileUmkm::create($validatedData);

        // Kirim notifikasi ke semua admin
        $admins = User::where('role', 'admin')->get();
        $firebaseService = app(FirebaseService::class);
        
        foreach ($admins as $admin) {
            // Notifikasi database
            Notifikasi::create([
                'user_id' => $admin->id,
                'judul' => 'Pendaftaran Penjual Baru',
                'pesan' => 'UMKM "' . $profile->nama_umkm . '" telah mendaftar dan menunggu verifikasi Anda.',
                'tipe' => 'penjual',
                'referensi_id' => $profile->id,
                'link' => route('seller.approval'),
                'dibaca' => false
            ]);
            
            // Push notification via Firebase
            if ($admin->fcm_token) {
                $firebaseService->sendNotification(
                    $admin->fcm_token,
                    'Pendaftaran Penjual Baru',
                    'UMKM "' . $profile->nama_umkm . '" menunggu verifikasi.',
                    ['type' => 'penjual', 'profile_id' => $profile->id]
                );
            }
        }

        return redirect()->route('profile-umkm.index')
            ->with('success', 'Pendaftaran UMKM berhasil! Data Anda sedang dalam proses verifikasi oleh admin.');
    }
}

