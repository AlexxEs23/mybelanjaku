<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - UMKM Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center gap-2">
                    <span class="text-3xl">🛒</span>
                    <div>
                        <h1 class="text-xl font-bold text-white">UMKM Market</h1>
                        <p class="text-xs text-purple-200">Tambah Produk</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('produk.index') }}" class="text-white hover:text-purple-200 transition">
                        ← Kembali ke Daftar Produk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Produk Baru</h2>

            <form method="POST" action="{{ route('produk.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Nama Produk -->
                <div class="mb-6">
                    <label for="nama_produk" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Produk <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('nama_produk') border-red-500 @enderror"
                           placeholder="Masukkan nama produk">
                    @error('nama_produk')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="mb-6">
                    <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="kategori_id" id="kategori_id"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('kategori_id') border-red-500 @enderror">
                        <option value="">Pilih Kategori</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('kategori_id')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('deskripsi') border-red-500 @enderror"
                              placeholder="Jelaskan detail produk Anda">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Harga & Stok -->
                <div class="grid md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga (Rp) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga') }}" min="0" step="0.01"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('harga') border-red-500 @enderror"
                               placeholder="0">
                        @error('harga')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                            Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="stok" id="stok" value="{{ old('stok') }}" min="0"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('stok') border-red-500 @enderror"
                               placeholder="0">
                        @error('stok')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Gambar -->
                <div class="mb-6">
                    <label for="gambar" class="block text-sm font-medium text-gray-700 mb-2">
                        Gambar Produk
                    </label>
                    <input type="file" name="gambar" id="gambar" accept="image/*"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('gambar') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, GIF. Maksimal 2MB</p>
                    @error('gambar')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div class="mb-6">
                    <label for="nomor_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor WhatsApp Penjual <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_whatsapp" id="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" required
                           placeholder="Contoh: 6282006261837 (format: 62xxxxx tanpa +)"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('nomor_whatsapp') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">Masukkan nomor WhatsApp yang aktif untuk dihubungi pembeli (format: 62xxxxx tanpa tanda +)</p>
                    @error('nomor_whatsapp')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                               class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-700">Aktifkan produk ini</span>
                    </label>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-medium">
                        💾 Simpan Produk
                    </button>
                    <a href="{{ route('produk.index') }}" class="px-6 py-3 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
