<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\SupabaseService;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cek role - redirect jika bukan admin/penjual
        if (Auth::user()->role === 'user') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya admin dan penjual yang dapat mengelola produk.');
        }
        
        $user = Auth::user();
        
        // Admin bisa lihat semua produk, penjual hanya produknya sendiri
        if ($user->role === 'admin') {
            $produk = Produk::with(['user', 'kategori'])->latest()->paginate(10);
        } else {
            $produk = Produk::where('user_id', $user->id)
                           ->with('kategori')
                           ->latest()
                           ->paginate(10);
        }
        
        return view('produk.index', compact('produk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Hanya penjual yang bisa akses
        if (Auth::user()->role === 'admin') {
            return redirect()->route('produk.index')
                           ->with('error', 'Admin hanya dapat melihat produk, tidak dapat menambah produk.');
        }
        
        $kategori = Kategori::all();
        return view('produk.create', compact('kategori'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hanya penjual yang bisa akses
        if (Auth::user()->role === 'admin') {
            return redirect()->route('produk.index')
                           ->with('error', 'Admin hanya dapat melihat produk, tidak dapat menambah produk.');
        }
        
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomor_whatsapp' => 'required|string|regex:/^62[0-9]{9,13}$/|max:20',
            'status' => 'boolean'
        ], [
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'nama_produk.required' => 'Nama produk harus diisi',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'stok.required' => 'Stok harus diisi',
            'stok.integer' => 'Stok harus berupa angka',
            'stok.min' => 'Stok tidak boleh kurang dari 0',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Gambar harus format jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'nomor_whatsapp.required' => 'Nomor WhatsApp harus diisi',
            'nomor_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format 62xxxxx (tanpa tanda +)',
            'nomor_whatsapp.max' => 'Nomor WhatsApp terlalu panjang'
        ]);

        // Upload gambar jika ada
        if ($request->hasFile('gambar')) {
            $supabase = new SupabaseService();
            $uploadResult = $supabase->uploadFile($request->file('gambar'), 'produk');
            
            if ($uploadResult['success']) {
                $validated['gambar'] = $uploadResult['path'];
            } else {
                return back()->withErrors(['gambar' => $uploadResult['message']])->withInput();
            }
        }

        // Tambahkan user_id dari user yang login
        $validated['user_id'] = Auth::id();
        $validated['status'] = $request->has('status') ? 1 : 0;
        
        // Generate slug dari nama produk
        $slug = \Illuminate\Support\Str::slug($validated['nama_produk']);
        
        // Ensure unique slug
        $count = 1;
        $originalSlug = $slug;
        while (Produk::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        $validated['slug'] = $slug;

        Produk::create($validated);

        return redirect()->route('produk.index')
                       ->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with(['user', 'kategori'])->findOrFail($id);
        return view('produk.show', compact('produk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        
        // Admin tidak bisa edit
        if (Auth::user()->role === 'admin') {
            return redirect()->route('produk.index')
                           ->with('error', 'Admin hanya dapat melihat produk, tidak dapat mengedit produk.');
        }
        
        // Penjual hanya bisa edit produknya sendiri
        if ($produk->user_id !== Auth::id()) {
            return redirect()->route('produk.index')
                           ->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini');
        }
        
        $kategori = Kategori::all();
        return view('produk.edit', compact('produk', 'kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);
        
        // Admin tidak bisa update
        if (Auth::user()->role === 'admin') {
            return redirect()->route('produk.index')
                           ->with('error', 'Admin hanya dapat melihat produk, tidak dapat mengupdate produk.');
        }
        
        // Penjual hanya bisa update produknya sendiri
        if ($produk->user_id !== Auth::id()) {
            return redirect()->route('produk.index')
                           ->with('error', 'Anda tidak memiliki akses untuk mengupdate produk ini');
        }
        
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategoris,id',
            'nama_produk' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomor_whatsapp' => 'required|string|regex:/^62[0-9]{9,13}$/|max:20',
            'status' => 'boolean'
        ], [
            'kategori_id.required' => 'Kategori harus dipilih',
            'kategori_id.exists' => 'Kategori tidak valid',
            'nama_produk.required' => 'Nama produk harus diisi',
            'deskripsi.required' => 'Deskripsi harus diisi',
            'harga.required' => 'Harga harus diisi',
            'harga.numeric' => 'Harga harus berupa angka',
            'harga.min' => 'Harga tidak boleh kurang dari 0',
            'stok.required' => 'Stok harus diisi',
            'stok.integer' => 'Stok harus berupa angka',
            'stok.min' => 'Stok tidak boleh kurang dari 0',
            'gambar.image' => 'File harus berupa gambar',
            'gambar.mimes' => 'Gambar harus format jpeg, png, jpg, atau gif',
            'gambar.max' => 'Ukuran gambar maksimal 2MB',
            'nomor_whatsapp.required' => 'Nomor WhatsApp harus diisi',
            'nomor_whatsapp.regex' => 'Format nomor WhatsApp tidak valid. Gunakan format 62xxxxx (tanpa tanda +)',
            'nomor_whatsapp.max' => 'Nomor WhatsApp terlalu panjang'
        ]);

        // Upload gambar baru jika ada
        if ($request->hasFile('gambar')) {
            $supabase = new SupabaseService();
            
            // Hapus gambar lama jika ada
            if ($produk->gambar) {
                $supabase->deleteFile($produk->gambar);
            }
            
            // Upload gambar baru
            $uploadResult = $supabase->uploadFile($request->file('gambar'), 'produk');
            
            if ($uploadResult['success']) {
                $validated['gambar'] = $uploadResult['path'];
            } else {
                return back()->withErrors(['gambar' => $uploadResult['message']])->withInput();
            }
        }

        $validated['status'] = $request->has('status') ? 1 : 0;

        $produk->update($validated);

        return redirect()->route('produk.index')
                       ->with('success', 'Produk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);
        
        // Admin tidak bisa hapus
        if (Auth::user()->role === 'admin') {
            return redirect()->route('produk.index')
                           ->with('error', 'Admin hanya dapat melihat produk, tidak dapat menghapus produk.');
        }
        
        // Penjual hanya bisa hapus produknya sendiri
        if ($produk->user_id !== Auth::id()) {
            return redirect()->route('produk.index')
                           ->with('error', 'Anda tidak memiliki akses untuk menghapus produk ini');
        }
        
        // Hapus gambar jika ada
        if ($produk->gambar) {
            $supabase = new SupabaseService();
            $supabase->deleteFile($produk->gambar);
        }
        
        $produk->delete();
        
        return redirect()->route('produk.index')
                       ->with('success', 'Produk berhasil dihapus!');
    }
}
