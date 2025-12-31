<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $fillable = [
        'admin_id',
        'penjual_id',
        'pesanan_id'
    ];

    /**
     * Relasi ke User (Admin)
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Relasi ke User (Penjual)
     */
    public function penjual(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penjual_id');
    }

    /**
     * Relasi ke Pesanan (Opsional)
     */
    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    /**
     * Relasi ke PesanChat
     */
    public function pesanChats(): HasMany
    {
        return $this->hasMany(PesanChat::class);
    }

    /**
     * Ambil pesan terakhir
     */
    public function lastMessage()
    {
        return $this->hasOne(PesanChat::class)->latest();
    }
}
