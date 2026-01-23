<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileUmkm extends Model
{
    protected $table = 'profile_umkms';

    protected $fillable = [
        'user_id',
        'nama_umkm',
        'kategori_id',
        'deskripsi_umkm',
        'tahun_berdiri',
        'nama_pemilik',
        'no_hp',
        'wilayah',
        'status_verifikasi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
}