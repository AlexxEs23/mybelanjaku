# 🧪 Testing Guide - Forgot Password Feature

## Persiapan Testing

### 1. Setup Mailtrap (Development)
1. Daftar di https://mailtrap.io (gratis)
2. Login dan buka "Email Testing" → "Inboxes"
3. Klik inbox Anda
4. Klik "Show Credentials" → pilih "Laravel 9+"
5. Copy `MAIL_USERNAME` dan `MAIL_PASSWORD`
6. Update file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=paste_username_dari_mailtrap
MAIL_PASSWORD=paste_password_dari_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@umkmmarketplace.com"
MAIL_FROM_NAME="UMKM Marketplace"
APP_URL=http://localhost
```

7. Clear cache:
```bash
php artisan config:clear
php artisan cache:clear
```

## 📋 Test Cases

### ✅ Test Case 1: Flow Normal - Reset Password Berhasil

**Steps:**
1. Buka http://localhost/login
2. Klik link "Lupa Password?"
3. Input email yang terdaftar (contoh: `pembeli@test.com`)
4. Klik "Kirim Link Reset Password"
5. Buka Mailtrap inbox
6. Buka email "Reset Password"
7. Klik tombol "Reset Password Sekarang"
8. Input password baru: `newpassword123`
9. Input konfirmasi password: `newpassword123`
10. Klik "Reset Password"
11. Harus redirect ke login dengan pesan sukses
12. Login dengan email dan password baru
13. Harus berhasil login

**Expected Result:**
- ✅ Email terkirim ke Mailtrap
- ✅ Link reset valid dan bisa dibuka
- ✅ Password berhasil direset
- ✅ Bisa login dengan password baru
- ✅ Token terhapus dari database

---

### ❌ Test Case 2: Email Tidak Terdaftar

**Steps:**
1. Buka http://localhost/forgot-password
2. Input email yang tidak terdaftar: `tidakada@test.com`
3. Klik "Kirim Link Reset Password"

**Expected Result:**
- ❌ Muncul error: "Email tidak terdaftar di sistem kami."
- ❌ Email tidak terkirim

---

### ❌ Test Case 3: Format Email Invalid

**Steps:**
1. Buka http://localhost/forgot-password
2. Input email invalid: `emailinvalid`
3. Klik "Kirim Link Reset Password"

**Expected Result:**
- ❌ Muncul error: "Format email tidak valid."
- ❌ Email tidak terkirim

---

### ❌ Test Case 4: User Google OAuth

**Steps:**
1. Buka http://localhost/forgot-password
2. Input email user yang login via Google
3. Klik "Kirim Link Reset Password"

**Expected Result:**
- ❌ Muncul error: "Akun Anda menggunakan login Google..."
- ❌ Email tidak terkirim

**Note:** User Google adalah user yang field `password`-nya NULL/kosong

---

### ❌ Test Case 5: Token Kadaluarsa (24 Jam)

**Steps:**
1. Request reset password (test case 1)
2. Edit database: Update `created_at` di tabel `password_reset_tokens` jadi 25 jam yang lalu
   ```sql
   UPDATE password_reset_tokens 
   SET created_at = DATE_SUB(NOW(), INTERVAL 25 HOUR) 
   WHERE email = 'pembeli@test.com';
   ```
3. Buka link reset dari email
4. Input password baru
5. Submit form

**Expected Result:**
- ❌ Muncul error: "Token reset password sudah kadaluarsa..."
- ❌ Token terhapus dari database
- ❌ Password tidak berubah

---

### ❌ Test Case 6: Token Invalid/Dipalsukan

**Steps:**
1. Request reset password (test case 1)
2. Edit URL manual, ubah token jadi random: 
   `http://localhost/password/reset/tokenpalsu123?email=pembeli@test.com`
3. Input password baru
4. Submit form

**Expected Result:**
- ❌ Muncul error: "Token reset password tidak valid."
- ❌ Password tidak berubah

---

### ❌ Test Case 7: Password Terlalu Pendek

**Steps:**
1. Request reset password (test case 1)
2. Klik link dari email
3. Input password baru: `123` (kurang dari 8 karakter)
4. Input konfirmasi: `123`
5. Submit form

**Expected Result:**
- ❌ Muncul error: "Password minimal 8 karakter."
- ❌ Password tidak berubah

---

### ❌ Test Case 8: Konfirmasi Password Tidak Cocok

**Steps:**
1. Request reset password (test case 1)
2. Klik link dari email
3. Input password baru: `newpassword123`
4. Input konfirmasi: `differentpassword`
5. Submit form

**Expected Result:**
- ❌ Muncul error: "Konfirmasi password tidak cocok."
- ❌ Password tidak berubah

---

### ✅ Test Case 9: Token Dihapus Setelah Berhasil Reset

**Steps:**
1. Reset password berhasil (test case 1)
2. Coba buka link reset yang sama lagi

**Expected Result:**
- ❌ Muncul error: "Token reset password tidak valid atau sudah kadaluarsa."
- ✅ Token tidak ada di database (sudah dihapus)

---

### ✅ Test Case 10: Request Reset Password Dua Kali

**Steps:**
1. Request reset password untuk email: `pembeli@test.com`
2. Cek Mailtrap, ada email pertama
3. Request reset password lagi untuk email yang sama
4. Cek Mailtrap, ada email kedua
5. Coba gunakan link email pertama
6. Gunakan link email kedua

**Expected Result:**
- ❌ Link email pertama: error "Token reset password tidak valid"
- ✅ Link email kedua: berhasil reset password
- ✅ Hanya ada 1 token di database (yang terakhir)

---

## 🔍 Cara Cek Database

### Cek Apakah Token Tersimpan:
```sql
SELECT * FROM password_reset_tokens;
```

### Cek Apakah Password User Berubah:
```sql
SELECT email, password, updated_at FROM users WHERE email = 'pembeli@test.com';
```

### Manual Hapus Token (untuk testing ulang):
```sql
DELETE FROM password_reset_tokens WHERE email = 'pembeli@test.com';
```

---

## 📧 Cek Email di Mailtrap

1. Login ke https://mailtrap.io
2. Buka "Email Testing" → "Inboxes" → pilih inbox Anda
3. Setiap email yang dikirim akan muncul di sini
4. Klik email untuk lihat detail dan klik link reset

**Tips:**
- Email akan muncul dalam hitungan detik
- Jika tidak muncul, cek `storage/logs/laravel.log` untuk error
- Mailtrap inbox bisa menyimpan hingga 500 email

---

## 🐛 Troubleshooting

### Problem: Email tidak terkirim
**Solution:**
1. Cek `.env` - pastikan kredensial Mailtrap benar
2. Run `php artisan config:clear`
3. Cek `storage/logs/laravel.log`
4. Test koneksi SMTP:
```bash
php artisan tinker
Mail::raw('Test', function($msg){ $msg->to('test@test.com')->subject('Test'); });
```

### Problem: Token invalid terus
**Solution:**
1. Pastikan migration sudah dijalankan
2. Cek tabel `password_reset_tokens` ada dan struktur benar
3. Pastikan email di URL sama persis dengan email di database (case sensitive)

### Problem: Redirect loop atau 404
**Solution:**
1. Run `php artisan route:clear`
2. Run `php artisan route:list | grep password` untuk cek routes
3. Pastikan routes sudah benar di `web.php`

### Problem: CSRF Token Mismatch
**Solution:**
1. Clear browser cache/cookies
2. Pastikan `@csrf` ada di form
3. Run `php artisan cache:clear`

---

## ✅ Checklist Testing

Sebelum deploy ke production, pastikan semua test case ini pass:

- [ ] Test Case 1: Flow normal berhasil
- [ ] Test Case 2: Email tidak terdaftar
- [ ] Test Case 3: Format email invalid
- [ ] Test Case 4: User Google OAuth
- [ ] Test Case 5: Token kadaluarsa
- [ ] Test Case 6: Token invalid
- [ ] Test Case 7: Password terlalu pendek
- [ ] Test Case 8: Konfirmasi password tidak cocok
- [ ] Test Case 9: Token dihapus setelah berhasil
- [ ] Test Case 10: Request dua kali

---

## 📝 Test Report Template

```
FORGOT PASSWORD TESTING REPORT
Date: [Tanggal Testing]
Tester: [Nama Anda]
Environment: Development (Mailtrap)

┌─────────────────────────────────────────────────────┐
│ Test Case 1: Flow Normal                     ✅ PASS │
│ Test Case 2: Email Tidak Terdaftar           ✅ PASS │
│ Test Case 3: Format Email Invalid            ✅ PASS │
│ Test Case 4: User Google OAuth               ✅ PASS │
│ Test Case 5: Token Kadaluarsa                ✅ PASS │
│ Test Case 6: Token Invalid                   ✅ PASS │
│ Test Case 7: Password Pendek                 ✅ PASS │
│ Test Case 8: Konfirmasi Tidak Cocok          ✅ PASS │
│ Test Case 9: Token Dihapus                   ✅ PASS │
│ Test Case 10: Request Dua Kali               ✅ PASS │
└─────────────────────────────────────────────────────┘

Total: 10/10 Passed
Status: READY FOR PRODUCTION ✅

Notes:
[Tambahkan catatan jika ada]
```

---

**Happy Testing!** 🚀
