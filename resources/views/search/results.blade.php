<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian: {{ $query }} - MyBelanjaMu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Smooth transitions */
        * {
            transition: all 0.2s ease;
        }
        
        /* Hide scrollbar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    
    <!-- Main Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="{{ asset('img/images-removebg-preview (1).png') }}" alt="MyBelanjaMu Logo" class="h-10 sm:h-12 w-auto">
                    <h1 class="text-lg sm:text-2xl font-bold">
                        <span class="bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">My</span><span class="text-purple-700">Belanja</span><span class="bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">Mu</span>
                    </h1>
                </a>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Cari produk, toko, atau kategori..." 
                                   value="{{ $query }}"
                                   class="w-full px-4 py-2 pr-12 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-700">
                            <button type="submit" class="absolute right-0 top-0 h-full px-6 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 transition">
                                🔍
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right Menu -->
                <div class="flex items-center gap-4">
                    @guest
                        <a href="{{ url('/login') }}" class="px-4 py-2 text-purple-600 border-2 border-purple-600 rounded-lg hover:bg-purple-50 transition font-medium">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-medium">
                            Daftar
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition font-medium">
                            Dashboard
                        </a>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 py-3">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-purple-600">Beranda</a>
                <span>›</span>
                <span class="text-gray-900 font-medium">Hasil Pencarian</span>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        
        <!-- Search Info -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                Hasil Pencarian: "{{ $query }}"
            </h1>
            <p class="text-gray-600">
                Ditemukan <span class="font-semibold text-purple-600">{{ $products->total() }}</span> produk
            </p>
        </div>

        <!-- Filter & Sort -->
        <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <!-- Category Filter -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Kategori:</span>
                    <div class="flex gap-2">
                        <a href="{{ route('search', ['q' => $query, 'sort' => request('sort')]) }}" 
                           class="px-3 py-1 text-sm {{ !request('kategori') ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg">
                            Semua
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('search', ['q' => $query, 'kategori' => $category->id, 'sort' => request('sort')]) }}" 
                           class="px-3 py-1 text-sm {{ request('kategori') == $category->id ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg">
                            {{ $category->nama_kategori }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <!-- Sort Options -->
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium text-gray-700">Urutkan:</span>
                    <div class="flex gap-2">
                        <a href="{{ route('search', ['q' => $query, 'kategori' => request('kategori'), 'sort' => 'terbaru']) }}" 
                           class="px-3 py-1 text-sm {{ request('sort', 'terbaru') == 'terbaru' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg">
                            Terbaru
                        </a>
                        <a href="{{ route('search', ['q' => $query, 'kategori' => request('kategori'), 'sort' => 'terlaris']) }}" 
                           class="px-3 py-1 text-sm {{ request('sort') == 'terlaris' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg">
                            Terlaris
                        </a>
                        <a href="{{ route('search', ['q' => $query, 'kategori' => request('kategori'), 'sort' => 'termurah']) }}" 
                           class="px-3 py-1 text-sm {{ request('sort') == 'termurah' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg">
                            Termurah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
                @foreach($products as $product)
                <div class="bg-white border border-gray-200 rounded-xl hover:shadow-xl transition overflow-hidden group">
                    <a href="{{ route('produk.detail', $product->slug ?? $product->id) }}" class="block">
                        <div class="relative">
                            <div class="aspect-square bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center overflow-hidden">
                                @if($product->gambar)
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->nama_produk }} - {{ $product->kategori->nama_kategori ?? 'Produk' }} | Belanja di MyBelanjaMu"
                                         title="{{ $product->nama_produk }}"
                                         loading="lazy"
                                         class="w-full h-full object-cover group-hover:scale-105 transition">
                                @else
                                    <div class="text-7xl group-hover:scale-105 transition">📦</div>
                                @endif
                            </div>
                        </div>
                        <div class="p-3">
                            <h3 class="text-sm font-medium text-gray-800 mb-1 line-clamp-2 h-10">{{ $product->nama_produk }}</h3>
                            <div class="flex items-center gap-1 mb-2">
                                <span class="text-yellow-400 text-sm">⭐</span>
                                <span class="text-xs font-semibold text-gray-700">{{ $product->averageRating() }}</span>
                                <span class="text-xs text-gray-500">({{ $product->totalRatings() }})</span>
                            </div>
                            <div class="text-xs text-gray-500 mb-2">
                                <span class="px-2 py-1 bg-purple-50 text-purple-600 rounded">{{ $product->kategori->nama_kategori ?? 'Umum' }}</span>
                            </div>
                            <div class="flex items-baseline gap-2 mb-2">
                                <span class="text-lg font-bold text-purple-600">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center gap-1 text-xs text-gray-500 mb-3">
                                <span>📦</span>
                                <span>Stok: {{ $product->stok }}</span>
                            </div>
                        </div>
                    </a>
                    
                    <div class="px-3 pb-3">
                        @if($product->stok > 0 && $product->status)
                            <a href="{{ route('whatsapp.checkout.show', $product->id) }}" 
                               class="group relative overflow-hidden block w-full px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-center text-sm font-bold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95">
                                <span class="relative z-10 flex items-center justify-center gap-2">
                                    <span>💬</span>
                                    <span>Beli via WhatsApp</span>
                                </span>
                            </a>
                        @else
                            <button disabled class="w-full px-4 py-3 bg-gray-300 text-gray-600 text-center text-sm font-bold rounded-xl cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center">
                {{ $products->appends(request()->query())->links() }}
            </div>

        @else
            <!-- Empty State -->
            <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                <div class="text-6xl mb-4">🔍</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Produk Tidak Ditemukan</h3>
                <p class="text-gray-600 mb-6">Maaf, kami tidak menemukan produk yang cocok dengan pencarian Anda.</p>
                <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                    Kembali ke Beranda
                </a>
            </div>
        @endif

    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-purple-900 to-purple-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- About -->
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('img/images-removebg-preview (1).png') }}" alt="MyBelanjaMu Logo" class="h-10 w-auto">
                        <h3 class="text-xl font-bold">
                            <span class="text-purple-200">My</span><span class="text-white">Belanja</span><span class="text-purple-200">Mu</span>
                        </h3>
                    </div>
                    <p class="text-purple-200 text-sm">Platform belanja online terpercaya dengan produk berkualitas dan harga terbaik.</p>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h4 class="font-bold mb-4">Link Cepat</h4>
                    <ul class="space-y-2 text-purple-200">
                        <li><a href="{{ route('home') }}" class="hover:text-white transition">Beranda</a></li>
                        <li><a href="#" class="hover:text-white transition">Tentang Kami</a></li>
                        <li><a href="#" class="hover:text-white transition">Bantuan</a></li>
                    </ul>
                </div>
                
                <!-- Customer Service -->
                <div>
                    <h4 class="font-bold mb-4">Layanan Pelanggan</h4>
                    <ul class="space-y-2 text-purple-200">
                        <li><a href="#" class="hover:text-white transition">Cara Berbelanja</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                
                <!-- Social Media -->
                <div>
                    <h4 class="font-bold mb-4">Ikuti Kami</h4>
                    <div class="flex gap-4">
                        <a href="https://www.instagram.com/mybelanjamu" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-purple-700 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                        <a href="https://www.facebook.com/profile.php?id=100087532447096" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-purple-700 rounded-full flex items-center justify-center hover:bg-purple-600 transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-purple-700 mt-8 pt-8 text-center text-purple-200 text-sm">
                <p>&copy; 2025 MyBelanjaMu. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
