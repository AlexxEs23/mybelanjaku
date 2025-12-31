<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notifikasi extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'referensi_id',
        'link',
        'dibaca'
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    /**
     * Relasi ke User (Penerima notifikasi)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Ambil referensi terkait (polymorphic manual)
     */
    public function getReferensiAttribute()
    {
        if ($this->tipe === 'pesanan' && $this->referensi_id) {
            return Pesanan::find($this->referensi_id);
        }
        
        if ($this->tipe === 'chat' && $this->referensi_id) {
            return Chat::find($this->referensi_id);
        }
        
        return null;
    }
}
