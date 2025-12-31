<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Produk - UMKM Market</title>
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
                        <p class="text-xs text-purple-200">Detail Produk</p>
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
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="grid md:grid-cols-2 gap-8 p-8">
                <!-- Gambar Produk -->
                <div>
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" class="w-full h-96 object-cover rounded-xl shadow-md">
                    @else
                        <div class="w-full h-96 bg-gray-200 rounded-xl flex items-center justify-center text-8xl">
                            📦
                        </div>
                    @endif
                </div>

                <!-- Detail Produk -->
                <div>
                    <div class="mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                            {{ $produk->kategori->nama_kategori }}
                        </span>
                        @if($produk->status)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm ml-2">Aktif</span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm ml-2">Nonaktif</span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-bold text-gray-800 mb-4">{{ $produk->nama_produk }}</h1>

                    <div class="mb-6">
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-6">
                        <div class="flex items-center gap-3">
                            <span class="text-gray-600 font-medium w-24">Stok:</span>
                            @if($produk->stok > 10)
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">{{ $produk->stok }} unit</span>
                            @elseif($produk->stok > 0)
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">{{ $produk->stok }} unit</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">Stok Habis</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-gray-600 font-medium w-24">Penjual:</span>
                            <span class="text-gray-800 font-semibold">{{ $produk->user->name }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-gray-600 font-medium w-24">Email:</span>
                            <span class="text-gray-800">{{ $produk->user->email }}</span>
                        </div>

                        @if($produk->user->no_hp)
                            <div class="flex items-center gap-3">
                                <span class="text-gray-600 font-medium w-24">No HP:</span>
                                <span class="text-gray-800">{{ $produk->user->no_hp }}</span>
                            </div>
                        @endif

                        <div class="flex items-center gap-3">
                            <span class="text-gray-600 font-medium w-24">Dibuat:</span>
                            <span class="text-gray-800">{{ $produk->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        <div class="flex items-center gap-3">
                            <span class="text-gray-600 font-medium w-24">Diupdate:</span>
                            <span class="text-gray-800">{{ $produk->updated_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>

                    @if(Auth::user()->role === 'penjual' && Auth::id() === $produk->user_id)
                        <div class="flex gap-3 mb-6">
                            <a href="{{ route('produk.edit', $produk->id) }}" class="flex-1 px-6 py-3 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition font-medium text-center">
                                ✏️ Edit Produk
                            </a>
                            <form method="POST" action="{{ route('produk.destroy', $produk->id) }}" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-6 py-3 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium">
                                    🗑️ Hapus Produk
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="border-t pt-6">
                        <h2 class="text-xl font-bold text-gray-800 mb-3">Deskripsi Produk</h2>
                        <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $produk->deskripsi }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
