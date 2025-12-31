<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    protected $table = 'pesanans';
    
    protected $fillable = [
        'user_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'total',
        'status',
        'catatan',
        'nama_penerima',
        'no_hp',
        'alamat'
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'total' => 'decimal:2',
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
     * Relasi ke Chat
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class);
    }
}
