<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Ambil produk untuk flash sale (produk terbaru)
        $flashSaleProducts = Produk::with(['kategori', 'user'])
            ->where('status', true)
            ->latest()
            ->take(8)
            ->get();
        
        // Ambil semua produk untuk rekomendasi
        $recommendedProducts = Produk::with(['kategori', 'user'])
            ->where('status', true)
            ->latest()
            ->get();
        
        // Ambil semua kategori
        $categories = Kategori::all();
        
        return view('index', compact('flashSaleProducts', 'recommendedProducts', 'categories'));
    }

    /**
     * Menampilkan halaman detail produk berdasarkan slug (SEO-friendly)
     */
    public function showProduk($slug)
    {
        $produk = Produk::with(['kategori', 'user'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();
        
        // Ambil produk terkait dari kategori yang sama
        $relatedProducts = Produk::with(['kategori', 'user'])
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->where('status', true)
            ->limit(4)
            ->get();
        
        return view('produk.detail', compact('produk', 'relatedProducts'));
    }

    /**
     * Menampilkan halaman detail produk berdasarkan ID
     * Slug hanya untuk SEO, sistem baca dari ID
     */
    public function detailProduk($id, $slug = null)
    {
        $produk = Produk::with(['kategori', 'user'])
            ->where('id', $id)
            ->where('status', true)
            ->firstOrFail();
        
        // Redirect ke URL yang benar jika slug salah atau tidak ada
        $correctSlug = \Illuminate\Support\Str::slug($produk->nama_produk);
        if ($slug !== $correctSlug) {
            return redirect()->route('produk.detail', ['id' => $id, 'slug' => $correctSlug], 301);
        }
        
        // Ambil produk terkait dari kategori yang sama
        $relatedProducts = Produk::with(['kategori', 'user'])
            ->where('kategori_id', $produk->kategori_id)
            ->where('id', '!=', $produk->id)
            ->where('status', true)
            ->limit(4)
            ->get();
        
        return view('produk.detail', compact('produk', 'relatedProducts'));
    }
}
