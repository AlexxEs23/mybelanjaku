<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Meta Tags --}}
    <title>CheckoutAja - Belanja Online Produk UMKM Lokal Indonesia Terpercaya</title>
    <meta name="description" content="Platform marketplace terpercaya untuk produk UMKM Indonesia. Belanja online produk lokal berkualitas dengan harga terbaik. Dukung pengusaha lokal Indonesia dengan berbelanja di CheckoutAja.">
    <meta name="keywords" content="belanja online Indonesia, UMKM, produk lokal, marketplace UMKM, belanja produk Indonesia, CheckoutAja, toko online terpercaya">
    <meta name="author" content="CheckoutAja.com">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('home') }}">
    
    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:title" content="CheckoutAja - Belanja Produk UMKM Lokal Indonesia">
    <meta property="og:description" content="Platform marketplace terpercaya untuk produk UMKM Indonesia. Belanja online produk lokal berkualitas dengan harga terbaik.">
    <meta property="og:image" content="{{ asset('images/og-home.png') }}">
    <meta property="og:site_name" content="CheckoutAja.com">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from { transform: translateX(100%); }
            to { transform: translateX(-100%); }
        }
        .animate-slide { animation: slideIn 20s linear infinite; }
        
        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>
<body class="bg-white min-h-screen overflow-x-hidden">
    
    <!-- Top Bar -->
   

    <!-- Main Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <div class="flex items-center gap-1">
                    <img src="{{ asset('img/Gemini_Generated_Image_pt2ptxpt2ptxpt2p-removebg-preview.png') }}" alt="CheckoutAja.com Logo" class="h-16 sm:h-20 w-auto">
                    <h1 class="text-xl sm:text-3xl font-black">
                        <span class="text-purple-700">Checkout</span><span class="text-purple-600">Aja</span><span class="text-purple-500">.com</span>
                    </h1>
                </div>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Cari produk, toko, atau kategori..." 
                                   value="{{ request('q') }}"
                                   class="w-full px-4 py-2 pr-12 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-800 shadow-md">
                            <button type="submit" class="absolute right-0 top-0 h-full px-6 bg-purple-700 text-yellow-400 rounded-r-lg hover:bg-purple-900 transition font-bold shadow-md">
                                🔍
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right Menu -->
                <div class="flex items-center gap-4">
                    <button class="relative">
                        
                       
                    </button>
                    
                    @guest
                        <a href="{{ url('/login') }}" class="px-4 py-2 text-black hover:text-purple-700 font-bold transition">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" class="px-4 py-2 bg-purple-700 text-yellow-400 rounded-lg hover:bg-purple-900 hover:text-white transition font-bold shadow-md">
                            Daftar
                        </a>
                    @else
                        @if(Auth::user()->role === 'pembeli' || Auth::user()->role === 'user')
                            <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-2 px-4 py-2 bg-purple-100 rounded-lg hover:bg-purple-200 transition">
                                <span class="text-2xl">👤</span>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">Dashboard</p>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 bg-purple-100 rounded-lg hover:bg-purple-200 transition">
                                <span class="text-2xl">👤</span>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">Dashboard</p>
                                </div>
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
            
            <!-- Category Menu -->
            <div class="border-t border-gray-200 py-3">
                <div class="flex items-center gap-3 sm:gap-6 text-xs sm:text-sm overflow-x-auto scrollbar-hide pb-2 sm:pb-0">
                    <a href="#" class="text-gray-700 hover:text-purple-600 transition font-medium whitespace-nowrap">🏠 Semua Kategori</a>
                    @foreach($categories as $category)
                        <a href="#" class="text-gray-700 hover:text-purple-600 transition whitespace-nowrap">{{ $category->nama_kategori }}</a>
                    @endforeach
                    <a href="#" class="text-purple-600 font-medium whitespace-nowrap">🔥 Promo Hari Ini</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Banner Carousel -->
    <section class="bg-white py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-purple-700 border-4 border-yellow-400 rounded-2xl overflow-hidden shadow-2xl">
                <div class="flex items-center justify-between p-12">
                    <div class="max-w-xl">
                        <h2 class="text-5xl font-black mb-4 text-yellow-400">Produk Unggulan UMKM Indonesia</h2>
                        <p class="text-2xl mb-6 font-bold text-white">Temukan berbagai produk berkualitas dari pengrajin dan pengusaha lokal terpercaya</p>
                        <button class="px-8 py-3 bg-yellow-400 text-black rounded-lg font-bold text-lg hover:bg-white transition shadow-lg">
                            Jelajahi Produk
                        </button>
                    </div>
                    <div class="text-8xl">🛍️</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Shortcuts -->
    <section class="bg-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-10">
                <h2 class="text-4xl font-black text-black mb-2">Belanja Berdasarkan Kategori</h2>
                <p class="text-yellow-600 text-lg font-bold">Temukan produk UMKM terbaik sesuai kebutuhan Anda</p>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-6">
                <!-- Tombol Semua -->
                <a href="{{ route('home', ['sort' => request('sort', 'terbaru')]) }}" class="group category-btn" data-category="all">
                    <div class="bg-white rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 {{ !request('kategori') ? 'border-4 border-yellow-400 bg-purple-50 shadow-2xl' : 'border-2 border-gray-300' }}">
                        <div class="w-16 h-16 mx-auto bg-purple-700 rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <span class="text-3xl transform group-hover:scale-110 transition-transform duration-300">🏠</span>
                        </div>
                        <p class="text-xs font-bold text-black text-center leading-tight group-hover:text-yellow-600 transition-colors">Semua</p>
                    </div>
                </a>
                
                @foreach($categories as $index => $category)
                <a href="{{ route('home', ['kategori' => $category->id, 'sort' => request('sort', 'terbaru')]) }}" class="group category-btn" data-category="{{ $category->id }}">
                    <div class="bg-white rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 {{ request('kategori') == $category->id ? 'border-4 border-yellow-400 bg-purple-50 shadow-2xl' : 'border-2 border-gray-300' }}">
                        @php
                            $colors = ['bg-purple-700', 'bg-purple-600', 'bg-purple-800', 'bg-purple-500', 'bg-purple-700', 'bg-purple-600', 'bg-purple-800', 'bg-purple-900', 'bg-purple-700', 'bg-purple-600'];
                            
                            $colorBg = $colors[$index % count($colors)];
                            
                            $icons = ['Fashion' => '👕', 'Makanan' => '🍽️', 'Kerajinan' => '🎨', 'Kecantikan' => '💄', 'Buku' => '📚', 'Elektronik' => '⚡', 'Rumah Tangga' => '🏠', 'Olahraga' => '⚽', 'Hobi' => '🎮', 'Otomotif' => '🚗'];
                            $icon = $icons[$category->nama_kategori] ?? '📦';
                        @endphp
                        <div class="w-16 h-16 mx-auto {{ $colorBg }} rounded-2xl flex items-center justify-center mb-3 shadow-lg group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <span class="text-3xl transform group-hover:scale-110 transition-transform duration-300">{{ $icon }}</span>
                        </div>
                        <p class="text-xs font-bold text-black text-center leading-tight group-hover:text-yellow-600 transition-colors">{{ $category->nama_kategori }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Recommended Products -->
    <section class="bg-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-4xl font-black text-black">Rekomendasi Untuk Anda</h2>
                <div class="flex gap-2">
                    <a href="{{ route('home', ['sort' => 'terbaru']) }}" 
                       class="px-4 py-2 {{ request('sort', 'terbaru') == 'terbaru' ? 'bg-purple-700 text-yellow-400' : 'bg-purple-500 text-white hover:bg-purple-700' }} rounded-lg text-sm font-bold transition shadow-md">
                        Terbaru
                    </a>
                    <a href="{{ route('home', ['sort' => 'terlaris']) }}" 
                       class="px-4 py-2 {{ request('sort') == 'terlaris' ? 'bg-purple-700 text-yellow-400' : 'bg-purple-500 text-white hover:bg-purple-700' }} rounded-lg text-sm font-bold transition shadow-md">
                        Terlaris
                    </a>
                    <a href="{{ route('home', ['sort' => 'termurah']) }}" 
                       class="px-4 py-2 {{ request('sort') == 'termurah' ? 'bg-purple-700 text-yellow-400' : 'bg-purple-500 text-white hover:bg-purple-700' }} rounded-lg text-sm font-bold transition shadow-md">
                        Termurah
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @forelse($recommendedProducts as $product)
                <!-- Product -->
                <div class="product-card bg-white border border-gray-200 rounded-xl hover:shadow-xl transition overflow-hidden group" data-category="{{ $product->kategori_id }}">
                    <a href="{{ route('produk.detail', $product->slug ?? $product->id) }}" class="block">
                        <div class="relative">
                            <div class="aspect-square bg-purple-100 flex items-center justify-center overflow-hidden border-2 border-gray-200">
                                @if($product->gambar)
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->nama_produk }} - {{ $product->kategori->nama_kategori ?? 'Produk' }} | Belanja di CheckoutAja"
                                         title="{{ $product->nama_produk }}"
                                         loading="lazy"
                                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'text-7xl\'>📦</div><small class=\'text-xs text-red-500\'>Gambar error</small>';"
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
                               class="group relative overflow-hidden block w-full px-4 py-3 bg-purple-700 hover:bg-purple-900 text-yellow-400 hover:text-white text-center text-sm font-bold rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95">
                                <!-- Shimmer Effect -->
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                                
                                <span class="relative z-10 inline-flex items-center gap-2 justify-center">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                    </svg>
                                    <span class="tracking-wide">BELI SEKARANG</span>
                                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </span>
                            </a>
                        @else
                            <button disabled class="block w-full px-4 py-3 bg-gray-300 text-gray-600 text-center text-sm font-bold rounded-xl cursor-not-allowed opacity-60">
                                <span class="inline-flex items-center gap-2 justify-center">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Stok Habis
                                </span>
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-5 text-center py-8 text-gray-500">
                    Belum ada produk rekomendasi
                </div>
                @endforelse
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-8">
                <button class="px-8 py-3 border-4 border-yellow-400 bg-purple-700 text-yellow-400 rounded-lg font-black hover:bg-purple-900 hover:text-white transition shadow-lg">
                    Muat Lebih Banyak Produk
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-5 gap-8 mb-8">
                <!-- Brand -->
                <div class="md:col-span-2">
                    <div class="flex items-center gap-1 mb-4">
                        <img src="{{ asset('img/Gemini_Generated_Image_pt2ptxpt2ptxpt2p-removebg-preview.png') }}" alt="CheckoutAja.com Logo" class="h-16 w-auto">
                        <h3 class="text-3xl font-black">
                            <span class="text-purple-400">Checkout</span><span class="text-purple-300">Aja</span><span class="text-purple-200">.com</span>
                        </h3>
                    </div>
                    <p class="text-gray-400 mb-4">
                        Platform marketplace terpercaya untuk UMKM Indonesia. Belanja produk lokal berkualitas dengan harga terbaik.
                    </p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/profile.php?id=100087532447096" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center hover:bg-purple-700 transition hover:scale-110" title="Facebook">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/checkoutaja/" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-purple-600 rounded-full flex items-center justify-center hover:scale-110 hover:bg-yellow-400 transition" title="Instagram">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Tentang -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Tentang Kami</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang CheckoutAja</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                
                <!-- Bantuan -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Cara Berbelanja</a></li>
                        <li><a href="#" class="hover:text-white transition">Cara Berjualan</a></li>
                        <li><a href="#" class="hover:text-white transition">Pembayaran</a></li>
                        <li><a href="#" class="hover:text-white transition">Pengiriman</a></li>
                    </ul>
                </div>
                
                <!-- Lainnya -->
                <div>
                    <h4 class="font-bold text-lg mb-4">Lainnya</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Jual di CheckoutAja</a></li>
                        <li><a href="#" class="hover:text-white transition">Flash Sale</a></li>
                        <li><a href="#" class="hover:text-white transition">Promosi</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 <span class="text-purple-400 font-bold">CheckoutAja</span>. All Rights Reserved. Made with ❤️ for Indonesian UMKM</p>
            </div>
        </div>
    </footer>

    <script>
        // Modal functions for quantity input
        function showQuantityModal(button) {
            const form = button.closest('form');
            const productName = form.dataset.productName;
            const maxStock = parseInt(form.dataset.maxStock);
            
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Masukkan Jumlah Pesanan</h3>
                    <p class="text-gray-600 mb-4">${productName}</p>
                    <p class="text-sm text-gray-500 mb-4">Stok tersedia: ${maxStock} unit</p>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah:</label>
                        <input type="number" 
                               id="quantity-input" 
                               min="1" 
                               max="${maxStock}" 
                               value="1"
                               class="w-full px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600">
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="closeModal()" 
                                class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button onclick="submitCheckout()" 
                                class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            Pesan Sekarang
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            
            // Store form reference
            window.currentForm = form;
            
            // Focus on input
            setTimeout(() => {
                document.getElementById('quantity-input').focus();
                document.getElementById('quantity-input').select();
            }, 100);
        }

        function closeModal() {
            const modal = document.querySelector('.fixed.inset-0');
            if (modal) {
                modal.remove();
            }
        }

        function submitCheckout() {
            const quantity = parseInt(document.getElementById('quantity-input').value);
            const form = window.currentForm;
            const maxStock = parseInt(form.dataset.maxStock);
            
            if (quantity < 1) {
                alert('Jumlah minimal adalah 1');
                return;
            }
            
            if (quantity > maxStock) {
                alert(`Jumlah maksimal adalah ${maxStock} (stok tersedia)`);
                return;
            }
            
            // Update quantity in form
            form.querySelector('input[name="jumlah"]').value = quantity;
            
            // Close modal
            closeModal();
            
            // Submit form
            form.submit();
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('fixed') && e.target.classList.contains('inset-0')) {
                closeModal();
            }
        });

        // Dropdown Toggle
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const userDropdown = document.getElementById('userDropdown');
            const dropdownMenu = document.getElementById('dropdownMenu');
            
            if (userDropdown && !userDropdown.contains(e.target) && dropdownMenu) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>
</body>
</html>