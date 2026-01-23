<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile_umkms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nama_umkm');
            $table->foreignId('kategori_id')->constrained('kategoris')->onDelete('cascade');
            $table->text('deskripsi_umkm')->nullable();
            $table->string('tahun_berdiri');
            $table->string('nama_pemilik');
            $table->string('no_hp')->nullable();
            $table->string('wilayah');
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_umkms');
    }
};
