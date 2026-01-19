<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'CheckoutAja - Belanja Produk UMKM Lokal Indonesia Terpercaya')</title>
    <meta name="description" content="@yield('meta_description', 'Platform marketplace terpercaya untuk produk UMKM Indonesia. Belanja online produk lokal berkualitas dengan harga terbaik.')">
    <meta name="keywords" content="@yield('meta_keywords', 'belanja online, UMKM, produk lokal, marketplace Indonesia, belanja UMKM, CheckoutAja')">
    <meta name="author" content="CheckoutAja">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'CheckoutAja.com - Belanja Produk UMKM Lokal')">
    <meta property="og:description" content="@yield('meta_description', 'Platform marketplace terpercaya untuk produk UMKM Indonesia')">
    <meta property="og:image" content="@yield('og_image', asset('images/og-default.png'))">
    <meta property="og:site_name" content="CheckoutAja.com">
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('title', 'CheckoutAja.com')">
    <meta name="twitter:description" content="@yield('meta_description', 'Belanja produk UMKM lokal Indonesia')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/og-default.png'))">
    
    <script src="https://cdn.tailwindcss.com"></script>

    @stack('styles')
</head>
<body class="bg-gray-50">
    
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white text-sm py-2">
        <div class="max-w-7xl mx-auto px-4 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <span>📱 Download Aplikasi</span>
                <span>|</span>
                <span>Ikuti Kami: 📘 📷 🐦</span>
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
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <span class="text-3xl">🛒</span>
                    <h1 class="text-2xl font-bold text-purple-700">CheckoutAja</h1>
                </a>
                
                <!-- Search Bar -->
                <div class="flex-1 max-w-2xl mx-8">
                    <div class="relative">
                        <input type="text" placeholder="Cari produk, toko, atau kategori..." 
                               class="w-full px-4 py-2 pr-12 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-700">
                        <button class="absolute right-0 top-0 h-full px-6 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 transition">
                            🔍
                        </button>
                    </div>
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
        </div>
    </nav>

    <!-- Main Content -->
    @yield('content')

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2025 <span class="text-purple-400 font-bold">CheckoutAja</span>. All rights reserved.</p>
        </div>
    </footer>

    @stack('scripts')
    <script>
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
