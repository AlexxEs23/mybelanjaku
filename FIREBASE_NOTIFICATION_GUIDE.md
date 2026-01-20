# Dokumentasi Notifikasi Firebase

## Status Implementasi Notifikasi ✅

### 1. **User Checkout - Notifikasi ke Admin** ✅
**Lokasi:** `app/Http/Controllers/WhatsAppCheckoutController.php`

Saat user melakukan checkout via WhatsApp, sistem otomatis:
- Membuat notifikasi ke **semua admin**
- Mengirim **push notification Firebase** jika admin memiliki FCM token
- **Realtime broadcast** via Laravel Echo

```php
// Notifikasi ke Admin
$admins = User::where('role', 'admin')->get();
foreach ($admins as $admin) {
    Notifikasi::create([
        'user_id' => $admin->id,
        'judul' => 'Pesanan Baru #' . $pesanan->id,
        'pesan' => 'Ada pesanan baru dari ' . $userName . ' untuk produk ' . $produk->nama_produk,
        'tipe' => 'pesanan',
        'referensi_id' => $pesanan->id,
        'link' => route('admin.pesanan.index'),
        'dibaca' => false
    ]);
}
```

---

### 2. **Admin Konfirmasi Pesanan - Notifikasi ke Penjual** ✅
**Lokasi:** `app/Http/Controllers/AdminPesananController.php`

Saat admin mengkonfirmasi pesanan (status: `pending` → `diproses`):
- Membuat notifikasi ke **penjual**
- Mengirim **push notification Firebase** 
- **Realtime broadcast**
- Membuat chat room antara admin dan penjual

```php
// Notifikasi ke Penjual
Notifikasi::create([
    'user_id' => $penjual->id,
    'judul' => 'Produk Anda Dipesan',
    'pesan' => 'Pesanan #' . $pesanan->id . ' siap diproses.',
    'tipe' => 'pesanan',
    'referensi_id' => $pesanan->id,
    'link' => route('penjual.pesanan.index'),
    'dibaca' => false
]);
```

---

### 3. **Penjual Mengirim Pesanan - Notifikasi ke Pembeli** ✅
**Lokasi:** `app/Http/Controllers/PesananController.php`

Saat penjual input nomor resi (status: `diproses` → `dikirim`):
- Membuat notifikasi ke **pembeli**
- Mengirim **push notification Firebase**
- **Realtime broadcast**

```php
// Notifikasi ke Pembeli
if ($pesanan->user_id) {
    Notifikasi::create([
        'user_id' => $pesanan->user_id,
        'judul' => 'Pesanan Dikirim',
        'pesan' => 'Pesanan #' . $pesanan->id . ' telah dikirim dengan nomor resi: ' . $request->resi,
        'tipe' => 'pesanan',
        'referensi_id' => $pesanan->id,
        'link' => route('pembeli.pesanan.index'),
        'dibaca' => false
    ]);
}
```

---

### 4. **Pembeli Konfirmasi Pesanan Diterima - Notifikasi ke Penjual** ✅
**Lokasi:** `app/Http/Controllers/PesananController.php`

Saat pembeli konfirmasi pesanan diterima (status: `dikirim` → `selesai`):
- Membuat notifikasi ke **penjual**
- Mengirim **push notification Firebase**
- **Realtime broadcast**

```php
// Notifikasi ke Penjual
$penjual = $pesanan->produk->user;
Notifikasi::create([
    'user_id' => $penjual->id,
    'judul' => 'Pesanan Selesai',
    'pesan' => 'Pesanan #' . $pesanan->id . ' telah dikonfirmasi diterima oleh pembeli',
    'tipe' => 'pesanan',
    'referensi_id' => $pesanan->id,
    'link' => route('penjual.pesanan.index'),
    'dibaca' => false
]);
```

---

### 5. **Admin Membatalkan Pesanan - Notifikasi ke Pembeli** ✅
**Lokasi:** `app/Http/Controllers/AdminPesananController.php`

Saat admin membatalkan pesanan:
- Membuat notifikasi ke **pembeli**
- Mengirim **push notification Firebase**
- **Realtime broadcast**
- Mengembalikan stok produk

```php
// Notifikasi ke Pembeli
if ($pesanan->user_id) {
    Notifikasi::create([
        'user_id' => $pesanan->user_id,
        'judul' => 'Pesanan Dibatalkan',
        'pesan' => 'Pesanan #' . $pesanan->id . ' telah dibatalkan oleh admin',
        'tipe' => 'pesanan',
        'referensi_id' => $pesanan->id,
        'link' => route('penjual.pesanan.index'),
        'dibaca' => false
    ]);
}
```

---

## Sistem Otomatis Firebase Push Notification

### **Model Boot Event** 🔥
**Lokasi:** `app/Models/Notifikasi.php`

Setiap kali `Notifikasi::create()` dipanggil, sistem **otomatis**:

1. **Broadcast Realtime** via Laravel Echo (Reverb)
2. **Kirim Push Notification Firebase** jika user memiliki FCM token

```php
protected static function boot()
{
    parent::boot();
    
    static::created(function ($notifikasi) {
        // 1. Broadcast realtime
        broadcast(new NotificationSent($notifikasi))->toOthers();
        
        // 2. Kirim Firebase Push Notification
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
    });
}
```

---

## Firebase Service

**Lokasi:** `app/Services/FirebaseService.php`

Service untuk mengirim push notification menggunakan Firebase Cloud Messaging (FCM).

**Fungsi Utama:**
- `sendNotification()` - Kirim ke 1 user
- `sendMultipleNotifications()` - Kirim ke banyak user sekaligus

---

## Konfigurasi Firebase

### **1. Tambahkan Firebase Server Key ke `.env`**

```env
FIREBASE_SERVER_KEY=your-firebase-server-key-here
```

**Cara mendapatkan Server Key:**
1. Buka [Firebase Console](https://console.firebase.google.com/)
2. Pilih project Anda
3. Klik ⚙️ **Settings** → **Project settings**
4. Tab **Cloud Messaging**
5. Copy **Server key** atau buat key baru di **Cloud Messaging API (Legacy)**

### **2. FCM Token Disimpan Otomatis**

Saat user klik tombol "Aktifkan Notifikasi" di dashboard:
- Browser meminta permission
- Service Worker mendaftar
- FCM Token diambil dari Firebase
- Token disimpan ke database via endpoint `/save-fcm-token`

**Route:** `routes/web.php`
```php
Route::post('/save-fcm-token', function (Request $request) {
    $user = Auth::user();
    $user->update(['fcm_token' => $request->fcm_token]);
    return response()->json(['success' => true]);
});
```

---

## Alur Lengkap Notifikasi

```
USER CHECKOUT
    ↓
✅ Notifikasi DB → Admin
✅ Firebase Push → Admin (jika ada FCM token)
✅ Realtime Broadcast → Admin dashboard
    ↓
ADMIN KONFIRMASI
    ↓
✅ Notifikasi DB → Penjual
✅ Firebase Push → Penjual
✅ Realtime Broadcast → Penjual dashboard
    ↓
PENJUAL KIRIM (input resi)
    ↓
✅ Notifikasi DB → Pembeli
✅ Firebase Push → Pembeli
✅ Realtime Broadcast → Pembeli dashboard
    ↓
PEMBELI KONFIRMASI DITERIMA
    ↓
✅ Notifikasi DB → Penjual
✅ Firebase Push → Penjual
✅ Realtime Broadcast → Penjual dashboard
    ↓
SELESAI ✅
```

---

## Testing Firebase Notification

### **1. Setup FCM Token**
- Login sebagai user
- Klik tombol **"Aktifkan Notifikasi"** di dashboard
- Allow notification di browser
- FCM token akan tersimpan otomatis

### **2. Test Notifikasi**
- Lakukan checkout sebagai user
- Login sebagai admin → cek notifikasi masuk
- Konfirmasi pesanan → cek penjual dapat notifikasi
- Dan seterusnya...

### **3. Cek Log**
File log: `storage/logs/laravel.log`

Cari log Firebase:
```
Firebase notification sent successfully
Firebase notification failed
```

---

## Fitur Tambahan

### **Multiple Device Support** 
User bisa login di banyak device, setiap device punya FCM token sendiri. Saat ini sistem hanya menyimpan 1 token (terakhir). 

**Upgrade opsional:** Buat tabel `user_devices` untuk simpan banyak FCM token per user.

### **Notification Badge**
Badge merah di menu sidebar sudah ada, update realtime via Laravel Echo.

### **Notification Sound**
Tambahkan sound di `firebase-messaging-sw.js` jika perlu.

---

## Kesimpulan

✅ **Semua notifikasi sudah terimplementasi:**
1. User checkout → Notifikasi ke Admin (Firebase ✅)
2. Admin konfirmasi → Notifikasi ke Penjual (Firebase ✅)
3. Penjual kirim → Notifikasi ke Pembeli (Firebase ✅)
4. Pembeli terima → Notifikasi ke Penjual (Firebase ✅)
5. Admin batalkan → Notifikasi ke Pembeli (Firebase ✅)

✅ **Firebase Push Notification sudah otomatis** untuk semua notifikasi di atas

✅ **Realtime broadcast** via Laravel Echo (Reverb)

🔧 **Yang perlu dilakukan:**
1. Tambahkan `FIREBASE_SERVER_KEY` ke file `.env`
2. Test dengan klik "Aktifkan Notifikasi" di dashboard
3. Lakukan transaksi untuk test notifikasi end-to-end
