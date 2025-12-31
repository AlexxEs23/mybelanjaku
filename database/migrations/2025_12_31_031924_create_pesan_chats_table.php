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
         Schema::create('pesan_chats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('chat_id')
                ->constrained('chats')
                ->onDelete('cascade');

            $table->foreignId('pengirim_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->text('pesan');
            $table->boolean('dibaca')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesan_chats');
    }
};
