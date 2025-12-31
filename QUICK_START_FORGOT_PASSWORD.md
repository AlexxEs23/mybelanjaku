# 🔐 Forgot Password Feature - Quick Start

## ⚡ Setup Cepat (5 Menit)

### 1️⃣ Konfigurasi Email Mailtrap

**Daftar Mailtrap:**
- Buka: https://mailtrap.io
- Daftar gratis (pakai email atau Google)
- Buka: Email Testing → Inboxes → pilih inbox Anda
- Klik "Show Credentials" → pilih "Laravel 9+"

**Update .env:**
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

### 2️⃣ Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 3️⃣ Test Fitur

1. Buka: http://localhost/login
2. Klik: "Lupa Password?"
3. Input email terdaftar (contoh: `pembeli@test.com`)
4. Cek email di Mailtrap inbox
5. Klik link reset password
6. Input password baru dan submit
7. Login dengan password baru ✅

---

## 📁 File yang Dibuat

```
✅ app/Http/Controllers/Auth/
   ├── ForgotPasswordController.php
   └── ResetPasswordController.php

✅ resources/views/auth/
   ├── forgot-password.blade.php
   ├── reset-password.blade.php
   └── emails/reset-password.blade.php

✅ database/migrations/
   ├── 2025_12_29_000000_create_password_reset_tokens_table.php
   └── 2025_12_29_000001_add_google_id_to_users_table.php (opsional)

✅ routes/web.php (updated)

✅ Dokumentasi:
   ├── FORGOT_PASSWORD_DOCUMENTATION.md (lengkap)
   ├── TESTING_GUIDE.md (test cases)
   └── .env.email.example (contoh konfigurasi)
```

---

## 🔗 Routes

```php
GET  /forgot-password          → Form input email
POST /forgot-password          → Kirim email reset
GET  /password/reset/{token}   → Form reset password
POST /password/reset           → Update password
```

---

## 🎯 Fitur Utama

✅ Generate token reset password (64 karakter)
✅ Token expired 24 jam
✅ Token dihapus setelah digunakan
✅ Email professional dengan Mailtrap
✅ Validasi email terdaftar
✅ Password minimal 8 karakter
✅ Konfirmasi password
✅ Handling user Google OAuth
✅ CSRF protection
✅ Error messages user-friendly
✅ Responsive design dengan Tailwind CSS

---

## 🚀 Untuk Production

**Ganti Email Provider:**

Edit `.env` dengan salah satu:

### Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-gmail-app-password
```

### SendGrid:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
```

**Update APP_URL:**
```env
APP_URL=https://yourdomain.com
```

---

## 📚 Dokumentasi Lengkap

- **Setup & Konfigurasi**: [FORGOT_PASSWORD_DOCUMENTATION.md](FORGOT_PASSWORD_DOCUMENTATION.md)
- **Testing Guide**: [TESTING_GUIDE.md](TESTING_GUIDE.md)
- **Email Config**: [.env.email.example](.env.email.example)

---

## ⚠️ Troubleshooting Cepat

**Email tidak terkirim?**
```bash
php artisan config:clear
php artisan cache:clear
# Cek storage/logs/laravel.log
```

**Token invalid?**
- Pastikan tabel password_reset_tokens ada
- Pastikan email di URL sama dengan database
- Token tidak boleh > 24 jam

**Error 500?**
- Cek storage/logs/laravel.log
- Pastikan .env sudah benar
- Clear semua cache

---

## 📧 Support

Baca dokumentasi lengkap di:
- [FORGOT_PASSWORD_DOCUMENTATION.md](FORGOT_PASSWORD_DOCUMENTATION.md)

**Developed with ❤️ for UMKM Marketplace**

Version: 1.0 | December 2025
