@extends('layouts.dashboard')

@section('content')
<div class="w-full">
    @if (session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">✅</span>
                <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">❌</span>
                <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Header Card -->
    <div class="bg-purple-700 rounded-2xl shadow-2xl mb-6 p-6 md:p-8 text-white">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold text-white mb-1">📦 Daftar Produk</h2>
                    @if(Auth::user()->role === 'admin')
                        <p class="text-purple-200 text-sm md:text-base">Semua produk dari seluruh penjual</p>
                    @else
                        <p class="text-purple-200 text-sm md:text-base">Kelola produk toko Anda</p>
                    @endif
                </div>
                
                @if(Auth::user()->role === 'penjual')
                    <a href="{{ route('produk.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-white text-purple-700 rounded-xl hover:bg-purple-50 transition-all duration-200 font-bold shadow-lg hover:shadow-xl text-sm md:text-base">
                        <span>➕</span>
                        <span>Tambah Produk</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if($produk->isEmpty())
        <div class="bg-white rounded-2xl shadow-xl p-12 md:p-16 text-center">
            <div class="text-6xl md:text-8xl mb-4">📦</div>
            <p class="text-lg md:text-xl text-gray-600 mb-4 font-semibold">Belum ada produk</p>
            @if(Auth::user()->role === 'penjual')
                <a href="{{ route('produk.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl">
                    <span>➕</span>
                    <span>Tambah Produk Pertama</span>
                </a>
            @endif
        </div>
    @else
        <!-- Desktop Table View -->
        <div class="hidden lg:block bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100 border-b-2 border-gray-200">
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Gambar</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Nama Produk</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Kategori</th>
                            @if(Auth::user()->role === 'admin')
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Penjual</th>
                            @endif
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Harga</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Stok</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase">Status</th>
                            <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($produk as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-4">
                                    @if($item->gambar)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->nama_produk }}" class="w-16 h-16 object-cover rounded-lg shadow-md">
                                    @else
                                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center text-2xl">📦</div>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-gray-800">{{ $item->nama_produk }}</p>
                                    <p class="text-sm text-gray-500 truncate max-w-xs">{{ Str::limit($item->deskripsi, 50) }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                        {{ $item->kategori->nama_kategori }}
                                    </span>
                                </td>
                                @if(Auth::user()->role === 'admin')
                                    <td class="px-4 py-4">
                                        <p class="text-sm font-medium text-gray-700">{{ $item->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $item->user->email }}</p>
                                    </td>
                                @endif
                                <td class="px-4 py-4">
                                    <p class="font-bold text-gray-800">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if($item->stok > 10)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">{{ $item->stok }}</span>
                                    @elseif($item->stok > 0)
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold">{{ $item->stok }}</span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-bold">Habis</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    @if($item->status)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">✓ Aktif</span>
                                    @else
                                        <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">○ Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('produk.show', $item->id) }}" class="px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-xs font-medium">
                                            👁️
                                        </a>
                                        
                                        @if(Auth::user()->role === 'penjual')
                                            <a href="{{ route('produk.edit', $item->id) }}" class="px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-xs font-medium">
                                                ✏️
                                            </a>
                                            
                                            <form method="POST" action="{{ route('produk.destroy', $item->id) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-xs font-medium">
                                                    🗑️
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
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($produk as $item)
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border-2 border-gray-200 hover:border-purple-400 transition-all duration-200">
                    @if($item->gambar)
                        <img src="{{ $item->image_url }}" alt="{{ $item->nama_produk }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-6xl">📦</div>
                    @endif
                    
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">
                                {{ $item->kategori->nama_kategori }}
                            </span>
                            @if($item->status)
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-bold">✓</span>
                            @else
                                <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs font-bold">○</span>
                            @endif
                        </div>
                        
                        <h3 class="font-bold text-gray-800 mb-1 text-lg">{{ $item->nama_produk }}</h3>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ Str::limit($item->deskripsi, 60) }}</p>
                        
                        @if(Auth::user()->role === 'admin')
                            <div class="mb-3 pb-3 border-b border-gray-200">
                                <p class="text-xs text-gray-600">Penjual:</p>
                                <p class="text-sm font-medium text-gray-800">{{ $item->user->name }}</p>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center mb-3">
                            <div>
                                <p class="text-xs text-gray-600">Harga</p>
                                <p class="font-bold text-purple-600 text-lg">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">Stok</p>
                                @if($item->stok > 10)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-bold">{{ $item->stok }}</span>
                                @elseif($item->stok > 0)
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-bold">{{ $item->stok }}</span>
                                @else
                                    <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-bold">Habis</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="{{ route('produk.show', $item->id) }}" class="flex-1 px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition text-sm font-medium text-center">
                                👁️ Lihat
                            </a>
                            
                            @if(Auth::user()->role === 'penjual')
                                <a href="{{ route('produk.edit', $item->id) }}" class="flex-1 px-3 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition text-sm font-medium text-center">
                                    ✏️ Edit
                                </a>
                                
                                <form method="POST" action="{{ route('produk.destroy', $item->id) }}" class="flex-1" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-3 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition text-sm font-medium">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $produk->links() }}
        </div>
    @endif
</div>
@endsection
