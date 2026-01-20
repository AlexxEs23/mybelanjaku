<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Services\FirebaseService;
use App\Events\NotificationSent;

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
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        // Setelah notifikasi dibuat, kirim push notification via Firebase
        static::created(function ($notifikasi) {
            // Broadcast event untuk realtime notification (non-blocking)
            try {
                if (config('broadcasting.default') !== 'null' && config('app.env') !== 'testing') {
                    broadcast(new NotificationSent($notifikasi))->toOthers();
                }
            } catch (\Exception $e) {
                // Log error tapi jangan stop proses utama
                \Illuminate\Support\Facades\Log::warning('Broadcasting notification failed: ' . $e->getMessage());
            }

            // Kirim Firebase Push Notification jika user punya FCM token (non-blocking)
            try {
                $user = $notifikasi->user;
                if ($user && !empty($user->fcm_token)) {
                    $firebaseService = new FirebaseService();
                    $firebaseService->sendNotification(
                        $user->fcm_token,
                        $notifikasi->judul,
                        $notifikasi->pesan,
                        [
                            'tipe' => $notifikasi->tipe,
                            'referensi_id' => $notifikasi->referensi_id,
                            'link' => $notifikasi->link ?? url('/notifikasi'),
                        ]
                    );
                }
            } catch (\Exception $e) {
                // Log error tapi jangan stop proses utama
                \Illuminate\Support\Facades\Log::warning('Firebase push notification failed: ' . $e->getMessage());
            }
        });
    }

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
