# 📍 Lokasi Inputan Kategori - Panduan

## ✅ Inputan Kategori Sudah Ada di:

### 1. **Form Tambah Produk**
📂 **Lokasi File**: `resources/views/produk/create.blade.php`
📍 **Baris**: 52-66

**Tampilan:**
```html
<!-- Kategori -->
<div class="mb-6">
    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
        Kategori <span class="text-red-500">*</span>
    </label>
    <select name="kategori_id" id="kategori_id"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
        <option value="">Pilih Kategori</option>
        @foreach($kategori as $kat)
            <option value="{{ $kat->id }}">
                {{ $kat->nama_kategori }}
            </option>
        @endforeach
    </select>
</div>
```

**Cara Akses:**
1. Login sebagai **Penjual** yang sudah approved
2. Klik **"Tambah Produk Baru"** di dashboard atau menu
3. URL: `/produk/create`
4. Form akan menampilkan dropdown kategori

---

### 2. **Form Edit Produk**
📂 **Lokasi File**: `resources/views/produk/edit.blade.php`
📍 **Baris**: 54-68

**Tampilan:**
```html
<!-- Kategori -->
<div class="mb-6">
    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
        Kategori <span class="text-red-500">*</span>
    </label>
    <select name="kategori_id" id="kategori_id"
            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600">
        <option value="">Pilih Kategori</option>
        @foreach($kategori as $kat)
            <option value="{{ $kat->id }}" {{ old('kategori_id', $produk->kategori_id) == $kat->id ? 'selected' : '' }}>
                {{ $kat->nama_kategori }}
            </option>
        @endforeach
    </select>
</div>
```

**Cara Akses:**
1. Login sebagai **Penjual**
2. Masuk ke halaman **"Daftar Produk"**
3. Klik icon **Edit (pensil)** pada produk yang ingin diedit
4. URL: `/produk/{id}/edit`
5. Form akan menampilkan dropdown kategori dengan nilai existing produk ter-selected

---

## 🏪 Cara Mengelola Kategori

### **Admin & Penjual dapat:**

#### 1. **Tambah Kategori Baru**
- Akses: Dashboard → **"Kelola Kategori"**
- URL: `/kategori`
- Klik tombol **"Tambah Kategori"**
- Isi nama kategori (contoh: Elektronik, Fashion, Makanan)
- Klik **"Simpan Kategori"**

#### 2. **Edit Kategori**
- Masuk ke halaman **"Kelola Kategori"**
- Klik icon **Edit** pada kategori
- Ubah nama kategori
- Klik **"Perbarui Kategori"**

#### 3. **Hapus Kategori**
- Masuk ke halaman **"Kelola Kategori"**
- Klik icon **Hapus** pada kategori
- Kategori hanya bisa dihapus jika tidak ada produk yang menggunakannya

---

## 🎯 Alur Penggunaan Kategori

### **Scenario 1: Penjual Baru Menambah Produk**

```
1. Login sebagai Penjual
   ↓
2. Jika belum ada kategori → Klik "Kelola Kategori" → Tambah kategori baru
   ↓
3. Klik "Tambah Produk Baru"
   ↓
4. Isi form produk (nama, deskripsi, harga, dll)
   ↓
5. Pilih KATEGORI dari dropdown ⬅️ INPUTAN KATEGORI DI SINI
   ↓
6. Upload gambar produk
   ↓
7. Klik "Simpan Produk"
```

### **Scenario 2: Penjual Edit Produk Existing**

```
1. Login sebagai Penjual
   ↓
2. Masuk ke "Daftar Produk"
   ↓
3. Klik icon Edit pada produk
   ↓
4. Form edit akan tampil dengan kategori saat ini sudah ter-select
   ↓
5. Ubah kategori jika diperlukan ⬅️ INPUTAN KATEGORI DI SINI
   ↓
6. Klik "Update Produk"
```

---

## 📊 Data Controller yang Mengirim Kategori

### **ProdukController.php**

#### Method `create()` - Line 42-50
```php
public function create()
{
    // Hanya penjual yang bisa akses
    if (Auth::user()->role === 'admin') {
        return redirect()->route('produk.index')
                       ->with('error', 'Admin hanya dapat melihat produk, tidak dapat menambah produk.');
    }
    
    $kategori = Kategori::all();  // ⬅️ Ambil semua kategori
    return view('produk.create', compact('kategori'));  // ⬅️ Kirim ke view
}
```

#### Method `edit()` - Line 140-158
```php
public function edit(string $id)
{
    $produk = Produk::findOrFail($id);
    
    // Admin tidak bisa edit
    if (Auth::user()->role === 'admin') {
        return redirect()->route('produk.index')
                       ->with('error', 'Admin hanya dapat melihat produk, tidak dapat mengedit produk.');
    }
    
    // Penjual hanya bisa edit produknya sendiri
    if ($produk->user_id !== Auth::id()) {
        return redirect()->route('produk.index')
                       ->with('error', 'Anda tidak memiliki akses untuk mengedit produk ini');
    }
    
    $kategori = Kategori::all();  // ⬅️ Ambil semua kategori
    return view('produk.edit', compact('produk', 'kategori'));  // ⬅️ Kirim ke view
}
```

---

## ✅ Validasi Kategori

### **Di ProdukController**

#### Method `store()` - Validasi saat tambah produk
```php
$validated = $request->validate([
    'kategori_id' => 'required|exists:kategoris,id',  // ⬅️ Wajib diisi & harus ada di tabel kategoris
    'nama_produk' => 'required|string|max:255',
    // ... validasi lainnya
]);
```

#### Method `update()` - Validasi saat edit produk
```php
$validated = $request->validate([
    'kategori_id' => 'required|exists:kategoris,id',  // ⬅️ Wajib diisi & harus ada di tabel kategoris
    'nama_produk' => 'required|string|max:255',
    // ... validasi lainnya
]);
```

### **Error Messages Custom**
```php
'kategori_id.required' => 'Kategori harus dipilih',
'kategori_id.exists' => 'Kategori tidak valid',
```

---

## 🗄️ Database Schema

### **Tabel: kategoris**
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
nama_kategori   VARCHAR(255) UNIQUE NOT NULL
created_at      TIMESTAMP
updated_at      TIMESTAMP
```

### **Tabel: produks**
```sql
id              INT PRIMARY KEY AUTO_INCREMENT
kategori_id     INT NOT NULL  ⬅️ Foreign Key ke kategoris.id
nama_produk     VARCHAR(255)
deskripsi       TEXT
harga           DECIMAL
stok            INT
...
```

### **Relasi:**
- 1 Kategori → Banyak Produk (One to Many)
- 1 Produk → 1 Kategori (Belongs To)

---

## 🎨 Preview Inputan Kategori

### **Tampilan Dropdown di Form:**
```
┌─────────────────────────────────────┐
│ Kategori *                          │
├─────────────────────────────────────┤
│ Pilih Kategori              ▼       │  ⬅️ Default option
├─────────────────────────────────────┤
│ - Elektronik                        │  ⬅️ Dari database
│ - Fashion                           │
│ - Makanan & Minuman                 │
│ - Kesehatan & Kecantikan            │
│ - Perlengkapan Rumah                │
└─────────────────────────────────────┘
```

### **Jika Belum Ada Kategori:**
```
┌─────────────────────────────────────┐
│ Kategori *                          │
├─────────────────────────────────────┤
│ Pilih Kategori              ▼       │
└─────────────────────────────────────┘
```
**Solusi:** Admin/Penjual harus tambah kategori dulu via menu "Kelola Kategori"

---

## 🔍 Testing Checklist

- [x] Dropdown kategori muncul di form tambah produk
- [x] Dropdown kategori muncul di form edit produk
- [x] Kategori dari database tampil di dropdown
- [x] Validasi required berfungsi (tidak bisa simpan tanpa pilih kategori)
- [x] Selected kategori ter-retain saat edit produk
- [x] Error message muncul jika kategori tidak dipilih

---

## 📞 Troubleshooting

### ❌ **Dropdown kategori kosong/tidak muncul**
**Penyebab:** Belum ada kategori di database

**Solusi:**
1. Login sebagai Admin atau Penjual
2. Masuk ke menu "Kelola Kategori"
3. Tambah minimal 1 kategori baru
4. Kembali ke form tambah produk
5. Dropdown akan menampilkan kategori yang baru ditambahkan

### ❌ **Error "Kategori harus dipilih"**
**Penyebab:** User submit form tanpa memilih kategori

**Solusi:**
1. Pilih salah satu kategori dari dropdown
2. Submit form lagi

### ❌ **Kategori tidak muncul setelah ditambahkan**
**Penyebab:** Cache atau perlu refresh

**Solusi:**
1. Refresh halaman (F5)
2. Atau clear cache: `php artisan cache:clear`

---

## 📝 Summary

| Fitur | Lokasi | Akses |
|-------|--------|-------|
| **Input Kategori di Produk** | Form Create/Edit Produk | Penjual |
| **Kelola Kategori** | `/kategori` | Admin & Penjual |
| **Dropdown Kategori** | `produk/create.blade.php` Line 52-66 | Penjual |
| **Dropdown Kategori** | `produk/edit.blade.php` Line 54-68 | Penjual |

---

**Author**: GitHub Copilot  
**Date**: 19 Januari 2026  
**Version**: 1.0
