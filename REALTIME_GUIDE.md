# 🚀 Cara Menggunakan Real-Time Features

## Prerequisites Yang Harus Berjalan:

### 1. **Reverb Server** (WebSocket)
```bash
php artisan reverb:start
```
Harus running terus di background. Buka terminal baru dan jalankan command ini.

### 2. **Vite Dev Server** (untuk compile JS)
```bash
npm run dev
```
Atau untuk production:
```bash
npm run build
```

### 3. **Laravel Server**
```bash
php artisan serve
```

---

## 📝 Setup di Layout/View Anda

### 1. Di Layout Utama (`app.blade.php` atau `layout.blade.php`)

Tambahkan di bagian `<head>`:
```blade
<x-realtime-meta />
```

### 2. Di Navbar (untuk Notification Bell)

```blade
<x-notification-bell 
    :notifications="auth()->user()->notifikasis()->latest()->take(5)->get()"
    :unreadCount="auth()->user()->notifikasis()->where('dibaca', false)->count()"
/>
```

### 3. Di Halaman Chat

```blade
{{-- Di halaman chat --}}
<div data-chat-messages data-current-user="{{ auth()->id() }}">
    {{-- Chat messages akan muncul di sini --}}
</div>

<script>
    // Initialize chat ketika halaman dimuat
    document.addEventListener('DOMContentLoaded', () => {
        const chatId = {{ $chat->id }};
        initializeRealtimeChat(chatId);
    });
</script>
```

---

## 🎯 Cara Kerja Real-Time

### **Notifikasi:**
1. Server create notifikasi baru
2. Server trigger event `NotificationSent`
3. Event dikirim via Reverb WebSocket ke user yang tepat
4. Browser user langsung terima notifikasi (TANPA refresh!)
5. Notifikasi muncul sebagai toast + update badge

### **Chat:**
1. User kirim pesan
2. Server simpan pesan & trigger event `MessageSent`
3. Event dikirim via WebSocket ke semua user di chat room
4. Pesan langsung muncul di chat (TANPA refresh!)

---

## 🧪 Testing Real-Time

### Cara 1: Gunakan Test Dashboard
```
http://localhost:8000/test/realtime-dashboard
```

### Cara 2: Manual Testing
1. Buka 2 browser/tab berbeda
2. Login dengan user berbeda
3. Kirim chat/notifikasi dari satu user
4. Lihat apakah user lain langsung menerima (tanpa refresh!)

### Cara 3: Menggunakan Artisan Tinker
```bash
php artisan tinker
```

```php
# Test Notifikasi
$user = App\Models\User::find(1);
$notif = App\Models\Notifikasi::create([
    'user_id' => $user->id,
    'judul' => 'Test Real-Time',
    'pesan' => 'Ini notifikasi real-time!',
    'tipe' => 'info'
]);
event(new App\Events\NotificationSent($notif));

# Test Chat
$chat = App\Models\Chat::find(1);
$pesan = App\Models\PesanChat::create([
    'chat_id' => $chat->id,
    'pengirim_id' => 1,
    'pesan' => 'Halo, ini pesan real-time!'
]);
event(new App\Events\MessageSent($pesan));
```

---

## ⚠️ Troubleshooting

### ❌ Notifikasi tidak muncul?

**Cek:**
1. ✅ Reverb server berjalan? `php artisan reverb:start`
2. ✅ Vite dev server berjalan? `npm run dev`
3. ✅ Browser console ada error? (F12)
4. ✅ Sudah tambahkan `<x-realtime-meta />` di layout?
5. ✅ User sudah login? (cek `meta[name="user-id"]`)

### ❌ Console error: "Echo not initialized"

**Solusi:**
1. Jalankan: `npm run dev`
2. Refresh browser
3. Pastikan `@vite(['resources/js/app.js'])` ada di layout

### ❌ WebSocket connection failed

**Cek:**
1. Reverb server running di port 8080
2. `.env` setting benar:
   ```
   BROADCAST_CONNECTION=reverb
   REVERB_HOST=localhost
   REVERB_PORT=8080
   REVERB_SCHEME=http
   ```
3. Jalankan: `php artisan config:clear`

### ❌ Event dikirim tapi tidak diterima

**Cek di Browser Console:**
1. Apakah ada log "Subscribing to..."?
2. Apakah ada error "403 Forbidden"?
3. Jika 403: cek `routes/channels.php` authorization

---

## 🎨 Customization

### Ubah Tampilan Toast Notifikasi
Edit file: `resources/js/realtime-notifications.js`
Function: `createToast()`

### Ubah Tampilan Chat Message
Edit file: `resources/js/realtime-chat.js`
Function: `appendMessageToChat()`

### Tambah Sound Notification
Taruh file `notification.mp3` di `public/sounds/`
Sudah otomatis dipakai saat chat message masuk.

---

## 📦 Dependencies Yang Dipakai

```json
{
  "laravel-echo": "^2.2.7",
  "pusher-js": "^8.4.0-rc2"
}
```

Pusher-js dipakai oleh Reverb (bukan Pusher.com service).

---

## 🚀 Production Deployment

1. Build assets:
   ```bash
   npm run build
   ```

2. Set `.env` production:
   ```env
   BROADCAST_CONNECTION=reverb
   REVERB_HOST=yourdomain.com
   REVERB_PORT=443
   REVERB_SCHEME=https
   ```

3. Jalankan Reverb di production:
   ```bash
   # Gunakan supervisor atau pm2
   php artisan reverb:start
   ```

4. Jangan lupa: SSL certificate untuk WebSocket (wss://)

---

## 📞 Support

Jika ada masalah, cek:
1. `storage/logs/laravel.log`
2. Browser console (F12)
3. Reverb server output

Happy coding! 🎉
