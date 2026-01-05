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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('produk_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned()->comment('Rating 1-5 bintang');
            $table->text('review')->nullable()->comment('Komentar/review produk');
            $table->timestamps();
            
            // PENTING: 1 user hanya boleh memberi 1 rating per produk
            $table->unique(['user_id', 'produk_id'], 'unique_user_produk_rating');
            
            // Index untuk performa query
            $table->index('produk_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
