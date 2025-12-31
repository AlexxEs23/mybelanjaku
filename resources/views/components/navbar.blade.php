{{-- Main Navigation --}}
<nav class="bg-white shadow-md sticky top-0 z-40" role="navigation" aria-label="Main navigation">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            {{-- Logo --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2 hover:opacity-80 transition" aria-label="UMKM Marketplace Home">
                    <span class="text-3xl" aria-hidden="true">🛒</span>
                    <span class="text-xl sm:text-2xl font-bold text-purple-700">UMKM Market</span>
                </a>
            </div>
            
            {{-- Search Bar - Desktop --}}
            <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                <form action="{{ route('home') }}" method="GET" class="w-full">
                    <div class="relative">
                        <label for="search" class="sr-only">Cari produk</label>
                        <input 
                            type="search" 
                            id="search"
                            name="search"
                            placeholder="Cari produk, toko, atau kategori..." 
                            class="w-full px-4 py-2 pr-12 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-700 focus:ring-2 focus:ring-purple-200"
                            aria-label="Search products"
                        >
                        <button 
                            type="submit"
                            class="absolute right-0 top-0 h-full px-6 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700 transition focus:outline-none focus:ring-2 focus:ring-purple-500"
                            aria-label="Search"
                        >
                            <span aria-hidden="true">🔍</span>
                        </button>
                    </div>
                </form>
            </div>
            
            {{-- Desktop Navigation Links --}}
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-purple-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                    
                    <a href="{{ route('profile.show') }}" class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:text-purple-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span>Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2 text-purple-600 font-semibold hover:text-purple-700 transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-800 text-white font-semibold rounded-lg hover:from-purple-700 hover:to-purple-900 transition shadow-md">
                        Daftar
                    </a>
                @endauth
            </div>
            
            {{-- Mobile Menu Button --}}
            <button 
                id="mobile-menu-button" 
                class="md:hidden p-2 text-gray-700 hover:text-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 rounded"
                aria-label="Toggle mobile menu"
                aria-expanded="false"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
        
        {{-- Mobile Search Bar --}}
        <div class="md:hidden pb-4">
            <form action="{{ route('home') }}" method="GET">
                <div class="relative">
                    <label for="mobile-search" class="sr-only">Cari produk</label>
                    <input 
                        type="search" 
                        id="mobile-search"
                        name="search"
                        placeholder="Cari produk..." 
                        class="w-full px-4 py-2 pr-12 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-700"
                    >
                    <button 
                        type="submit"
                        class="absolute right-0 top-0 h-full px-4 bg-purple-600 text-white rounded-r-lg hover:bg-purple-700"
                        aria-label="Search"
                    >
                        🔍
                    </button>
                </div>
            </form>
        </div>
        
        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden md:hidden pb-4 border-t border-gray-200 mt-2">
            <div class="flex flex-col gap-2 pt-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-gray-700 hover:bg-purple-50 rounded transition">
                        Dashboard
                    </a>
                    <a href="{{ route('profile.show') }}" class="px-4 py-2 text-gray-700 hover:bg-purple-50 rounded transition">
                        Profil Saya
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="px-4">
                        @csrf
                        <button type="submit" class="w-full text-left py-2 text-red-600 hover:bg-red-50 rounded transition">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-purple-600 font-semibold hover:bg-purple-50 rounded transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="mx-4 py-2 bg-purple-600 text-white text-center font-semibold rounded-lg hover:bg-purple-700 transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
