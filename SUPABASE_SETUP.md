# Supabase Integration - Setup Guide

## 📦 Yang Sudah Diimplementasikan

### 1. **SupabaseService** (`app/Services/SupabaseService.php`)
Service untuk mengelola upload, delete, dan URL generation untuk Supabase Storage.

**Methods:**
- `uploadFile($file, $folder)` - Upload file ke Supabase Storage
- `deleteFile($path)` - Hapus file dari Supabase Storage  
- `getPublicUrl($path)` - Generate public URL untuk file
- `fileExists($path)` - Check apakah file exists

### 2. **ProdukController Integration**
Controller sudah diupdate untuk menggunakan Supabase:
- ✅ `store()` - Upload gambar ke Supabase saat create produk
- ✅ `update()` - Replace gambar lama dengan gambar baru di Supabase
- ✅ `destroy()` - Hapus gambar dari Supabase saat delete produk

### 3. **Produk Model Enhancement**
Tambahan attribute `image_url`:
```php
$produk->image_url // Returns full Supabase URL
```

### 4. **Views Updated** 
Semua 11 views sudah diupdate untuk menggunakan `$produk->image_url`:
- ✅ index.blade.php
- ✅ search/results.blade.php
- ✅ produk/detail.blade.php
- ✅ produk/index.blade.php
- ✅ produk/edit.blade.php
- ✅ produk/show.blade.php
- ✅ whatsapp/formCheckout.blade.php
- ✅ penjual/pesanan/index.blade.php
- ✅ penjual/pesanan/resi-form.blade.php
- ✅ pembeli/dashboard.blade.php
- ✅ pembeli/pesanan.blade.php

---

## 🚀 Setup Supabase Storage

### Step 1: Buat Storage Bucket di Supabase

1. Login ke [Supabase Dashboard](https://supabase.com/dashboard)
2. Pilih project Anda
3. Klik **Storage** di sidebar
4. Klik **New bucket**
5. Bucket name: `products`
6. **Public bucket**: ✅ CENTANG (agar gambar bisa diakses public)
7. Klik **Create bucket**

### Step 2: Set Bucket Policies (Optional - Jika Public)

Jika bucket tidak otomatis public, tambahkan policy:

```sql
-- Allow public read access
CREATE POLICY "Public Access"
ON storage.objects FOR SELECT
USING ( bucket_id = 'products' );

-- Allow authenticated uploads (untuk admin/penjual)
CREATE POLICY "Authenticated users can upload"
ON storage.objects FOR INSERT
TO authenticated
WITH CHECK ( bucket_id = 'products' );

-- Allow authenticated delete
CREATE POLICY "Authenticated users can delete own files"
ON storage.objects FOR DELETE
TO authenticated
USING ( bucket_id = 'products' );
```

### Step 3: Verify Configuration

Pastikan `.env` sudah diisi:
```env
SUPABASE_URL=https://zpdqqnsdhjnckezbyiws.supabase.co
SUPABASE_KEY=eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...
SUPABASE_STORAGE_BUCKET=products
```

### Step 4: Clear Cache
```bash
php artisan config:clear
php artisan config:cache
```

---

## 🧪 Testing

### Test Upload Produk Baru
1. Login sebagai **penjual**
2. Buka **Kelola Produk** → **Tambah Produk**
3. Upload gambar produk
4. Submit form
5. ✅ Gambar harus terupload ke Supabase
6. ✅ URL gambar di database: `produk/timestamp_uniqueid.jpg`
7. ✅ Gambar muncul di homepage

### Test Update Produk
1. Edit produk yang sudah ada
2. Upload gambar baru
3. ✅ Gambar lama terhapus dari Supabase
4. ✅ Gambar baru terupload

### Test Delete Produk
1. Delete produk
2. ✅ Gambar terhapus dari Supabase Storage

### Verifikasi di Supabase Dashboard
1. Buka **Storage** → **products**
2. Folder `produk/` harus berisi file yang diupload
3. Klik file → Copy URL → Paste di browser
4. ✅ Gambar harus bisa diakses public

---

## 🔧 Troubleshooting

### Problem: "Failed to upload to Supabase"
**Solusi:**
1. Pastikan bucket `products` sudah dibuat
2. Pastikan bucket diset sebagai **Public**
3. Check `.env` credentials benar
4. Run `php artisan config:clear`

### Problem: Gambar tidak muncul (broken image)
**Solusi:**
1. Check URL di inspect element
2. Pastikan format URL: `https://xxx.supabase.co/storage/v1/object/public/products/produk/xxx.jpg`
3. Test buka URL langsung di browser
4. Jika 404, check apakah file exists di Supabase Storage dashboard

### Problem: "Bucket not found"
**Solusi:**
1. Buka Supabase Dashboard → Storage
2. Pastikan bucket name = `products` (lowercase)
3. Update `.env` jika beda: `SUPABASE_STORAGE_BUCKET=nama-bucket-anda`

### Problem: CORS Error
**Solusi:**
1. Tidak perlu setup CORS karena menggunakan server-side upload
2. Jika tetap error, check apakah menggunakan Supabase service_role key (bukan anon key)

---

## 📊 Database Structure

Tidak ada perubahan struktur database. Field `gambar` tetap varchar:
- **Before**: Menyimpan path lokal `produk/xxx.jpg`
- **After**: Menyimpan path Supabase `produk/xxx.jpg`
- **Display**: Otomatis convert ke full URL via `$produk->image_url`

---

## 💡 Best Practices

1. **Gunakan Service Role Key** - Bukan anon key, agar bisa upload/delete
2. **Unique Filename** - SupabaseService otomatis generate `timestamp_uniqid.ext`
3. **Error Handling** - Semua upload wrapped dalam try-catch dengan logging
4. **Lazy Loading** - Views menggunakan `loading="lazy"` untuk performa
5. **Alt Attributes** - Semua image punya alt text untuk SEO

---

## 🎯 Next Steps (Optional)

- [ ] Image optimization sebelum upload (resize/compress)
- [ ] Multiple image upload per produk (gallery)
- [ ] Image CDN caching
- [ ] Thumbnail generation
- [ ] Image upload progress bar

---

## 📞 Support

Jika ada issue, check:
1. Laravel logs: `storage/logs/laravel.log`
2. Supabase Dashboard: Storage → products
3. Browser Console: Network tab untuk error URL

**Konfigurasi sudah selesai dan siap digunakan!** 🎉
