<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori - Penjual</title>
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
                        <h1 class="text-xl font-bold text-white">CheckoutAja</h1>
                        <p class="text-xs text-purple-200">Tambah Kategori</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('kategori.index') }}" class="text-white hover:text-purple-200 transition">
                        ← Kembali ke Daftar Kategori
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Kategori Baru</h2>

            <form method="POST" action="{{ route('kategori.store') }}">
                @csrf

                <!-- Nama Kategori -->
                <div class="mb-6">
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kategori" id="nama_kategori" value="{{ old('nama_kategori') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('nama_kategori') border-red-500 @enderror"
                           placeholder="Contoh: Elektronik, Fashion, Makanan, dll">
                    @error('nama_kategori')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Masukkan nama kategori yang jelas dan mudah dipahami</p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                        Simpan Kategori
                    </button>
                    <a href="{{ route('kategori.index') }}" 
                       class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold px-6 py-3 rounded-lg transition text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
