<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RatingController extends Controller
{
    /**
     * Store a new rating
     */
    public function store(Request $request, Produk $produk)
    {
        // 1. VALIDASI: User harus login sebagai USER (bukan admin/penjual)
        if (Auth::user()->role !== 'user') {
            return back()->with('error', 'Hanya pembeli yang dapat memberikan rating.');
        }

        // 2. VALIDASI: User harus sudah membeli produk ini
        if (!Auth::user()->hasPurchasedProduct($produk->id)) {
            return back()->with('error', 'Anda harus membeli produk ini terlebih dahulu untuk memberikan rating.');
        }

        // 3. VALIDASI: User belum pernah memberi rating untuk produk ini
        if (Auth::user()->hasRatedProduct($produk->id)) {
            return back()->with('error', 'Anda sudah memberikan rating untuk produk ini. Anda dapat mengedit rating Anda.');
        }

        // 4. VALIDASI: Input rating dan review
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        // 5. SIMPAN RATING
        try {
            Rating::create([
                'user_id' => Auth::id(),
                'produk_id' => $produk->id,
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]);

            return back()->with('success', 'Terima kasih! Rating Anda berhasil disimpan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menyimpan rating.');
        }
    }

    /**
     * Update existing rating
     */
    public function update(Request $request, Rating $rating)
    {
        // 1. VALIDASI: User hanya bisa update rating miliknya sendiri
        if ($rating->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk mengedit rating ini.');
        }

        // 2. VALIDASI: Input rating dan review
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string', 'max:1000'],
        ]);

        // 3. UPDATE RATING
        try {
            $rating->update([
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]);

            return back()->with('success', 'Rating Anda berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui rating.');
        }
    }

    /**
     * Delete rating
     */
    public function destroy(Rating $rating)
    {
        // 1. VALIDASI: User hanya bisa hapus rating miliknya sendiri
        if ($rating->user_id !== Auth::id()) {
            return back()->with('error', 'Anda tidak memiliki akses untuk menghapus rating ini.');
        }

        // 2. HAPUS RATING
        try {
            $rating->delete();
            return back()->with('success', 'Rating Anda berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus rating.');
        }
    }

    /**
     * Show form untuk memberi/edit rating
     * Bisa juga langsung di halaman detail produk
     */
    public function showRatingForm(Produk $produk)
    {
        $user = Auth::user();

        // Cek eligibility
        if ($user->role !== 'user') {
            abort(403, 'Hanya pembeli yang dapat memberikan rating.');
        }

        if (!$user->hasPurchasedProduct($produk->id)) {
            abort(403, 'Anda belum membeli produk ini.');
        }

        // Get existing rating jika ada
        $existingRating = $user->getRatingForProduct($produk->id);

        return view('ratings.form', compact('produk', 'existingRating'));
    }

    /**
     * API endpoint untuk mendapatkan rating statistics produk
     * (Optional, untuk AJAX requests)
     */
    public function getRatingStats(Produk $produk)
    {
        return response()->json([
            'average' => $produk->averageRating(),
            'total' => $produk->totalRatings(),
            'distribution' => $produk->ratingDistribution(),
        ]);
    }

    /**
     * Load more ratings dengan pagination
     * (Optional, untuk infinite scroll)
     */
    public function loadMoreRatings(Request $request, Produk $produk)
    {
        $ratings = $produk->ratings()
            ->with('user:id,name')
            ->latest()
            ->paginate(10);

        return response()->json($ratings);
    }
}
