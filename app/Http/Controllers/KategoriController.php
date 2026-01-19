<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $kategoris = Kategori::orderBy('nama_kategori', 'asc')->paginate(10);
        
        // Tentukan view berdasarkan role
        if ($user->role === 'admin') {
            return view('admin.kategori.index', compact('kategoris'));
        } elseif ($user->role === 'penjual') {
            return view('penjual.kategori.index', compact('kategoris'));
        }
        
        abort(403, 'Unauthorized action.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Tentukan view berdasarkan role
        if ($user->role === 'admin') {
            return view('admin.kategori.create');
        } elseif ($user->role === 'penjual') {
            return view('penjual.kategori.create');
        }
        
        abort(403, 'Unauthorized action.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'nama_kategori.unique' => 'Kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
        ]);

        Kategori::create($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $kategori = Kategori::findOrFail($id);
        
        // Tentukan view berdasarkan role
        if ($user->role === 'admin') {
            return view('admin.kategori.edit', compact('kategori'));
        } elseif ($user->role === 'penjual') {
            return view('penjual.kategori.edit', compact('kategori'));
        }
        
        abort(403, 'Unauthorized action.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kategori = Kategori::findOrFail($id);
        
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:255|unique:kategoris,nama_kategori,' . $id,
        ], [
            'nama_kategori.required' => 'Nama kategori harus diisi',
            'nama_kategori.unique' => 'Kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
        ]);

        $kategori->update($validated);

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = Kategori::findOrFail($id);
        
        // Cek apakah kategori masih digunakan oleh produk
        if ($kategori->produks()->count() > 0) {
            return redirect()->route('kategori.index')->with('error', 'Kategori tidak dapat dihapus karena masih digunakan oleh ' . $kategori->produks()->count() . ' produk!');
        }
        
        $kategori->delete();

        return redirect()->route('kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
