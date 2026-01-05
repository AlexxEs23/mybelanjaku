<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'user_id',
        'produk_id',
        'rating',
        'review',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    /**
     * Relasi ke User (Pembeli)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Produk
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    /**
     * Scope untuk filter rating tertentu
     */
    public function scopeWithRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope untuk produk tertentu
     */
    public function scopeForProduk($query, int $produkId)
    {
        return $query->where('produk_id', $produkId);
    }
}
