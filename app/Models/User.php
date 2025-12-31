<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
