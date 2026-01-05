<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Produk extends Model
{
    protected $table = 'produks';
    
    protected $fillable = [
        'user_id',
        'kategori_id',
        'nama_produk',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'gambar',
        'status',
        'nomor_whatsapp'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'stok' => 'integer',
        'status' => 'boolean',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug saat produk dibuat
        static::creating(function ($produk) {
            if (empty($produk->slug)) {
                $produk->slug = Str::slug($produk->nama_produk);
                
                // Ensure unique slug
                $count = 1;
                while (static::where('slug', $produk->slug)->exists()) {
                    $produk->slug = Str::slug($produk->nama_produk) . '-' . $count;
                    $count++;
                }
            }
        });

        // Update slug saat nama produk diupdate
        static::updating(function ($produk) {
            if ($produk->isDirty('nama_produk')) {
                $produk->slug = Str::slug($produk->nama_produk);
                
                // Ensure unique slug
                $count = 1;
                while (static::where('slug', $produk->slug)->where('id', '!=', $produk->id)->exists()) {
                    $produk->slug = Str::slug($produk->nama_produk) . '-' . $count;
                    $count++;
                }
            }
        });
    }

    /**
     * Relasi ke User (Penjual)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kategori
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi ke Ratings
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relasi ke Pesanan
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }

    /**
     * Hitung rata-rata rating produk
     */
    public function averageRating(): float
    {
        return round($this->ratings()->avg('rating') ?? 0, 1);
    }

    /**
     * Hitung total rating/review
     */
    public function totalRatings(): int
    {
        return $this->ratings()->count();
    }

    /**
     * Get rating distribution (berapa user beri rating 1, 2, 3, 4, 5)
     */
    public function ratingDistribution(): array
    {
        $total = $this->totalRatings();
        
        if ($total === 0) {
            return [
                1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0
            ];
        }

        $distribution = $this->ratings()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating')
            ->toArray();

        // Fill missing ratings with 0
        for ($i = 1; $i <= 5; $i++) {
            if (!isset($distribution[$i])) {
                $distribution[$i] = 0;
            }
        }

        ksort($distribution);
        return $distribution;
    }

    /**
     * Generate slug dari nama produk untuk URL SEO-friendly
     */
    public function getSlugAttribute(): string
    {
        return \Illuminate\Support\Str::slug($this->nama_produk);
    }
}
