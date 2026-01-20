# Panduan Deploy ke Production / Hosting Domain

## 🚀 Langkah Deploy ke Production

### 1. **Persiapan File untuk Upload**

File/folder yang perlu diupload:
```
✅ app/
✅ bootstrap/
✅ config/
✅ database/
✅ public/
✅ resources/
✅ routes/
✅ storage/
✅ vendor/ (atau jalankan composer install di server)
✅ .env (edit untuk production)
✅ artisan
✅ composer.json
✅ composer.lock
✅ package.json
✅ vite.config.js
```

**JANGAN upload:**
```
❌ .git/
❌ node_modules/
❌ tests/
❌ .env.example (sudah ada .env yang sudah dikonfigurasi)
```

---

### 2. **Konfigurasi File `.env` untuk Production**

Edit file [.env](d:\laragon\www\Belajar\.env) sebelum upload:

```env
APP_NAME="CheckoutAja"
APP_ENV=production
APP_KEY=base64:r59h6tLQ1rjXJPCoVyRLlRXBuu0e57Wc/ZjumU/HujU=
APP_DEBUG=false  # ⚠️ PENTING: Set false untuk production
APP_URL=https://yourdomain.com  # ⚠️ Ganti dengan domain Anda

# Database - sesuaikan dengan hosting Anda
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_anda
DB_USERNAME=username_database_anda
DB_PASSWORD=password_database_anda

# Broadcasting - Set ke log untuk disable realtime (optional)
BROADCAST_CONNECTION=log

# Google OAuth - Update redirect URI
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback

# Reverb - Untuk production, bisa disable atau setup dengan domain
REVERB_HOST=yourdomain.com
REVERB_PORT=443
REVERB_SCHEME=https

VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"

# Supabase - tetap sama
SUPABASE_URL=https://zpdqqnsdhjnckezbyiws.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6InpwZHFxbnNkaGpuY2tlemJ5aXdzIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTc2NjQ3OTI4NiwiZXhwIjoyMDgyMDU1Mjg2fQ.pkEPo3mB3bfaWLoegTtnuNic_V0uwdaH-OGDJQ-tgVI
SUPABASE_STORAGE_BUCKET=product-images

# Firebase - tambahkan server key Anda
FIREBASE_SERVER_KEY=your-actual-firebase-server-key
```

---

### 3. **Setup Permission Folder di Server**

Setelah upload, jalankan via SSH atau terminal hosting:

```bash
# Set permission untuk storage dan cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set owner (sesuaikan dengan user server Anda)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

---

### 4. **Install Dependencies di Server**

Jika tidak upload folder `vendor`:

```bash
cd /path/to/your/project
composer install --optimize-autoloader --no-dev
```

Untuk assets (jika perlu):
```bash
npm install
npm run build
```

---

### 5. **Database Migration**

Jalankan migration di server:

```bash
php artisan migrate --force
php artisan db:seed --class=UserSeeder --force
```

**⚠️ Backup database dulu sebelum migrate!**

---

### 6. **Optimize Laravel untuk Production**

```bash
# Cache config
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Clear all cache jika perlu
php artisan optimize:clear
```

---

### 7. **Setup Document Root**

**Penting:** Document root harus mengarah ke folder `public/`

Contoh di cPanel:
- Domain: `yourdomain.com`
- Document Root: `/public_html/yourdomain.com/public`

Atau edit `.htaccess` di root jika tidak bisa ubah document root:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### 8. **Perbaikan Error 404 / NotFound**

#### A. **Setup .htaccess di folder public/**

File: `public/.htaccess`
```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### B. **Pastikan mod_rewrite aktif**

Di cPanel: Apache Modules → cek `mod_rewrite` aktif

#### C. **Storage Link**

Jalankan untuk link storage:
```bash
php artisan storage:link
```

---

### 9. **Disable Broadcasting Jika Tidak Setup Reverb di Production**

Jika tidak setup Reverb/websocket server di production:

```env
# Di .env
BROADCAST_CONNECTION=log
```

Ini akan membuat broadcasting tidak error tapi juga tidak mengirim realtime notification. Firebase push notification tetap jalan.

---

### 10. **Testing Setelah Deploy**

✅ **Checklist Testing:**
- [ ] Homepage bisa diakses
- [ ] Login/Register berfungsi
- [ ] Dashboard muncul setelah login
- [ ] Upload produk dengan gambar (ke Supabase)
- [ ] Checkout berfungsi tanpa error
- [ ] Notifikasi database masuk
- [ ] Firebase push notification (jika sudah setup)
- [ ] Google OAuth (update redirect URI dulu)

---

## 🔧 Troubleshooting Common Issues

### **Error: 500 Internal Server Error**
```bash
# Cek log error
tail -f storage/logs/laravel.log

# Clear semua cache
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### **Error: 404 Not Found (semua route)**
- Pastikan document root mengarah ke folder `public/`
- Pastikan `.htaccess` ada di folder `public/`
- Pastikan `mod_rewrite` aktif

### **Error: APP_KEY not set**
```bash
php artisan key:generate
```

### **Error: Storage symlink tidak jalan**
```bash
# Hapus link lama
rm public/storage

# Buat link baru
php artisan storage:link
```

### **Error: Broadcast/Reverb Connection Failed**
```env
# Set di .env
BROADCAST_CONNECTION=log
```

Atau hapus semua yang berkaitan dengan Reverb di `.env`

### **Gambar tidak muncul (Supabase)**
- Pastikan `SUPABASE_URL` dan `SUPABASE_KEY` benar
- Cek bucket `product-images` sudah dibuat
- Cek policies bucket set ke public

### **Firebase Notification tidak jalan**
- Pastikan `FIREBASE_SERVER_KEY` sudah diisi
- Cek di Firebase Console apakah server key benar
- Cek log: `storage/logs/laravel.log`

---

## 📝 Kesimpulan

### **Yang Sudah Diperbaiki:**

✅ **Broadcasting Error Fixed**
- Broadcasting sekarang fail gracefully (tidak akan stop proses utama)
- Jika Reverb tidak jalan, aplikasi tetap berfungsi normal
- Error hanya dicatat di log, tidak muncul ke user

✅ **Alert Notification Enhanced**
- Alert success/error/warning sekarang muncul di pojok kanan atas
- Auto-dismiss setelah 5 detik
- Animasi slide-in yang smooth
- Icon emoji untuk tiap jenis alert

✅ **Production Ready**
- Tidak ada hardcoded localhost URL
- Semua menggunakan Laravel helper (`url()`, `asset()`, `route()`)
- Broadcasting bisa di-disable tanpa error
- Firebase push notification jalan independen

✅ **Better Error Handling**
- Setiap proses ada feedback message
- Error di-catch dengan baik
- User selalu tahu status aksi mereka

---

## 🎯 Quick Start Production

1. Edit `.env` → set `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://yourdomain.com`
2. Upload semua file kecuali `node_modules` dan `.git`
3. Set permission: `chmod -R 775 storage bootstrap/cache`
4. Install dependencies: `composer install --no-dev`
5. Migrate database: `php artisan migrate --force`
6. Cache config: `php artisan config:cache`
7. Storage link: `php artisan storage:link`
8. Set document root ke `public/`
9. Test di browser! 🚀

---

**Selamat Deploy! 🎉**
