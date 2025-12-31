<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanChat extends Model
{
    protected $table = 'pesan_chats';
    
    protected $fillable = [
        'chat_id',
        'pengirim_id',
        'pesan',
        'dibaca'
    ];

    protected $casts = [
        'dibaca' => 'boolean',
    ];

    /**
     * Relasi ke Chat
     */
    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * Relasi ke User (Pengirim)
     */
    public function pengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pengirim_id');
    }
}
