# 🔔 Cara Mengatasi Notifikasi Windows Tidak Muncul

## ❌ Masalah Yang Ditemukan:

**FIREBASE_SERVER_KEY salah!** Anda mengisi dengan VAPID Key, bukan Server Key.

---

## ✅ Solusi Lengkap:

### **Step 1: Dapatkan Firebase Server Key yang BENAR**

1. Buka [Firebase Console](https://console.firebase.google.com/)
2. Pilih project: **ecommerceumkm-4dbc3**
3. Klik ⚙️ **Settings** → **Project settings**
4. Tab **Cloud Messaging**
5. Scroll ke bawah, cari **Cloud Messaging API (Legacy)**
6. Copy **Server key** (bukan VAPID/Web Push certificates!)

**Contoh Server Key:**
```
AAAAxxxxxx:APAxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```
(Server Key biasanya dimulai dengan "AAAA" dan lebih panjang dari VAPID)

---

### **Step 2: Update File .env**

Edit file `.env` baris 101:
```env
FIREBASE_SERVER_KEY=AAAA_paste_server_key_anda_disini
```

⚠️ **JANGAN pakai VAPID Key ini:**
```
BOwt2zTQ2vDTYlfG7dL9RxNPNKFIgeTWMfPRxwelU0b-6LN6S1F8xAiw0dde-8YKG696R7P24cQIxfsjjmYxnms
```

---

### **Step 3: Clear Cache Laravel**

Jalankan di terminal:
```bash
php artisan config:clear
php artisan cache:clear
```

---

### **Step 4: Aktifkan Cloud Messaging API (Legacy)**

Jika di Firebase Console tidak ada "Server key":

1. Di Firebase Console → Cloud Messaging
2. Klik tombol **"Enable"** atau **"Upgrade"** untuk Cloud Messaging API (Legacy)
3. Server Key akan muncul

**Atau aktifkan via Google Cloud Console:**
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Pilih project yang sama
3. Navigation Menu → APIs & Services → Library
4. Cari **"Firebase Cloud Messaging API"**
5. Klik **Enable**

---

### **Step 5: Test di Browser**

1. **Reload halaman** (Ctrl + F5 untuk hard refresh)
2. Login ke dashboard
3. Klik tombol **"Aktifkan Notifikasi"**
4. Klik **"Allow"** saat browser minta permission
5. Tunggu sampai muncul alert "✅ Notifikasi berhasil diaktifkan!"

---

### **Step 6: Test Notifikasi**

**Test dengan cara ini:**

1. Buka 2 tab browser:
   - Tab 1: Login sebagai **User/Pembeli**
   - Tab 2: Login sebagai **Admin**

2. Di Tab 1 (User), **checkout produk** via WhatsApp

3. Di Tab 2 (Admin), **konfirmasi pesanan** di halaman Pesanan

4. **Notifikasi Windows harus muncul** di Tab 1!

---

## 🔍 Debug / Troubleshooting:

### **Cek 1: FCM Token Tersimpan?**

Setelah klik "Aktifkan Notifikasi", buka Browser Console (F12):
```javascript
// Cek token tersimpan
console.log('FCM Token saved');
```

Atau cek database:
```sql
SELECT id, name, fcm_token FROM users WHERE id = [your_user_id];
```

FCM token harus terisi dan panjang!

---

### **Cek 2: Laravel Log**

Cek file `storage/logs/laravel.log`:

**Kalau berhasil kirim notifikasi:**
```
Firebase notification sent successfully
```

**Kalau gagal:**
```
Firebase notification failed
Firebase Server Key not configured
```

---

### **Cek 3: Browser Permission**

1. Klik **icon gembok** di address bar browser
2. Cek **Notifications** = **Allow**
3. Jika **Block**, ubah ke **Allow** dan reload

**Di Windows Settings:**
- Settings → System → Notifications
- Cek notifikasi untuk browser Anda (Chrome/Edge/Firefox) **ON**

---

### **Cek 4: Test Manual Firebase**

Buat file `test-firebase.php` di root project:

```php
<?php
require __DIR__.'/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$serverKey = $_ENV['FIREBASE_SERVER_KEY'];
$fcmToken = 'PASTE_FCM_TOKEN_DARI_DATABASE_DISINI';

echo "Server Key: " . substr($serverKey, 0, 20) . "...\n";
echo "FCM Token: " . substr($fcmToken, 0, 30) . "...\n\n";

$url = 'https://fcm.googleapis.com/fcm/send';

$notification = [
    'title' => 'Test Notifikasi',
    'body' => 'Ini test dari PHP',
    'icon' => '/favicon.ico',
];

$data = [
    'to' => $fcmToken,
    'notification' => $notification,
    'priority' => 'high',
];

$headers = [
    'Authorization: key=' . $serverKey,
    'Content-Type: application/json',
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$result = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
echo "Response: $result\n";
```

Jalankan:
```bash
php test-firebase.php
```

**Hasil yang benar:**
```json
HTTP Code: 200
Response: {"multicast_id":...,"success":1,"failure":0,...}
```

---

## 📝 Checklist Final:

- [ ] FIREBASE_SERVER_KEY sudah diisi dengan Server Key (bukan VAPID)
- [ ] Cloud Messaging API (Legacy) sudah enabled
- [ ] User sudah klik "Aktifkan Notifikasi"
- [ ] Browser permission = Allow
- [ ] FCM token tersimpan di database
- [ ] Laravel log tidak ada error Firebase
- [ ] Test checkout → notifikasi muncul

---

## 🎯 Perbedaan VAPID vs Server Key:

| **VAPID Key** | **Server Key** |
|---------------|----------------|
| Untuk client-side (browser) | Untuk server-side (PHP) |
| Dipakai di JavaScript | Dipakai di Laravel/backend |
| Format: `BOwt2zT...` | Format: `AAAAxxx...` |
| Di file `dashboard.blade.php` | Di file `.env` |
| Sudah benar ✅ | **Perlu diperbaiki ❌** |

---

## 💡 Kesimpulan:

**Masalah utama:** FIREBASE_SERVER_KEY salah (menggunakan VAPID Key).

**Solusi:**
1. Dapatkan **Server Key** yang benar dari Firebase Console
2. Update `.env` → `FIREBASE_SERVER_KEY=AAAA...`
3. `php artisan config:clear`
4. Test lagi!

Setelah diperbaiki, notifikasi Windows akan muncul! 🎉
