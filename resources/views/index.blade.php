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
        
        /* Custom Colors */
        :root {
            --primary: #6D28D9;
            --secondary: #FACC15;
        }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        /* Smooth animations */
        * {
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Hero gradient animation */
        @keyframes gradient {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .animate-gradient {
            background-size: 200% 200%;
            animation: gradient 15s ease infinite;
        }
        
        /* Product Card Slider Animation */
        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateX(20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }
        
        .product-card-animate {
            animation: fadeInSlide 0.6s ease-out forwards;
        }
        
        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6D28D9',
                        secondary: '#FACC15',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen overflow-x-hidden antialiased">
    
    <!-- Main Navbar -->
    <nav class="bg-white/80 backdrop-blur-md shadow-sm sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center group">
                    <img
                        src="{{ asset('img/logo.png') }}"
                        alt="CheckoutAja.com"
                        class="h-32 sm:h-36 w-auto object-contain transition-transform group-hover:scale-105"

                        />

                </a>

                
                <!-- Search Bar -->
                <div class="hidden md:flex flex-1 max-w-2xl mx-12">
                    <form action="{{ route('search') }}" method="GET" class="w-full">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 group-focus-within:text-primary transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" 
                                   name="q" 
                                   placeholder="Cari produk, toko, atau kategori..." 
                                   value="{{ request('q') }}"
                                   class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition bg-gray-50/50 hover:bg-white">
                        </div>
                    </form>
                </div>
                
                <!-- Right Menu -->
                <div class="flex items-center gap-2 sm:gap-4">
                    @guest
                        <a href="{{ url('/login') }}" 
                           class="px-3 sm:px-5 py-2 sm:py-2.5 text-gray-700 hover:text-primary font-medium transition text-sm sm:text-base">
                            Masuk
                        </a>
                        <a href="{{ url('/register') }}" 
                           class="px-3 sm:px-6 py-2 sm:py-2.5 bg-primary text-white rounded-lg sm:rounded-xl hover:bg-primary/90 font-medium transition shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 hover:-translate-y-0.5 text-sm sm:text-base">
                            <span class="hidden sm:inline">Daftar Sekarang</span>
                            <span class="sm:hidden">Daftar</span>
                        </a>
                    @else
                        @if(Auth::user()->role === 'pembeli' || Auth::user()->role === 'user')
                            <a href="{{ route('pembeli.dashboard') }}" 
                               class="flex items-center gap-2 sm:gap-3 px-2 sm:px-4 py-2 sm:py-2.5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg sm:rounded-xl hover:from-gray-100 hover:to-gray-200 transition group">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary to-purple-600 text-white rounded-lg sm:rounded-xl flex items-center justify-center font-semibold shadow-lg group-hover:scale-105 transition text-sm sm:text-base">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Lihat Dashboard</p>
                                </div>
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" 
                               class="flex items-center gap-2 sm:gap-3 px-2 sm:px-4 py-2 sm:py-2.5 bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg sm:rounded-xl hover:from-gray-100 hover:to-gray-200 transition group">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gradient-to-br from-primary to-purple-600 text-white rounded-lg sm:rounded-xl flex items-center justify-center font-semibold shadow-lg group-hover:scale-105 transition text-sm sm:text-base">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                                <div class="text-left hidden sm:block">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500">Lihat Dashboard</p>
                                </div>
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
            
            <!-- Category Menu -->
            <div class="border-t border-gray-100 py-3 sm:py-4 -mx-3 sm:mx-0 px-3 sm:px-0">
                <div class="flex items-center gap-4 sm:gap-8 text-xs sm:text-sm overflow-x-auto scrollbar-hide">
                    <a href="{{ route('home', ['sort' => request('sort', 'terbaru')]) }}" 
                       class="text-gray-600 hover:text-primary transition font-medium whitespace-nowrap flex items-center gap-2 {{ !request('kategori') ? 'text-primary' : '' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Semua Kategori
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('home', ['kategori' => $category->id, 'sort' => request('sort', 'terbaru')]) }}" 
                           class="text-gray-600 hover:text-primary transition whitespace-nowrap {{ request('kategori') == $category->id ? 'text-primary font-semibold' : '' }}">
                            {{ $category->nama_kategori }}
                        </a>
                    @endforeach
                    <a href="#" 
                       class="text-secondary font-semibold whitespace-nowrap flex items-center gap-2 bg-secondary/10 px-3 py-1 rounded-full">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"></path>
                        </svg>
                        Promo Hari Ini
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section - Apa itu CheckoutAja -->
    <section class="relative bg-gradient-to-br from-primary via-purple-700 to-purple-900 overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 lg:py-32 relative text-center">
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-full border border-white/20 mb-6">
                <svg class="w-4 h-4 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-white text-sm font-medium">Platform UMKM Terverifikasi</span>
            </div>
            
            <h1 class="text-3xl sm:text-4xl lg:text-5xl xl:text-6xl font-bold text-white leading-tight mb-6">
                CheckoutAja Bukan<br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-secondary to-yellow-200">
                    Marketplace Biasa
                </span>
            </h1>
            
            <p class="text-lg sm:text-xl text-purple-100 leading-relaxed mb-8 max-w-3xl mx-auto">
                Kami adalah platform perdagangan UMKM yang <span class="font-semibold text-white">dikurasi dan diverifikasi</span>. 
                Setiap penjual melalui proses seleksi ketat untuk memastikan Anda berbelanja dengan aman dan nyaman.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="#why-umkm" class="inline-flex items-center gap-2 px-8 py-4 bg-secondary text-gray-900 rounded-xl font-semibold hover:bg-secondary/90 transition shadow-2xl shadow-secondary/50 hover:shadow-secondary/70 hover:scale-105 group">
                    <span>Pelajari Lebih Lanjut</span>
                    <svg class="w-5 h-5 group-hover:translate-y-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
                <a href="#products" class="inline-flex items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-sm text-white rounded-xl font-semibold hover:bg-white/20 transition border border-white/20">
                    <span>Lihat Produk Terkurasi</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Section 2: Kenapa UMKM Harus Daftar -->
    <section id="why-umkm" class="bg-white py-16 sm:py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-4">
                    Untuk UMKM
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Mengapa UMKM Perlu Bergabung?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Bukan sekadar tempat jualan. Kami membantu UMKM membangun kepercayaan dan mendapatkan pembeli yang lebih baik.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border-2 border-gray-100 hover:border-primary/30 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Verifikasi Resmi</h3>
                    <p class="text-gray-600 leading-relaxed">
                        UMKM Anda akan melalui proses kurasi oleh admin. Pembeli tahu bahwa toko Anda telah terverifikasi dan terpercaya.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border-2 border-gray-100 hover:border-primary/30 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Meningkatkan Kepercayaan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Status terverifikasi membuat pembeli lebih percaya diri berbelanja, meningkatkan konversi penjualan Anda.
                    </p>
                </div>

                <div class="bg-gradient-to-br from-gray-50 to-white p-8 rounded-2xl border-2 border-gray-100 hover:border-primary/30 hover:shadow-xl transition">
                    <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Pasar Lebih Berkualitas</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Dapatkan akses ke pembeli yang mencari produk berkualitas, bukan hanya mencari harga murah.
                    </p>
                </div>
            </div>

            <div class="mt-12 text-center">
                @auth
                    @if(Auth::user()->role === 'user')
                        {{-- Tombol untuk user yang sudah login tapi belum jadi penjual --}}
                        <form method="POST" action="{{ route('profile.apply-seller') }}" onsubmit="return confirm('Yakin ingin mendaftar sebagai penjual UMKM? Akun Anda akan diverifikasi oleh admin.')" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition shadow-lg shadow-primary/30 hover:shadow-xl hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span>Daftar Sebagai UMKM</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </form>
                    @else
                        {{-- Untuk penjual/admin yang sudah terdaftar --}}
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gray-600 text-white rounded-xl font-semibold hover:bg-gray-700 transition shadow-lg hover:shadow-xl hover:scale-105">
                            <span>Lihat Dashboard</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @endif
                @else
                    {{-- Untuk pengunjung yang belum login --}}
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-primary text-white rounded-xl font-semibold hover:bg-primary/90 transition shadow-lg shadow-primary/30 hover:shadow-xl hover:scale-105">
                        <span>Daftar Sebagai UMKM</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Section 3: Kenapa Pembeli Bisa Percaya -->
    <section class="bg-gradient-to-b from-gray-50 to-white py-16 sm:py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-2 bg-secondary/20 text-gray-900 rounded-full text-sm font-semibold mb-4">
                    Untuk Pembeli
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    Kenapa Anda Bisa Belanja dengan Tenang?
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Kami memastikan setiap transaksi Anda aman dan setiap penjual telah melewati verifikasi ketat.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">UMKM Terkurasi</h3>
                    <p class="text-gray-600 text-sm">Setiap penjual telah diverifikasi oleh admin kami</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Produk Terpantau</h3>
                    <p class="text-gray-600 text-sm">Kualitas produk dijaga dan dipantau secara berkala</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Transaksi Aman</h3>
                    <p class="text-gray-600 text-sm">Sistem pembayaran yang transparan dan terpercaya</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-primary text-white rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Pesanan Terlacak</h3>
                    <p class="text-gray-600 text-sm">Monitoring pesanan dari awal sampai produk diterima</p>
                </div>
            </div>

            <div class="mt-12 bg-white rounded-2xl p-8 shadow-lg border border-gray-100">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="flex-shrink-0">
                        <div class="w-20 h-20 bg-secondary/20 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Bukan Marketplace Bebas</h3>
                        <p class="text-gray-600">
                            Kami <span class="font-semibold text-gray-900">bukan marketplace yang membiarkan siapa saja jualan</span>. 
                            Setiap UMKM dikurasi untuk memastikan Anda mendapatkan pengalaman belanja yang berkualitas.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Products Section - UMKM Terkurasi -->
    <section id="products" class="bg-white py-16 sm:py-20 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-semibold mb-4">
                    Produk Terkurasi
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">
                    UMKM Terverifikasi Pilihan Kami
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                    Setiap produk di bawah ini berasal dari UMKM yang telah melewati proses kurasi dan verifikasi ketat oleh tim kami.
                </p>
                
                <!-- Filter -->
                <div class="flex gap-2 sm:gap-3 justify-center overflow-x-auto scrollbar-hide pb-4">
                    <a href="{{ route('home', ['sort' => 'terbaru']) }}" 
                       class="px-4 sm:px-6 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('sort', 'terbaru') == 'terbaru' ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Terbaru
                    </a>
                    <a href="{{ route('home', ['sort' => 'terlaris']) }}" 
                       class="px-4 sm:px-6 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('sort') == 'terlaris' ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Terlaris
                    </a>
                    <a href="{{ route('home', ['sort' => 'termurah']) }}" 
                       class="px-4 sm:px-6 py-2.5 rounded-xl text-sm font-semibold transition whitespace-nowrap {{ request('sort') == 'termurah' ? 'bg-primary text-white shadow-lg shadow-primary/30' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Harga Terbaik
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                @forelse($recommendedProducts as $product)
                <!-- Product Card -->
                <div class="product-card bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group border-2 border-gray-100 hover:border-primary/30 hover:-translate-y-1" 
                     data-category="{{ $product->kategori_id }}">
                    <a href="{{ route('produk.detail', $product->slug ?? $product->id) }}" class="block">
                        <!-- Verified Badge -->
                        <div class="absolute top-3 left-3 z-10 px-2.5 py-1 bg-primary text-white text-xs font-bold rounded-lg shadow-lg flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Terverifikasi</span>
                        </div>
                        
                        <div class="relative aspect-square bg-gradient-to-br from-gray-50 to-gray-100 overflow-hidden">
                            @if($product->gambar)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->nama_produk }} - {{ $product->kategori->nama_kategori ?? 'Produk' }} | CheckoutAja"
                                     title="{{ $product->nama_produk }}"
                                     loading="lazy"
                                     onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'flex items-center justify-center h-full text-6xl\'>📦</div>';"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="flex items-center justify-center h-full text-6xl">📦</div>
                            @endif
                            
                            @if($product->stok <= 5 && $product->stok > 0)
                                <div class="absolute bottom-3 left-3 right-3 px-3 py-2 bg-red-500 text-white text-xs font-semibold rounded-lg shadow-lg text-center">
                                    ⚠️ Stok Terbatas
                                </div>
                            @endif
                        </div>
                        
                        <div class="p-4 space-y-3">
                            <div class="text-xs text-primary font-semibold">
                                {{ $product->kategori->nama_kategori ?? 'Produk UMKM' }}
                            </div>
                            <h3 class="text-sm font-bold text-gray-800 line-clamp-2 h-10 group-hover:text-primary transition">
                                {{ $product->nama_produk }}
                            </h3>
                            <div class="flex items-center gap-1.5">
                                <div class="flex items-center gap-0.5">
                                    <svg class="w-4 h-4 text-secondary" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                </div>
                                <span class="text-sm font-semibold text-gray-700">{{ $product->averageRating() }}</span>
                                <span class="text-xs text-gray-400">({{ $product->totalRatings() }})</span>
                            </div>
                            <div class="flex items-baseline justify-between">
                                <span class="text-xl font-bold text-gray-900">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                <span class="text-xs px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg">{{ $product->kategori->nama_kategori ?? 'Umum' }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs text-gray-500 pt-1">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                    Stok: {{ $product->stok }}
                                </span>
                            </div>
                        </div>
                    </a>
                    
                    <div class="px-4 pb-4">
                        @if($product->stok > 0 && $product->status)
                            <a href="{{ route('whatsapp.checkout.show', $product->id) }}" 
                               class="block w-full px-4 py-3 bg-gradient-to-r from-secondary to-yellow-400 hover:from-secondary/90 hover:to-yellow-300 text-gray-900 text-center text-sm font-bold rounded-xl transition-all shadow-lg shadow-secondary/30 hover:shadow-xl hover:shadow-secondary/40 hover:scale-105">
                                Beli Sekarang
                            </a>
                        @else
                            <button disabled 
                                    class="block w-full px-4 py-3 bg-gray-100 text-gray-400 text-center text-sm font-semibold rounded-xl cursor-not-allowed">
                                Stok Habis
                            </button>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-5 text-center py-16">
                    <div class="text-gray-300 mb-4">
                        <svg class="w-24 h-24 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum ada produk</h3>
                    <p class="text-gray-500">Produk akan segera tersedia</p>
                </div>
                @endforelse
            </div>

            <!-- Load More -->
            <div class="text-center mt-12">
                <button class="px-8 py-4 border-2 border-primary text-primary rounded-xl font-semibold hover:bg-primary hover:text-white transition shadow-lg hover:shadow-xl hover:scale-105">
                    Muat Lebih Banyak Produk
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Brand -->
                <div class="md:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <img src="{{ asset('/img/sidelogo.png') }}" 
                             alt="CheckoutAja.com Logo" 
                             class="h-10 w-auto">
                        <h3 class="text-xl font-bold text-white">CheckoutAja</h3>
                    </div>
                    <p class="text-gray-400 text-sm mb-4 leading-relaxed">
                        Platform marketplace terpercaya untuk UMKM Indonesia. Belanja produk lokal berkualitas dengan harga terbaik.
                    </p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/profile.php?id=100087532447096" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary transition" 
                           title="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/checkoutaja/" 
                           target="_blank" 
                           rel="noopener noreferrer" 
                           class="w-9 h-9 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-primary transition" 
                           title="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Tentang -->
                <div>
                    <h4 class="font-semibold text-base mb-4">Tentang Kami</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang CheckoutAja</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                
                <!-- Bantuan -->
                <div>
                    <h4 class="font-semibold text-base mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Cara Berbelanja</a></li>
                        <li><a href="#" class="hover:text-white transition">Cara Berjualan</a></li>
                        <li><a href="#" class="hover:text-white transition">Pembayaran</a></li>
                        <li><a href="#" class="hover:text-white transition">Pengiriman</a></li>
                    </ul>
                </div>
                
                <!-- Lainnya -->
                <div>
                    <h4 class="font-semibold text-base mb-4">Lainnya</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Jual di CheckoutAja</a></li>
                        <li><a href="#" class="hover:text-white transition">Flash Sale</a></li>
                        <li><a href="#" class="hover:text-white transition">Promosi</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    &copy; 2024 <span class="text-primary font-semibold">CheckoutAja</span>. All Rights Reserved. 
                    <span class="text-gray-500">Made with ❤️ for Indonesian UMKM</span>
                </p>
            </div>
        </div>
    </footer>

    <script>
        // Hero Product Slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.product-slide');
        const dots = document.querySelectorAll('.slider-dot');
        const totalSlides = slides.length;
        
        function changeSlide(index) {
            // Hide current slide
            slides[currentSlide].classList.add('hidden');
            slides[currentSlide].classList.remove('product-card-animate');
            dots[currentSlide].classList.remove('bg-white', 'w-8');
            dots[currentSlide].classList.add('bg-white/40');
            
            // Show new slide
            currentSlide = index;
            slides[currentSlide].classList.remove('hidden');
            slides[currentSlide].classList.add('product-card-animate');
            dots[currentSlide].classList.remove('bg-white/40');
            dots[currentSlide].classList.add('bg-white', 'w-8');
        }
        
        // Auto slide every 5 seconds
        if (totalSlides > 1) {
            setInterval(() => {
                const nextSlide = (currentSlide + 1) % totalSlides;
                changeSlide(nextSlide);
            }, 5000);
        }

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