<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Chat;
use App\Models\PesanChat;
use App\Models\Notifikasi;
use App\Events\MessageSent;
use App\Events\NotificationSent;

/*
|--------------------------------------------------------------------------
| Real-Time Testing Routes
|--------------------------------------------------------------------------
|
| Route-route ini untuk testing fitur real-time secara manual.
| HANYA untuk development, JANGAN di production!
|
*/

// 🔥 Simple Demo - NO NPM NEEDED! (Pakai CDN)
Route::get('/simple/realtime', function () {
    return view('simple-realtime-demo');
})->middleware('auth')->name('simple.realtime');

// 🔥 Demo Page - Simple Real-Time Demo
Route::get('/demo/realtime', function () {
    return view('realtime-demo');
})->middleware('auth')->name('demo.realtime');

// 🔥 Test Kirim Notifikasi
Route::get('/test/notification/{userId}', function ($userId) {
    $user = User::findOrFail($userId);
    
    $notifikasi = Notifikasi::create([
        'user_id' => $user->id,
        'judul' => 'Test Real-Time Notification',
        'pesan' => 'Ini adalah test notifikasi real-time pada ' . now()->format('H:i:s'),
        'tipe' => 'info',
    ]);

    event(new NotificationSent($notifikasi));

    return response()->json([
        'success' => true,
        'message' => 'Notifikasi berhasil dikirim!',
        'data' => $notifikasi,
        'channel' => 'user.' . $user->id,
        'event' => 'notification.sent',
        'instructions' => [
            'Buka Console Browser (F12)',
            'Pastikan sudah subscribe ke channel: user.' . $user->id,
            'Lihat apakah event diterima di console',
        ]
    ]);
})->name('test.notification');

// 🔥 Test Kirim Chat Message
Route::get('/test/chat/{chatId}', function ($chatId) {
    $chat = Chat::findOrFail($chatId);
    
    // Pilih pengirim (ambil admin atau penjual)
    $pengirim = $chat->admin;
    
    $pesan = PesanChat::create([
        'chat_id' => $chat->id,
        'pengirim_id' => $pengirim->id,
        'pesan' => 'Test real-time chat message pada ' . now()->format('H:i:s'),
    ]);

    event(new MessageSent($pesan));

    return response()->json([
        'success' => true,
        'message' => 'Pesan berhasil dikirim!',
        'data' => $pesan,
        'channel' => 'chat.' . $chat->id,
        'event' => 'message.sent',
        'instructions' => [
            'Buka Console Browser (F12)',
            'Pastikan sudah subscribe ke channel: chat.' . $chat->id,
            'Lihat apakah event diterima di console',
        ]
    ]);
})->name('test.chat');

// 🔥 Test Broadcast Status
Route::get('/test/broadcast-status', function () {
    return response()->json([
        'broadcast_driver' => config('broadcasting.default'),
        'reverb_configured' => config('broadcasting.connections.reverb.key') !== null,
        'pusher_configured' => config('broadcasting.connections.pusher.key') !== null,
        'queue_driver' => config('queue.default'),
        'app_url' => config('app.url'),
        'reverb_config' => [
            'host' => config('broadcasting.connections.reverb.options.host'),
            'port' => config('broadcasting.connections.reverb.options.port'),
            'scheme' => config('broadcasting.connections.reverb.options.scheme'),
        ],
    ]);
})->name('test.broadcast-status');

// 🔥 Halaman Testing Real-Time
Route::get('/test/realtime-dashboard', function () {
    $users = User::take(5)->get();
    $chats = Chat::with(['admin', 'penjual'])->take(5)->get();

    return view('test-realtime', [
        'users' => $users,
        'chats' => $chats,
        'broadcastDriver' => config('broadcasting.default'),
    ]);
})->name('test.realtime-dashboard');
