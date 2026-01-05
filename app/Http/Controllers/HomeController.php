<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Ambil produk untuk flash sale (produk terbaru)
        $flashSaleProducts = Produk::with(['kategori', 'user'])
            ->where('status', true)
            ->latest()
            ->take(8)
            ->get();
        
        // Query builder untuk rekomendasi dengan filter dan sorting
        $query = Produk::with(['kategori', 'user'])
            ->where('status', true);
        
        // Filter by category
        if ($request->has('kategori') && $request->kategori != 'all') {
            $query->where('kategori_id', $request->kategori);
        }
        
        // Sorting
        $sort = $request->get('sort', 'terbaru');
        switch ($sort) {
            case 'terlaris':
                // Sort by total ratings/reviews (most popular)
                $query->withCount('ratings')
                      ->orderBy('ratings_count', 'desc');
                break;
            case 'terbaru':
                $query->latest();
                break;
            case 'termurah':
                $query->orderBy('harga', 'asc');
                break;
            case 'termahal':
                $query->orderBy('harga', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $recommendedProducts = $query->get();
        
        // Ambil semua kategori
        $categories = Kategori::all();
        
        return view('index', compact('flashSaleProducts', 'recommendedProducts', 'categories'));
    }
    
    /**
     * Handle search functionality
     */
    public function search(Request $request)
    {
        $query = $request->input('q');
        $sort = $request->get('sort', 'terbaru');
        
        if (empty($query)) {
            return redirect()->route('home');
        }
        
        // Build search query
        $productsQuery = Produk::with(['kategori', 'user'])
            ->where('status', true)
            ->where(function($q) use ($query) {
                $q->where('nama_produk', 'LIKE', "%{$query}%")
                  ->orWhere('deskripsi', 'LIKE', "%{$query}%")
                  ->orWhereHas('kategori', function($q) use ($query) {
                      $q->where('nama_kategori', 'LIKE', "%{$query}%");
                  });
            });
        
        // Apply sorting
        switch ($sort) {
            case 'terlaris':
                $productsQuery->withCount('ratings')
                              ->orderBy('ratings_count', 'desc');
                break;
            case 'terbaru':
                $productsQuery->latest();
                break;
            case 'termurah':
                $productsQuery->orderBy('harga', 'asc');
                break;
            case 'termahal':
                $productsQuery->orderBy('harga', 'desc');
                break;
            default:
                $productsQuery->latest();
        }
        
        $products = $productsQuery->paginate(20);
        $categories = Kategori::all();
        
        return view('search.results', compact('products', 'query', 'categories'));
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
