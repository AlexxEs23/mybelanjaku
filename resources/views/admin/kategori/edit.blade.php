<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori - Admin</title>
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
                        <p class="text-xs text-purple-200">Edit Kategori</p>
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
            <h2 class="text-3xl font-bold text-gray-800 mb-6">Edit Kategori</h2>

            <form method="POST" action="{{ route('kategori.update', $kategori->id) }}">
                @csrf
                @method('PUT')

                <!-- Nama Kategori -->
                <div class="mb-6">
                    <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama_kategori" id="nama_kategori" 
                           value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 @error('nama_kategori') border-red-500 @enderror"
                           placeholder="Contoh: Elektronik, Fashion, Makanan, dll">
                    @error('nama_kategori')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Masukkan nama kategori yang jelas dan mudah dipahami</p>
                </div>

                <!-- Info Produk -->
                <div class="mb-6 bg-blue-50 border-l-4 border-blue-400 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">
                                Kategori ini digunakan oleh <strong>{{ $kategori->produks()->count() }} produk</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-4">
                    <button type="submit" 
                            class="flex-1 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                        Perbarui Kategori
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
