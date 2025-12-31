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
         Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();

            // penerima notifikasi
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');

            // jenis notifikasi
            $table->string('judul');
            $table->text('pesan');

            // opsional relasi ke pesanan / chat
            $table->string('tipe')->nullable(); // pesanan / chat
            $table->unsignedBigInteger('referensi_id')->nullable();
            $table->string('link')->nullable(); // link ke halaman terkait

            // status dibaca
            $table->boolean('dibaca')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
