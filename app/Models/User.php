<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method bool hasPurchasedProduct(int $produkId)
 * @method bool hasRatedProduct(int $produkId)
 * @method Rating|null getRatingForProduct(int $produkId)
 * @method float sellerAverageRating()
 * @method int sellerTotalRatings()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
        'status',
        'status_approval',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi ke Produk
     */
    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    /**
     * Relasi ke Pesanan (sebagai pembeli)
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesanan::class);
    }

    /**
     * Relasi ke Chat (sebagai admin)
     */
    public function chatsAsAdmin(): HasMany
    {
        return $this->hasMany(Chat::class, 'admin_id');
    }

    /**
     * Relasi ke Chat (sebagai penjual)
     */
    public function chatsAsPenjual(): HasMany
    {
        return $this->hasMany(Chat::class, 'penjual_id');
    }

    /**
     * Relasi ke Notifikasi
     */
    public function notifikasis(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    /**
     * Relasi ke PesanChat (sebagai pengirim)
     */
    public function pesanChats(): HasMany
    {
        return $this->hasMany(PesanChat::class, 'sender_id');
    }

    /**
     * Relasi ke Ratings (sebagai pembeli yang memberi rating)
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Hitung rata-rata rating PENJUAL (dari semua produknya)
     * Hanya untuk user dengan role PENJUAL
     */
    public function sellerAverageRating(): float
    {
        if ($this->role !== 'penjual') {
            return 0;
        }

        // Ambil semua rating dari semua produk milik penjual ini
        $allRatings = Rating::whereIn('produk_id', 
            $this->produks()->pluck('id')
        )->avg('rating');

        return round($allRatings ?? 0, 1);
    }

    /**
     * Total rating yang diterima penjual (dari semua produknya)
     */
    public function sellerTotalRatings(): int
    {
        if ($this->role !== 'penjual') {
            return 0;
        }

        return Rating::whereIn('produk_id', 
            $this->produks()->pluck('id')
        )->count();
    }

    /**
     * Cek apakah user sudah pernah membeli produk tertentu dengan status selesai
     */
    public function hasPurchasedProduct(int $produkId): bool
    {
        return $this->pesanans()
            ->where('produk_id', $produkId)
            ->where('status', 'selesai')
            ->exists();
    }

    /**
     * Cek apakah user sudah memberi rating untuk produk tertentu
     */
    public function hasRatedProduct(int $produkId): bool
    {
        return $this->ratings()
            ->where('produk_id', $produkId)
            ->exists();
    }

    /**
     * Get rating user untuk produk tertentu
     */
    public function getRatingForProduct(int $produkId): ?Rating
    {
        return $this->ratings()
            ->where('produk_id', $produkId)
            ->first();
    }
}
