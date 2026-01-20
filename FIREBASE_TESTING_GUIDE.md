# 🔥 Panduan Testing Firebase Push Notification

## Cara Mengecek Apakah Notifikasi Berfungsi

### Step 1: Buka Debug Tool
1. **Buka browser** dan akses: `http://127.0.0.1:8000/test-firebase`
2. Halaman debug tool akan menampilkan status Firebase

### Step 2: Aktifkan Notifikasi (Jika Belum)

**Jika di debug tool muncul "TIDAK ada user yang memiliki FCM token"**, ikuti langkah ini:

1. **Login ke aplikasi**
   - Buka `http://127.0.0.1:8000/login`
   - Login dengan akun Anda (bisa user, penjual, atau admin)

2. **Aktifkan Notifikasi**
   - Buka halaman dashboard
   - Cari tombol biru **"Aktifkan Notifikasi"**
   - Klik tombol tersebut
   - Browser akan minta permission → Klik **"Allow"**

3. **Refresh debug tool**
   - Kembali ke `http://127.0.0.1:8000/test-firebase`
   - Sekarang user Anda akan muncul di tabel dengan FCM token ✅

### Step 3: Test Manual dengan Debug Tool

1. Di halaman debug tool, lihat tabel "Users dengan FCM Token"
2. Klik tombol **"Test Notifikasi"** di samping user Anda
3. Jika berhasil, akan muncul: **"✅ BERHASIL! Notifikasi telah dikirim!"**
4. **NOTIFIKASI HARUS MUNCUL DI WINDOWS ANDA SEKARANG!** 🔔

### Step 4: Jika Notifikasi Tidak Muncul

**Cek 1: Browser Permission**
- Chrome: Klik ikon gembok di address bar → Site settings → Notifications → **Allow**
- Edge: Klik ikon gembok → Permissions for this site → Notifications → **Allow**
- Firefox: Klik ikon gembok → Permissions → Receive Notifications → **Allow**

**Cek 2: Windows Settings**
1. Buka Windows Settings (Win + I)
2. Klik **System** → **Notifications**
3. Pastikan notification dari browser Anda **ON** (bukan OFF)

**Cek 3: Browser**
- Close semua tab browser
- Buka browser lagi
- Login dan test lagi

### Step 5: Test dengan Checkout Real

**Test Flow Lengkap:**

1. **Login sebagai User**
   - Pilih produk → Checkout via WhatsApp
   - Admin harus dapat notifikasi! 🔔

2. **Login sebagai Admin (tab baru)**
   - Buka `/admin/pesanan`
   - Konfirmasi pesanan
   - Penjual harus dapat notifikasi! 🔔

3. **Login sebagai Penjual (tab baru)**
   - Buka dashboard penjual
   - Klik "Kirim Pesanan"
   - User harus dapat notifikasi! 🔔

4. **Kembali sebagai User**
   - Buka dashboard user → "Pesanan Saya"
   - Klik "Konfirmasi Penerimaan"
   - Penjual harus dapat notifikasi! 🔔

## 🎯 Kapan Notifikasi Muncul?

| Aksi | Siapa yang Dapat Notifikasi |
|------|------------------------------|
| User checkout | → **Admin** |
| Admin konfirmasi pembayaran | → **Penjual** |
| Penjual kirim pesanan | → **User** |
| User konfirmasi penerimaan | → **Penjual** |
| User kasih rating | → **Penjual** |

## 📋 Troubleshooting Checklist

### ✅ Service Account JSON
- File: `ecommerceumkm-4dbc3-firebase-adminsdk-fbsvc-8fe7f35302.json`
- Location: Root folder project (sama level dengan `composer.json`)
- Cek di debug tool apakah file terdeteksi

### ✅ FCM Token Tersimpan
- Cek di debug tool apakah user memiliki FCM token
- Jika tidak ada → klik tombol "Aktifkan Notifikasi" di dashboard

### ✅ Browser Permission
- Chrome/Edge: Ikon gembok → Site settings → Notifications → **Allow**
- Jangan sampai **Block** atau **Ask**

### ✅ Windows Notification
- Windows Settings → System → Notifications → Browser = **ON**

### ✅ Firebase Logs
- Scroll ke bawah di debug tool
- Lihat bagian "Laravel Log (30 baris terakhir)"
- Cari baris yang mengandung kata "firebase" atau "error"

## 🔍 Cara Cek Database

Jika ingin cek manual apakah FCM token tersimpan:

```sql
SELECT id, name, role, LEFT(fcm_token, 30) as token_preview 
FROM users 
WHERE fcm_token IS NOT NULL;
```

## ⚡ Quick Commands

```bash
# Cek Laravel log untuk error
php artisan tail

# Clear cache jika ada issue
php artisan config:clear
php artisan cache:clear
```

## 🎉 Success Indicators

Notifikasi berhasil jika:
1. ✅ Debug tool menunjukkan "BERHASIL! Notifikasi telah dikirim!"
2. ✅ Windows menampilkan popup notifikasi
3. ✅ Data notifikasi masuk ke tabel `notifikasis`
4. ✅ Tidak ada error di Laravel log

## 🆘 Support

Jika masih error setelah semua checklist:
1. Screenshot debug tool
2. Screenshot browser console (F12 → Console tab)
3. Screenshot Windows notification settings
4. Copy isi Laravel log terakhir
