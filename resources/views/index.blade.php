<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- SEO Meta Tags --}}
    <title>MyBelanjaMu - Belanja Online Produk UMKM Lokal Indonesia Terpercaya</title>
    <meta name="description" content="Platform marketplace terpercaya untuk produk UMKM Indonesia. Belanja online produk lokal berkualitas dengan harga terbaik. Dukung pengusaha lokal Indonesia dengan berbelanja di MyBelanjaMu.">
    <meta name="keywords" content="belanja online Indonesia, UMKM, produk lokal, marketplace UMKM, belanja produk Indonesia, MyBelanjaMu, toko online terpercaya">
    <meta name="author" content="MyBelanjaMu">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ route('home') }}">
    
    {{-- Open Graph --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:title" content="MyBelanjaMu - Belanja Produk UMKM Lokal Indonesia">
    <meta property="og:description" content="Platform marketplace terpercaya untuk produk UMKM Indonesia. Belanja online produk lokal berkualitas dengan harga terbaik.">
    <meta property="og:image" content="{{ asset('images/og-home.png') }}">
    <meta property="og:site_name" content="MyBelanjaMu">
    
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
<body class="bg-gray-50 min-h-screen overflow-x-hidden">
    
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white text-xs sm:text-sm py-2">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-2 sm:gap-4">
                <span class="hidden sm:inline">📱 Download Aplikasi</span>
                <span class="hidden sm:inline">|</span>
                <span class="text-xs sm:text-sm">Ikuti Kami: � Instagram | 📷 Facebook</span>
            </div>
            <div class="flex items-center gap-4">
                <span>🔔 Notifikasi</span>
                <span>❓ Bantuan</span>
            </div>
        </div>
    </div>

    <!-- Main Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 sm:py-4">
                <!-- Logo -->
                <div class="flex items-center gap-2">
                    <img src="{{ asset('img/images-removebg-preview (1).png') }}" alt="MyBelanjaMu Logo" class="h-10 sm:h-12 w-auto">
                    <h1 class="text-lg sm:text-2xl font-bold">
                        <span class="bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">My</span><span class="text-purple-700">Belanja</span><span class="bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">Mu</span>
                    </h1>
                </div>
                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Cari produk, toko, atau kategori..." 
                                   value="{{ request('q') }}"
                                   class="w-full px-4 py-2 pr-12 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-700">
                            <button type="submit" class="absolute right-0 top-0 h-full px-6 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 transition">
                                🔍
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Right Menu -->
                <div class="flex items-center gap-4">
                    <button class="relative">
                        <span class="text-2xl">🛒</span>
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">0</span>
                    </button>
                    
                    @guest
                        <a href="{{ url('/login') }}" class="px-4 py-2 text-purple-600 border-2 border-purple-600 rounded-lg hover:bg-purple-50 transition font-medium">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-medium">
                            Daftar
                        </a>
                    @else
                        <div class="relative" id="userDropdown">
                            <button onclick="toggleDropdown()" class="flex items-center gap-2 px-4 py-2 bg-purple-100 rounded-lg hover:bg-purple-200 transition">
                                <span class="text-2xl">👤</span>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <span class="text-gray-500">▼</span>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 z-50">
                                <div class="py-2">
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'penjual')
                                        <a href="{{ url('/dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-purple-50 transition">
                                            <span class="text-xl">📊</span>
                                            <span class="text-gray-700">Dashboard</span>
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-purple-50 transition">
                                        <span class="text-xl">👤</span>
                                        <span class="text-gray-700">Profil Saya</span>
                                    </a>
                                    
                                    @if(Auth::user()->role === 'pembeli' || Auth::user()->role === 'user')
                                        <a href="{{ route('pembeli.pesanan.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-purple-50 transition">
                                            <span class="text-xl">📦</span>
                                            <span class="text-gray-700">Pesanan Saya</span>
                                        </a>
                                    @endif
                                    
                                    @if(Auth::user()->role === 'penjual' && Auth::user()->status_approval === 'approved')
                                        <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-purple-50 transition">
                                            <span class="text-xl">📦</span>
                                            <span class="text-gray-700">Produk Saya</span>
                                        </a>
                                    @endif
                                    <hr class="my-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2 hover:bg-red-50 text-red-600 transition">
                                            <span class="text-xl">🚪</span>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
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
    <section class="bg-gray-100 py-6">
        <div class="max-w-7xl mx-auto px-4">
            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-purple-800 rounded-2xl overflow-hidden shadow-xl">
                <div class="flex items-center justify-between p-12">
                    <div class="text-white max-w-xl">
                        <h2 class="text-4xl font-bold mb-4">Flash Sale Hari Ini!</h2>
                        <p class="text-xl mb-6">Diskon hingga 70% untuk produk pilihan</p>
                        <button class="px-8 py-3 bg-yellow-400 text-purple-900 rounded-lg font-bold text-lg hover:bg-yellow-300 transition">
                            Belanja Sekarang
                        </button>
                    </div>
                    <div class="text-8xl">🎉</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category Shortcuts -->
    <section class="bg-white py-12 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-purple-50 via-white to-blue-50 opacity-60"></div>
        
        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Belanja Berdasarkan Kategori</h2>
                <p class="text-gray-600">Temukan produk UMKM terbaik sesuai kebutuhan Anda</p>
            </div>
            
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-8 xl:grid-cols-10 gap-6">
                <!-- Tombol Semua -->
                <a href="{{ route('home', ['sort' => request('sort', 'terbaru')]) }}" class="group category-btn" data-category="all">
                    <div class="bg-white rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 {{ !request('kategori') ? 'border-2 border-purple-600 shadow-2xl' : 'border border-gray-100' }}">
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br from-gray-500 to-gray-600 rounded-2xl flex items-center justify-center mb-3 shadow-lg shadow-gray-200 group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <span class="text-3xl transform group-hover:scale-110 transition-transform duration-300">🏠</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 text-center leading-tight group-hover:text-purple-600 transition-colors">Semua</p>
                    </div>
                </a>
                
                @foreach($categories as $index => $category)
                <a href="{{ route('home', ['kategori' => $category->id, 'sort' => request('sort', 'terbaru')]) }}" class="group category-btn" data-category="{{ $category->id }}">
                    <div class="bg-white rounded-2xl p-4 transition-all duration-300 hover:shadow-xl hover:-translate-y-2 {{ request('kategori') == $category->id ? 'border-2 border-purple-600 shadow-2xl' : 'border border-gray-100' }}">
                        @php
                            $colors = ['from-purple-500 to-purple-600', 'from-orange-500 to-orange-600', 'from-pink-500 to-pink-600', 'from-blue-500 to-blue-600', 'from-green-500 to-green-600', 'from-yellow-500 to-yellow-600', 'from-indigo-500 to-indigo-600', 'from-red-500 to-red-600', 'from-teal-500 to-teal-600', 'from-cyan-500 to-cyan-600'];
                            $shadows = ['shadow-purple-200', 'shadow-orange-200', 'shadow-pink-200', 'shadow-blue-200', 'shadow-green-200', 'shadow-yellow-200', 'shadow-indigo-200', 'shadow-red-200', 'shadow-teal-200', 'shadow-cyan-200'];
                            $colorBg = $colors[$index % count($colors)];
                            $colorShadow = $shadows[$index % count($shadows)];
                            
                            $icons = ['Fashion' => '👕', 'Makanan' => '🍽️', 'Kerajinan' => '🎨', 'Kecantikan' => '💄', 'Buku' => '📚', 'Elektronik' => '⚡', 'Rumah Tangga' => '🏠', 'Olahraga' => '⚽', 'Hobi' => '🎮', 'Otomotif' => '🚗'];
                            $icon = $icons[$category->nama_kategori] ?? '📦';
                        @endphp
                        <div class="w-16 h-16 mx-auto bg-gradient-to-br {{ $colorBg }} rounded-2xl flex items-center justify-center mb-3 shadow-lg {{ $colorShadow }} group-hover:scale-110 group-hover:rotate-6 transition-all duration-300">
                            <span class="text-3xl transform group-hover:scale-110 transition-transform duration-300">{{ $icon }}</span>
                        </div>
                        <p class="text-xs font-semibold text-gray-700 text-center leading-tight group-hover:text-purple-600 transition-colors">{{ $category->nama_kategori }}</p>
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
                <h2 class="text-3xl font-bold text-gray-800">Rekomendasi Untuk Anda</h2>
                <div class="flex gap-2">
                    <a href="{{ route('home', ['sort' => 'terbaru']) }}" 
                       class="px-4 py-2 {{ request('sort', 'terbaru') == 'terbaru' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium transition">
                        Terbaru
                    </a>
                    <a href="{{ route('home', ['sort' => 'terlaris']) }}" 
                       class="px-4 py-2 {{ request('sort') == 'terlaris' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium transition">
                        Terlaris
                    </a>
                    <a href="{{ route('home', ['sort' => 'termurah']) }}" 
                       class="px-4 py-2 {{ request('sort') == 'termurah' ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-lg text-sm font-medium transition">
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
                <button class="px-8 py-3 border-2 border-purple-600 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition">
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
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('img/images-removebg-preview (1).png') }}" alt="MyBelanjaMu Logo" class="h-12 w-auto">
                        <h3 class="text-2xl font-bold">
                            <span class="bg-gradient-to-r from-purple-400 to-purple-600 bg-clip-text text-transparent">My</span><span class="text-white">Belanja</span><span class="bg-gradient-to-r from-purple-400 to-purple-600 bg-clip-text text-transparent">Mu</span>
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
                        <a href="https://www.instagram.com/mybelanjamu/" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-gradient-to-br from-purple-600 via-pink-600 to-orange-500 rounded-full flex items-center justify-center hover:scale-110 transition" title="Instagram">
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
                        <li><a href="#" class="hover:text-white transition">Tentang MyBelanjaMu</a></li>
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
                        <li><a href="#" class="hover:text-white transition">Jual di MyBelanjaMu</a></li>
                        <li><a href="#" class="hover:text-white transition">Flash Sale</a></li>
                        <li><a href="#" class="hover:text-white transition">Promosi</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-8 text-center text-gray-400">
                <p>&copy; 2024 MyBelanjaMu. All Rights Reserved. Made with ❤️ for Indonesian UMKM</p>
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