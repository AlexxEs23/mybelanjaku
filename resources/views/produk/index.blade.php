<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - UMKM Market</title>
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
                        <p class="text-xs text-purple-200">Kelola Produk</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-purple-200 transition">
                        ← Kembali ke Dashboard
                    </a>
                    <div class="text-right">
                        <p class="text-white font-semibold">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-purple-200">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-green-500 mr-2 text-xl">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-red-500 mr-2 text-xl">✕</span>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Daftar Produk</h2>
                    @if(Auth::user()->role === 'admin')
                        <p class="text-gray-600 mt-1">Semua produk dari seluruh penjual</p>
                    @else
                        <p class="text-gray-600 mt-1">Kelola produk toko Anda</p>
                    @endif
                </div>
                
                @if(Auth::user()->role === 'penjual')
                    <a href="{{ route('produk.create') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-medium">
                        ➕ Tambah Produk
                    </a>
                @endif
            </div>

            @if($produk->isEmpty())
                <div class="text-center py-16">
                    <div class="text-8xl mb-4">📦</div>
                    <p class="text-xl text-gray-600 mb-4">Belum ada produk</p>
                    @if(Auth::user()->role === 'penjual')
                        <a href="{{ route('produk.create') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Tambah Produk Pertama
                        </a>
                    @endif
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b-2 border-gray-200">
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Gambar</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Nama Produk</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kategori</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Penjual</th>
                                @endif
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Harga</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Stok</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Status</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($produk as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-4">
                                        @if($item->gambar)
                                            <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama_produk }}" class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-2xl">📦</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-gray-800">{{ $item->nama_produk }}</p>
                                        <p class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($item->deskripsi, 50) }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                            {{ $item->kategori->nama_kategori }}
                                        </span>
                                    </td>
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-4 py-4">
                                            <p class="text-sm text-gray-700">{{ $item->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                                        </td>
                                    @endif
                                    <td class="px-4 py-4">
                                        <p class="font-semibold text-gray-800">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($item->stok > 10)
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $item->stok }}</span>
                                        @elseif($item->stok > 0)
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">{{ $item->stok }}</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm">Habis</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        @if($item->status)
                                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Aktif</span>
                                        @else
                                            <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-sm">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('produk.show', $item->id) }}" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm">
                                                👁️ Lihat
                                            </a>
                                            
                                            @if(Auth::user()->role === 'penjual')
                                                <a href="{{ route('produk.edit', $item->id) }}" class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm">
                                                    ✏️ Edit
                                                </a>
                                                
                                                <form method="POST" action="{{ route('produk.destroy', $item->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm">
                                                        🗑️ Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $produk->links() }}
                </div>
            @endif
        </div>
    </div>
</body>
</html>
