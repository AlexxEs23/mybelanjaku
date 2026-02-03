# 🚀 Panduan Deploy ke Production - CheckoutAja

> **Status Kesiapan: ✅ READY FOR PRODUCTION**  
> Terakhir diupdate: February 3, 2026

## 📋 Pre-Deployment Checklist

Sebelum deploy, pastikan sudah:
- ✅ `.env` sudah diupdate untuk production (APP_DEBUG=false)
- ✅ Assets sudah di-build (`npm run build`)
- ✅ Console.log debugging sudah dibersihkan
- ✅ Database production sudah disiapkan
- ✅ Supabase bucket `product-images` sudah dibuat
- ✅ **Email provider dipilih** (Gmail/SendGrid/Mailgun) - Lihat `.env.email.production`
- ✅ **Firebase setup** (optional, untuk push notifications) - Lihat `NOTIFICATION_PRODUCTION_GUIDE.md`
- ✅ SSL Certificate sudah aktif di hosting (HTTPS)

---

## 🎯 Quick Deploy (5 Menit)

```bash
# 1. Upload file ke hosting (via FTP/cPanel File Manager)
# 2. Di terminal hosting, jalankan:
cd /path/to/your/project
composer install --optimize-autoloader --no-dev
php artisan key:generate
php artisan migrate --force
php artisan storage:link
chmod -R 775 storage bootstrap/cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Set document root ke folder public/
# 4. Akses domain Anda dan test!
```

---

---

## 📦 Langkah-Langkah Deploy Detail

### 1. **Persiapan File untuk Upload**

**File/folder yang WAJIB diupload:**
```
✅ app/                    # Application logic
✅ bootstrap/              # Framework bootstrap files
✅ config/                 # Configuration files
✅ database/               # Migrations & seeders
✅ public/                 # Web root (index.php, assets)
✅ resources/              # Views, CSS, JS source
✅ routes/                 # Route definitions
✅ storage/                # Logs, cache, uploads
✅ vendor/                 # PHP dependencies (atau run composer install)
✅ .env                    # Environment config (EDIT DULU!)
✅ artisan                 # CLI tool
✅ composer.json           # PHP dependencies manifest
✅ composer.lock           # Locked versions
✅ package.json            # Node dependencies manifest
✅ vite.config.js          # Vite config
✅ ecommerceumkm-*.json    # Firebase credentials
```

**JANGAN upload (akan menambah ukuran & tidak perlu):**
```
❌ .git/                   # Git history (besar & tidak perlu)
❌ node_modules/           # Akan di-install di server jika perlu
❌ tests/                  # Unit tests tidak perlu di production
❌ .env.example            # Hanya template
❌ .gitignore              # Hanya untuk development
❌ storage/logs/*.log      # File log lokal
❌ .vscode/                # Editor config
❌ .phpunit.result.cache   # Test cache
```

**Cara Upload:**
- **Via FTP:** Gunakan FileZilla atau WinSCP
- **Via cPanel:** File Manager → Upload → Extract
- **Via Git:** Clone repository, lalu run setup scripts

---

### 2. **Konfigurasi File `.env` untuk Production**

**⚠️ CRITICAL:** File `.env` sudah diupdate untuk production mode!

Sebelum upload, pastikan nilai berikut sudah benar:

```env
# === APLIKASI ===
APP_NAME=CheckoutAja
APP_ENV=production              # ⚠️ WAJIB production
APP_DEBUG=false                 # ⚠️ WAJIB false (security!)
APP_URL=https://yourdomain.com  # 🔄 GANTI dengan domain Anda

# === DATABASE ===
DB_CONNECTION=mysql
DB_HOST=127.0.0.1              # atau hostname dari hosting
DB_PORT=3306
DB_DATABASE=nama_database       # 🔄 Dari cPanel MySQL
DB_USERNAME=username_db         # 🔄 Dari cPanel MySQL
DB_PASSWORD=password_db         # 🔄 Dari cPanel MySQL

# === LOGGING ===
LOG_LEVEL=error                 # Production: hanya log error

# === SESSION & CACHE ===
SESSION_DRIVER=database
CACHE_STORE=database

# === GOOGLE OAUTH (opsional) ===
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-secret
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback  # 🔄 GANTI

# === SUPABASE (untuk upload gambar) ===
SUPABASE_URL=https://zpdqqnsdhjnckezbyiws.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...  # Key Anda
SUPABASE_STORAGE_BUCKET=product-images

# === FIREBASE (push notification) ===
FIREBASE_SERVER_KEY=your-firebase-server-key  # 🔄 Dari Firebase Console

# === BROADCASTING (optional - bisa di-disable) ===
BROADCAST_CONNECTION=log  # Disable realtime, gunakan Firebase saja
```

**Cara mendapatkan credentials:**

| Service | Cara Mendapatkan | File Panduan |
|---------|------------------|--------------|
| **Database** | cPanel → MySQL Database → Create Database & User | - |
| **Email** | Pilih provider (Gmail/SendGrid/Mailgun/dll) | [.env.email.production](.env.email.production) |
| **Google OAuth** | [Google Cloud Console](https://console.cloud.google.com) → Credentials | - |
| **Supabase** | [Supabase Dashboard](https://supabase.com/dashboard) → Project Settings → API | - |
| **Firebase** | [Firebase Console](https://console.firebase.google.com) → Project Settings → Cloud Messaging | [NOTIFICATION_PRODUCTION_GUIDE.md](NOTIFICATION_PRODUCTION_GUIDE.md) |

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

**A. PHP Dependencies (Composer):**

```bash
cd /path/to/your/project

# Install dependencies tanpa dev packages
composer install --optimize-autoloader --no-dev

# Generate application key (jika belum ada di .env)
php artisan key:generate
```

**B. Node.js Dependencies (optional, jika belum build lokal):**

```bash
# Hanya jika belum run npm run build di lokal
npm install
npm run build
```

**⚠️ Rekomendasi:** Build assets di lokal (`npm run build`), lalu upload folder `public/build/` saja. Lebih cepat!

---

### 5. **Database Setup**

**A. Buat Database di cPanel:**
1. Login cPanel → MySQL® Databases
2. Create New Database: `namaanda_checkoutaja`
3. Create User: `namaanda_user` + password
4. Add User to Database → All Privileges

**B. Update `.env` dengan credentials database**

**C. Jalankan Migration:**

```bash
# Backup database dulu jika ada data penting!

# Run migrations
php artisan migrate --force

# (Optional) Seed data awal
php artisan db:seed --class=UserSeeder --force
```

**Default Admin Account (dari Seeder):**
- **Email:** admin@checkoutaja.com
- **Password:** admin123
- **Role:** admin

⚠️ **GANTI PASSWORD** setelah login pertama kali!

---

### 6. **Optimize Laravel untuk Production**

Laravel memiliki fitur caching yang meningkatkan performa 10-20x di production:

```bash
# 1. Cache configuration (wajib!)
php artisan config:cache

# 2. Cache routes (wajib!)
php artisan route:cache

# 3. Cache views (optional tapi recommended)
php artisan view:cache

# 4. Optimize autoloader
php artisan optimize

# Jika ada masalah, clear semua cache:
php artisan optimize:clear
```

**Catatan:**
- Setiap kali update `.env`, jalankan `php artisan config:cache` lagi
- Setiap kali update routes, jalankan `php artisan route:cache` lagi

---

### 7. **Setup Document Root & Permissions**

**A. Set Document Root ke folder `public/`**

Di cPanel:
1. Domains → Select domain → Document Root
2. Set ke: `/home/username/public_html/checkoutaja/public`

**B. Set File Permissions:**

```bash
# Set permission untuk storage dan cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (sesuaikan user server)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache

# Atau di shared hosting biasanya:
chown -R username:username storage
chown -R username:username bootstrap/cache
```

**C. Create Storage Link:**

```bash
php artisan storage:link
```

Ini membuat symbolic link dari `public/storage` → `storage/app/public` untuk akses gambar upload.

**Struktur akhir:**
```
/home/username/
  └── public_html/
      └── checkoutaja/          ← Upload semua file Laravel di sini
          ├── app/
          ├── bootstrap/
          ├── public/            ← Document root mengarah ke sini!
          │   ├── index.php
          │   ├── storage → ../../storage/app/public
          │   └── build/
          └── ...
```

---

### 8. **.htaccess Configuration**

**File `public/.htaccess` sudah ada dan dikonfigurasi dengan benar.**

Pastikan isi file sesuai:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**Jika document root TIDAK bisa diset ke `public/`:**

Tambahkan file `.htaccess` di **root** project:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### 9. **SSL Certificate Setup (HTTPS)**

**⚠️ WAJIB:** Website harus menggunakan HTTPS untuk:
- Firebase Push Notifications
- Google OAuth
- Service Worker
- Keamanan data user

**Cara Setup SSL:**

**A. Di cPanel (Gratis via Let's Encrypt):**
1. cPanel → SSL/TLS Status
2. Pilih domain Anda
3. Run AutoSSL atau Install Let's Encrypt
4. Tunggu 2-5 menit sampai aktif

**B. Di Cloudflare (Gratis):**
1. Tambah domain ke Cloudflare
2. Update nameserver domain
3. SSL/TLS → Full (Strict)
4. Auto HTTPS Rewrites → On

**C. Verifikasi HTTPS:**
```bash
# Test apakah SSL sudah aktif
curl -I https://yourdomain.com
```

---

### 10. **Testing Setelah Deploy**

**✅ Checklist Testing Lengkap:**

| Fitur | Test | Status |
|-------|------|--------|
| **Homepage** | Akses `https://yourdomain.com` | [ ] |
| **Login** | Login dengan admin@checkoutaja.com / admin123 | [ ] |
| **Register** | Buat akun baru | [ ] |
| **Admin Dashboard** | Lihat dashboard admin | [ ] |
| **Upload Produk** | Upload produk + gambar ke Supabase | [ ] |
| **Lihat Produk** | Akses detail produk via slug | [ ] |
| **Checkout** | Proses checkout WhatsApp | [ ] |
| **Notifikasi Database** | Cek tabel notifikasi terisi | [ ] |
| **Firebase Push** | Test push notification (jika setup) | [ ] |
| **Google OAuth** | Login dengan Google (jika setup) | [ ] |
| **Responsive** | Test di mobile & tablet | [ ] |
| **Performance** | Page load < 3 detik | [ ] |

**Commands untuk Testing:**

```bash
# Cek error logs
tail -f storage/logs/laravel.log

# Cek routes terdaftar
php artisan route:list

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Clear cache jika ada masalah
php artisan optimize:clear
```

---

## 🔧 Troubleshooting Common Issues

### **Problem: 500 Internal Server Error**

**Penyebab umum:**
- Permission folder salah
- `.env` tidak dikonfigurasi dengan benar
- Cache config bermasalah
- PHP version tidak sesuai (butuh PHP 8.1+)

**Solusi:**

```bash
# 1. Cek error di log
tail -f storage/logs/laravel.log

# 2. Set permission yang benar
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# 3. Clear semua cache
php artisan optimize:clear
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 4. Regenerate cache
php artisan config:cache
php artisan route:cache

# 5. Cek PHP version
php -v  # Harus 8.1 atau lebih tinggi
```

---

### **Problem: 404 Not Found (semua route)**

**Penyebab:**
- Document root tidak mengarah ke `public/`
- `.htaccess` tidak ada atau salah
- `mod_rewrite` tidak aktif

**Solusi:**

```bash
# 1. Pastikan document root ke public/
# cPanel → Domains → Document Root → /path/to/project/public

# 2. Cek .htaccess ada di public/
ls -la public/.htaccess

# 3. Test mod_rewrite
php artisan route:list  # Harus tampil semua routes

# 4. Force HTTPS redirect (tambahkan di public/.htaccess)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

### **Problem: APP_KEY not set**

**Solusi:**

```bash
# Generate APP_KEY baru
php artisan key:generate

# Atau edit manual di .env:
APP_KEY=base64:GENERATED_KEY_HERE
```

---

### **Problem: Storage symlink tidak jalan**

**Gejala:** Gambar upload tidak muncul, error 404 di `/storage/...`

**Solusi:**

```bash
# 1. Hapus link lama (jika ada)
rm -rf public/storage

# 2. Buat link baru
php artisan storage:link

# 3. Verifikasi
ls -la public/storage
# Harus ada symbolic link: public/storage -> ../../storage/app/public

# 4. Pastikan permission
chmod -R 775 storage/app/public
```

---

### **Problem: Broadcast/Reverb Connection Failed**

**Gejala:** Error di console browser tentang WebSocket

**Solusi:**

```env
# Di .env, disable broadcasting:
BROADCAST_CONNECTION=log
```

Broadcasting tidak wajib. Firebase Push Notification tetap jalan tanpa broadcasting.

---

### **Problem: Gambar tidak muncul (Supabase)**

**Penyebab:**
- `SUPABASE_URL` atau `SUPABASE_KEY` salah
- Bucket `product-images` belum dibuat
- Policies bucket tidak public

**Solusi:**

```bash
# 1. Cek credentials di .env
# 2. Login Supabase Dashboard → Storage
# 3. Buat bucket 'product-images' jika belum ada
# 4. Set bucket policies:

# Policy untuk public read:
{
  "id": "public-read",
  "allowed_operations": ["SELECT"],
  "definition": "bucket_id = 'product-images'"
}

# 5. Test upload manual di dashboard
# 6. Cek file bisa diakses via public URL
```

---

### **Problem: Firebase Notification tidak jalan**

**Penyebab:**
- `FIREBASE_SERVER_KEY` tidak diisi atau salah
- Service Worker tidak terdaftar
- User tidak kasih permission notifikasi
- Website tidak HTTPS

**Solusi:**

```bash
# 1. Cek FIREBASE_SERVER_KEY di .env
# Dapat dari: Firebase Console → Project Settings → Cloud Messaging → Server Key

# 2. Pastikan HTTPS aktif (wajib!)
curl -I https://yourdomain.com

# 3. Cek service worker terdaftar
# Buka browser DevTools → Application → Service Workers
# Harus ada: firebase-messaging-sw.js

# 4. Test manual:
# - Buka /penjual/dashboard atau /admin/dashboard
# - Klik "Aktifkan Notifikasi"
# - Allow permission
# - Lihat console untuk FCM Token

# 5. Cek log server
tail -f storage/logs/laravel.log | grep Firebase
```

---

### **Problem: Google OAuth Error (redirect_uri_mismatch)**

**Penyebab:** Redirect URI tidak match dengan yang didaftarkan di Google Console

**Solusi:**

```bash
# 1. Update .env
GOOGLE_REDIRECT_URI=https://yourdomain.com/auth/google/callback

# 2. Update di Google Cloud Console:
# https://console.cloud.google.com
# → Credentials → OAuth 2.0 Client IDs → Edit
# → Authorized redirect URIs:
#    https://yourdomain.com/auth/google/callback

# 3. Clear config cache
php artisan config:clear
php artisan config:cache
```

---

### **Problem: Composer dependencies error**

**Gejala:** Class not found, autoload error

**Solusi:**

```bash
# Reinstall dependencies
rm -rf vendor/
composer install --optimize-autoloader --no-dev

# Regenerate autoload
composer dump-autoload
```

---

### **Problem: Database connection refused**

**Penyebab:**
- Credentials salah
- Database belum dibuat
- Host salah (bukan 127.0.0.1 atau localhost)

**Solusi:**

```bash
# 1. Cek credentials di .env
DB_HOST=127.0.0.1      # atau hostname dari hosting
DB_DATABASE=dbname     # Pastikan db sudah dibuat
DB_USERNAME=username   # Pastikan user ada
DB_PASSWORD=password   # Pastikan password benar

# 2. Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# 3. Jika masih error, cek host alternatif:
DB_HOST=localhost
# atau
DB_HOST=mysql.yourdomain.com  # sesuai hosting
```

---

### **Problem: Session/Cache tidak persistent**

**Penyebab:** Cache driver tidak support atau permission salah

**Solusi:**

```env
# Di .env, gunakan database driver:
SESSION_DRIVER=database
CACHE_STORE=database

# Atau gunakan file:
SESSION_DRIVER=file
CACHE_STORE=file
```

```bash
# Clear cache
php artisan cache:clear
php artisan config:cache

# Set permission
chmod -R 775 storage/framework/sessions
chmod -R 775 storage/framework/cache
```

---

## 🎯 Post-Deployment Optimization

### **1. Performance Optimization**

```bash
# Enable OPcache (di php.ini atau .htaccess)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000

# Queue jobs untuk performa (jika hosting support)
php artisan queue:work --daemon

# Setup cron job untuk queue (cPanel → Cron Jobs)
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### **2. Security Hardening**

```apache
# Tambahkan di public/.htaccess

# Disable directory listing
Options -Indexes

# Security Headers
<IfModule mod_headers.c>
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-Content-Type-Options "nosniff"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Disable server signature
ServerSignature Off
```

### **3. Monitoring & Logging**

```bash
# Setup log rotation (jika server support)
# Edit /etc/logrotate.d/laravel

/path/to/project/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    notifempty
    create 0640 www-data www-data
}

# Monitor disk usage
du -sh storage/logs/

# Auto-clean old logs (tambah ke cron)
0 2 * * * find /path/to/project/storage/logs -name "*.log" -mtime +7 -delete
```

### **4. Backup Strategy**

**A. Database Backup (Otomatis):**

```bash
# Buat script backup-db.sh
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p'password' database_name > /backups/db_$DATE.sql
find /backups -name "db_*.sql" -mtime +7 -delete

# Jadwalkan di cron (setiap hari jam 2 pagi)
0 2 * * * /path/to/backup-db.sh
```

**B. File Backup:**
- Upload folder `storage/` ke cloud (Dropbox, Google Drive)
- Backup `public/storage/` (gambar user)
- Backup `.env` (simpan di tempat aman!)

### **5. SEO Optimization**

Website sudah dilengkapi:
- ✅ Sitemap.xml (auto-generated)
- ✅ Meta tags untuk SEO
- ✅ Open Graph tags
- ✅ Friendly URLs dengan slug
- ✅ Robots.txt

**Submit ke Search Engine:**
```bash
# Google Search Console
https://search.google.com/search-console

# Submit sitemap:
https://yourdomain.com/sitemap.xml
```

---

## 📊 Fitur yang Sudah Siap Production

### **✅ Authentication & Authorization**
- [x] Login/Register dengan validasi
- [x] Google OAuth integration
- [x] Forgot Password via email
- [x] Role-based access (Admin, Penjual, Pembeli)
- [x] Seller approval workflow

### **✅ E-Commerce Core**
- [x] Produk CRUD dengan gambar (Supabase)
- [x] Kategori management
- [x] WhatsApp checkout integration
- [x] Rating & review system
- [x] Order management (multi-role)

### **✅ Notifications**
- [x] Database notifications
- [x] Firebase Push Notifications
- [x] Real-time updates (optional via Reverb)
- [x] Email notifications

### **✅ User Experience**
- [x] Responsive design (mobile-first)
- [x] Image upload preview
- [x] Alert notifications (success/error/warning)
- [x] Loading states
- [x] Form validation

### **✅ Admin Features**
- [x] User management
- [x] Seller approval
- [x] Order management
- [x] Dashboard analytics

### **✅ Technical**
- [x] SEO-friendly URLs
- [x] Sitemap generation
- [x] Error handling
- [x] Security headers ready
- [x] HTTPS support
- [x] Production-optimized

---

## 📝 Maintenance Checklist (Bulanan)

- [ ] Update Laravel & dependencies: `composer update`
- [ ] Clear old logs: `find storage/logs -mtime +30 -delete`
- [ ] Backup database
- [ ] Check error logs: `tail -f storage/logs/laravel.log`
- [ ] Monitor disk usage: `df -h`
- [ ] Test critical features (login, checkout, upload)
- [ ] Update SSL certificate (jika perlu)
- [ ] Check uptime & performance

---

## 🎉 Quick Deploy Summary

**Sudah siap deploy? Ikuti 10 langkah ini:**

1. ✅ **Edit `.env`** → APP_DEBUG=false, APP_ENV=production, database config
2. ✅ **Build assets** → `npm run build` (sudah done!)
3. ✅ **Upload files** → Via FTP/cPanel (exclude .git, node_modules, tests)
4. ✅ **Install dependencies** → `composer install --no-dev`
5. ✅ **Generate key** → `php artisan key:generate`
6. ✅ **Run migration** → `php artisan migrate --force`
7. ✅ **Storage link** → `php artisan storage:link`
8. ✅ **Set permissions** → `chmod -R 775 storage bootstrap/cache`
9. ✅ **Cache config** → `php artisan config:cache && php artisan route:cache`
10. ✅ **Set document root** → Point to `public/` folder

**Total waktu:** 15-30 menit

---

## 🆘 Need Help?

**Common Commands Reference:**

```bash
# Clear all cache
php artisan optimize:clear

# Regenerate cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check logs
tail -f storage/logs/laravel.log

# Test database
php artisan tinker
>>> DB::connection()->getPdo();

# List routes
php artisan route:list

# Check disk usage
du -sh storage/
```

**Useful Links:**
- Laravel Docs: https://laravel.com/docs
- Supabase Docs: https://supabase.com/docs
- Firebase Docs: https://firebase.google.com/docs

---

## 📞 Support

**Jika ada masalah:**
1. Cek Troubleshooting section di atas
2. Lihat error di `storage/logs/laravel.log`
3. Google error message spesifik
4. Contact hosting support untuk server issues

---

**🚀 Selamat Deploy! Semoga sukses!**

*Panduan ini diupdate: February 3, 2026*
