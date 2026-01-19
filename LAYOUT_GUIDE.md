# 📐 Layout Dashboard Guide - CheckoutAja.com

## Struktur Layout Yang Benar

### 1. **Layout Utama** (`layouts/dashboard.blade.php`)

```blade
<body class="bg-gray-100">
    <!-- Sidebar: Fixed, width 256px (w-64) -->
    <aside id="sidebar" class="w-64 fixed top-0 left-0 h-screen z-30">
        <!-- Sidebar content -->
    </aside>

    <!-- Main Content: Offset oleh sidebar dengan lg:ml-64 -->
    <main class="lg:ml-64 w-full lg:w-auto min-h-screen bg-gray-100 pt-20 lg:pt-6 px-4 md:px-6 pb-6 overflow-x-hidden">
        @yield('content')
    </main>
</body>
```

**Penjelasan:**
- `lg:ml-64` → Memberi margin-left 256px (sama dengan lebar sidebar) di desktop
- `overflow-x-hidden` → Mencegah horizontal scroll
- `w-full lg:w-auto` → Full width di mobile, auto di desktop

---

### 2. **Content Pages** (semua halaman dashboard)

#### ❌ **SALAH - Jangan Pakai Ini:**
```blade
@extends('layouts.dashboard')

@section('content')
<div class="max-w-5xl mx-auto">  <!-- ❌ Ini bikin konten terlalu lebar! -->
    <!-- content -->
</div>
@endsection
```

#### ✅ **BENAR - Pakai Ini:**
```blade
@extends('layouts.dashboard')

@section('content')
<div class="w-full">  <!-- ✅ Full width dalam container parent -->
    <!-- content -->
</div>
@endsection
```

**Atau tanpa wrapper sama sekali:**
```blade
@extends('layouts.dashboard')

@section('content')
<!-- Header Card -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-800 rounded-2xl p-6 mb-6">
    <h2 class="text-2xl font-bold text-white">Title</h2>
</div>

<!-- Content -->
<div class="bg-white rounded-xl p-6">
    <!-- content -->
</div>
@endsection
```

---

### 3. **Ukuran Max-Width yang Aman**

| Class | Lebar | Kapan Dipakai | Status |
|-------|-------|---------------|--------|
| `max-w-7xl` (1280px) | 1280px | ❌ **JANGAN** - Terlalu lebar untuk layout dengan sidebar | Bermasalah |
| `max-w-5xl` (1024px) | 1024px | ❌ **JANGAN** - Masih terlalu lebar | Bermasalah |
| `max-w-4xl` (896px) | 896px | ⚠️ Hati-hati - Bisa bermasalah | Risky |
| `max-w-full` | 100% | ✅ **RECOMMENDED** | Aman |
| `w-full` | 100% | ✅ **RECOMMENDED** | Aman |

---

### 4. **Responsive Sidebar**

```blade
<aside id="sidebar" class="w-64 bg-gradient-to-b from-purple-800 to-purple-900 text-white fixed top-0 left-0 h-screen overflow-y-auto shadow-2xl z-30 transition-transform duration-300 lg:translate-x-0 -translate-x-full lg:block">
```

**Penjelasan:**
- Desktop (`lg:`): Sidebar always visible (`translate-x-0`)
- Mobile: Sidebar hidden by default (`-translate-x-full`)
- `transition-transform duration-300`: Smooth animation saat buka/tutup

---

### 5. **Contoh Layout Card yang Benar**

```blade
@extends('layouts.dashboard')

@section('content')
<!-- Header -->
<div class="bg-gradient-to-r from-purple-600 to-indigo-800 rounded-2xl shadow-xl p-6 mb-6 text-white">
    <div class="flex items-center gap-4">
        <span class="text-4xl">📊</span>
        <div>
            <h2 class="text-2xl font-bold">Dashboard</h2>
            <p class="text-purple-200">Selamat datang kembali!</p>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Total Produk</p>
                <h3 class="text-3xl font-bold text-purple-600">150</h3>
            </div>
            <span class="text-4xl">📦</span>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-xl font-bold text-gray-800">Data Terbaru</h3>
    </div>
    <div class="p-6">
        <!-- Table content -->
    </div>
</div>
@endsection
```

---

### 6. **Spacing & Padding Best Practices**

```blade
<!-- Main sudah punya padding, jadi content langsung tanpa padding tambahan -->
<main class="lg:ml-64 px-4 md:px-6 pb-6">
    @yield('content')
</main>

<!-- Di content pages, langsung pakai card tanpa padding luar -->
@section('content')
<div class="bg-white rounded-xl p-6">
    <!-- Padding di DALAM card, bukan di luar -->
</div>
@endsection
```

---

### 7. **Testing Checklist**

✅ **Pastikan hal ini:**
1. Sidebar tidak tertutup konten di desktop
2. Tidak ada horizontal scroll
3. Konten tidak keluar dari viewport
4. Responsive di mobile (sidebar bisa slide in/out)
5. Spacing konsisten di semua halaman

---

### 8. **File yang Sudah Diperbaiki**

✅ Sudah diupdate ke `w-full` atau `max-w-full`:
- `resources/views/chat/show.blade.php`
- `resources/views/chat/index.blade.php`
- `resources/views/notifikasi/index.blade.php`
- `resources/views/profile.blade.php`
- `resources/views/penjual/pesanan/index.blade.php`
- `resources/views/produk/index.blade.php`

---

### 9. **Troubleshooting**

#### Problem: Konten masih menutupi sidebar
**Solution:** Cek apakah pakai `mx-auto` atau `max-w-*xl`. Ganti dengan `w-full`

#### Problem: Horizontal scroll muncul
**Solution:** Tambahkan `overflow-x-hidden` di main element

#### Problem: Sidebar tidak muncul di mobile
**Solution:** Pastikan JavaScript untuk toggle sidebar sudah jalan

---

## 🎯 Kesimpulan

**Formula Layout yang Benar:**
```
Sidebar (w-64, fixed) + Main Content (lg:ml-64, w-full, overflow-x-hidden)
```

**Content Pages:**
```blade
@extends('layouts.dashboard')
@section('content')
    <!-- Langsung card tanpa wrapper max-w-* -->
    <div class="bg-white rounded-xl p-6">
        Content here
    </div>
@endsection
```

---

**Updated:** 13 Januari 2026  
**Author:** GitHub Copilot
