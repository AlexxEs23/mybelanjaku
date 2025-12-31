# Fitur Forgot Password - UMKM Marketplace

## 📋 Deskripsi
Fitur "Forgot Password" (Lupa Password) yang memungkinkan user untuk mereset password mereka melalui email.

## 🚀 Instalasi dan Konfigurasi

### 1. Jalankan Migration
Pastikan tabel `password_reset_tokens` sudah dibuat:

```bash
php artisan migrate
```

### 2. Konfigurasi Email (Mailtrap untuk Development)

Edit file `.env` dan tambahkan konfigurasi Mailtrap:

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@umkmmarketplace.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Cara mendapatkan kredensial Mailtrap:**
1. Daftar di https://mailtrap.io (gratis)
2. Buka "Email Testing" → "Inboxes"
3. Pilih inbox Anda
4. Klik "Show Credentials" dan pilih "Laravel 9+"
5. Salin `MAIL_USERNAME` dan `MAIL_PASSWORD`

### 3. Clear Cache Configuration

```bash
php artisan config:clear
php artisan cache:clear
```

## 📁 File yang Dibuat

### Controllers:
1. **`app/Http/Controllers/Auth/ForgotPasswordController.php`**
   - Handle halaman form forgot password
   - Generate token reset password
   - Kirim email reset password

2. **`app/Http/Controllers/Auth/ResetPasswordController.php`**
   - Handle halaman form reset password
   - Validasi token
   - Update password user

### Views:
1. **`resources/views/auth/forgot-password.blade.php`**
   - Form input email untuk request reset password

2. **`resources/views/auth/reset-password.blade.php`**
   - Form input password baru dan konfirmasi

3. **`resources/views/auth/emails/reset-password.blade.php`**
   - Template email yang dikirim ke user

### Migration:
1. **`database/migrations/2025_12_29_000000_create_password_reset_tokens_table.php`**
   - Tabel untuk menyimpan token reset password

### Routes:
Routes sudah ditambahkan di `routes/web.php`:
```php
// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');

// Reset Password
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');
```

## 🔐 Fitur Keamanan

1. **Token Expiration**: Token hanya berlaku 24 jam
2. **Single Use Token**: Token otomatis dihapus setelah digunakan
3. **Email Validation**: Email harus terdaftar di database
4. **Password Hashing**: Password baru di-hash menggunakan bcrypt
5. **CSRF Protection**: Semua form dilindungi CSRF token
6. **Token Verification**: Token divalidasi dengan Hash::check()

## 🎯 Alur Kerja

### 1. User Request Reset Password
- User mengklik "Lupa Password?" di halaman login
- User memasukkan email di form forgot password
- Sistem validasi email (harus terdaftar)
- Sistem cek apakah user menggunakan login Google

### 2. Generate dan Kirim Token
- Sistem generate token random (64 karakter)
- Token di-hash dan disimpan ke database
- Email berisi link reset dikirim ke user
- Link format: `/password/reset/{token}?email={email}`

### 3. User Reset Password
- User klik link di email
- User diarahkan ke form reset password
- User input password baru dan konfirmasi
- Sistem validasi token dan password

### 4. Update Password
- Password baru di-hash dan disimpan
- Token dihapus dari database
- User diarahkan ke login dengan pesan sukses

## 🔔 Handling User Google OAuth

Fitur ini menangani user yang login via Google:

```php
// Di ForgotPasswordController
if (empty($user->password)) {
    return back()->withErrors([
        'email' => 'Akun Anda menggunakan login Google. 
                    Silakan login menggunakan tombol "Login dengan Google".'
    ]);
}
```

**Catatan**: Jika Anda memiliki field `google_id` di tabel users, ubah kondisi menjadi:
```php
if (!empty($user->google_id)) {
    // Handle Google user
}
```

## 🧪 Testing

### Testing di Local (Development):

1. **Buka Mailtrap Dashboard**
   - Login ke https://mailtrap.io
   - Buka inbox Anda

2. **Test Forgot Password Flow**
   - Buka: http://localhost/forgot-password
   - Masukkan email yang terdaftar
   - Cek email di Mailtrap inbox
   - Klik link reset password
   - Input password baru
   - Login dengan password baru

### Testing Edge Cases:

1. **Email tidak terdaftar**: Harus muncul error
2. **Token kadaluarsa**: Generate token, tunggu 24 jam, test harus error
3. **Token invalid**: Edit link manual, harus error
4. **Password confirmation tidak cocok**: Harus muncul error
5. **User Google**: Input email user Google, harus muncul pesan khusus

## 🚀 Production Deployment

### 1. Ganti Email Provider
Untuk production, ganti dari Mailtrap ke email provider real seperti:

#### Option A: Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Catatan Gmail**: Gunakan "App Password", bukan password Gmail biasa
- https://myaccount.google.com/apppasswords

#### Option B: Amazon SES
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

#### Option C: SendGrid
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your-sendgrid-api-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="${APP_NAME}"
```

### 2. Update APP_URL di .env
```env
APP_URL=https://yourdomain.com
```

### 3. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## 📝 Validasi dan Error Messages

### Forgot Password Form:
- Email wajib diisi
- Format email harus valid
- Email harus terdaftar di database
- User Google akan mendapat pesan khusus

### Reset Password Form:
- Token wajib ada
- Email wajib diisi dan terdaftar
- Password minimal 8 karakter
- Password dan konfirmasi harus sama
- Token tidak boleh kadaluarsa (> 24 jam)

## 🎨 Customization

### Ubah Template Email
Edit file: `resources/views/auth/emails/reset-password.blade.php`

### Ubah Durasi Token
Edit `ResetPasswordController.php`, line:
```php
if (Carbon::now()->diffInHours($tokenCreatedAt) > 24) {
    // Ubah 24 jadi durasi yang Anda inginkan (dalam jam)
}
```

### Ubah Panjang Token
Edit `ForgotPasswordController.php`, line:
```php
$token = Str::random(64); // Ubah 64 jadi panjang yang Anda inginkan
```

## ⚠️ Troubleshooting

### Email tidak terkirim?
1. Cek konfigurasi `.env` sudah benar
2. Cek `php artisan config:clear` sudah dijalankan
3. Cek log error: `storage/logs/laravel.log`
4. Test koneksi SMTP dengan artisan tinker:
```php
php artisan tinker
Mail::raw('Test email', function($msg) {
    $msg->to('test@example.com')->subject('Test');
});
```

### Token invalid terus?
1. Pastikan migration sudah dijalankan
2. Cek tabel `password_reset_tokens` ada di database
3. Cek token tidak lebih dari 24 jam
4. Pastikan email di URL sama dengan email di database

### Error 500?
1. Cek `storage/logs/laravel.log`
2. Pastikan permission folder storage correct (777)
3. Cek semua environment variable sudah di set

## 📧 Support

Jika ada pertanyaan atau issue, silakan hubungi tim development.

---

**Developed for UMKM Marketplace** 🛒
Version 1.0 - December 2025
