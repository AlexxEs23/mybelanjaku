<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Chat;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// 🔥 CHANNEL UNTUK CHAT ROOM
Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);
    if (!$chat) return false;
    
    // Hanya admin atau penjual dari chat ini yang boleh akses
    return $chat->admin_id === $user->id || $chat->penjual_id === $user->id;
});

// 🔥 CHANNEL UNTUK NOTIFIKASI USER
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});
