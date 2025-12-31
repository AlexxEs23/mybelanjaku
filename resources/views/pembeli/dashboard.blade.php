<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pembeli - UMKM Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    
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
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="text-3xl">🛒</span>
                    <h1 class="text-2xl font-bold text-purple-700">UMKM Market</h1>
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
                        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                    
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-4 py-2 bg-purple-100 rounded-lg hover:bg-purple-200 transition">
                                <span class="text-2xl">👤</span>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">Pembeli</p>
                                </div>
                                <span class="text-gray-500">▼</span>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <div class="py-2">
                                    <a href="{{ route('pembeli.dashboard') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-purple-50 transition">
                                        <span class="text-xl">📊</span>
                                        <span class="text-gray-700">Dashboard</span>
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2 hover:bg-purple-50 transition">
                                        <span class="text-xl">👤</span>
                                        <span class="text-gray-700">Profil Saya</span>
                                    </a>
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
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Container -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">👋 Halo, {{ Auth::user()->name ?? 'Pembeli' }}!</h1>
                    <p class="text-gray-600">Selamat datang di dashboard Anda. Kelola pesanan dan keranjang belanja Anda dengan mudah.</p>
                </div>
                <a href="{{ route('home') }}" class="px-6 py-3 bg-white border-2 border-purple-600 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition flex items-center gap-2">
                    <span>🏠</span>
                    <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Pesanan -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-4xl">📦</div>
                    <div class="bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs font-semibold">Total</div>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $totalOrders }}</h3>
                <p class="text-blue-100 text-sm">Total Pesanan</p>
            </div>

            <!-- Pesanan Aktif -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-4xl">🚚</div>
                    <div class="bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs font-semibold">Proses</div>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $activeOrders }}</h3>
                <p class="text-yellow-100 text-sm">Sedang Diproses</p>
            </div>

            <!-- Pesanan Selesai -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-4xl">✅</div>
                    <div class="bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs font-semibold">Selesai</div>
                </div>
                <h3 class="text-3xl font-bold mb-1">{{ $completedOrders }}</h3>
                <p class="text-purple-100 text-sm">Pesanan Selesai</p>
            </div>

            <!-- Total Belanja -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-4xl">💰</div>
                    <div class="bg-white bg-opacity-20 rounded-full px-3 py-1 text-xs font-semibold">Total</div>
                </div>
                <h3 class="text-2xl font-bold mb-1">Rp {{ number_format($totalSpent / 1000, 1) }}jt</h3>
                <p class="text-green-100 text-sm">Total Belanja</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>⚡</span> Menu Cepat
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Pesanan Saya -->
                        <a href="{{ route('pembeli.pesanan.index') }}" class="group bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 hover:shadow-lg transition border-2 border-transparent hover:border-blue-500">
                            <div class="text-center">
                                <div class="text-5xl mb-3 group-hover:scale-110 transition">📦</div>
                                <h3 class="font-bold text-gray-800 mb-1">Pesanan Saya</h3>
                                <p class="text-xs text-gray-600">Lihat status pesanan</p>
                            </div>
                        </a>

                        <!-- Keranjang -->
                        <a href="#keranjang" class="group bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 hover:shadow-lg transition border-2 border-transparent hover:border-purple-500">
                            <div class="text-center">
                                <div class="text-5xl mb-3 group-hover:scale-110 transition relative inline-block">
                                    🛒
                                </div>
                                <h3 class="font-bold text-gray-800 mb-1">Keranjang Saya</h3>
                                <p class="text-xs text-gray-600">Belanja lebih banyak</p>
                            </div>
                        </a>

                        <!-- Profil -->
                        <a href="{{ route('profile.show') }}" class="group bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 hover:shadow-lg transition border-2 border-transparent hover:border-green-500">
                            <div class="text-center">
                                <div class="text-5xl mb-3 group-hover:scale-110 transition">👤</div>
                                <h3 class="font-bold text-gray-800 mb-1">Profil Saya</h3>
                                <p class="text-xs text-gray-600">Kelola akun</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                            <span>📦</span> Pesanan Terbaru
                        </h2>
                        <a href="{{ route('pembeli.pesanan.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentOrders->count() > 0)
                        <div class="space-y-4">
                            @foreach($recentOrders as $order)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex gap-4">
                                        <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-purple-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            @if($order->produk->gambar)
                                                <img src="{{ asset('storage/' . $order->produk->gambar) }}" 
                                                     alt="{{ $order->produk->nama_produk }}"
                                                     class="w-full h-full object-cover rounded-lg">
                                            @else
                                                <span class="text-3xl">📦</span>
                                            @endif
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-start justify-between mb-2">
                                                <div>
                                                    <h3 class="font-bold text-gray-800">{{ $order->produk->nama_produk }}</h3>
                                                    <p class="text-sm text-gray-600">{{ $order->jumlah }} unit</p>
                                                </div>
                                                @if($order->status == 'menunggu')
                                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">Menunggu</span>
                                                @elseif($order->status == 'diproses')
                                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Diproses</span>
                                                @elseif($order->status == 'dikirim')
                                                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">Dikirim</span>
                                                @elseif($order->status == 'selesai')
                                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Selesai</span>
                                                @else
                                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">Dibatalkan</span>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <span class="font-bold text-purple-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                                <a href="{{ route('pembeli.pesanan.index') }}" class="px-4 py-1 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm">
                                                    Lihat Detail
                                                </a>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-2">📅 {{ $order->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="text-5xl mb-3">📦</div>
                            <p class="text-gray-600">Belum ada pesanan</p>
                            <a href="{{ route('home') }}" class="inline-block mt-4 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold">
                                Mulai Belanja
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Keranjang Preview -->
                

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Profile Card -->
                

                <!-- Promo Banner -->
               
                <!-- Activity -->
                
                    
                    
                </div>

                <!-- Help Center -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>❓</span> Bantuan
                    </h2>
                    
                    <div class="space-y-3">
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 transition">
                            <span>📞</span> Hubungi Customer Service
                        </a>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 transition">
                            <span>💬</span> Chat dengan Penjual
                        </a>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 transition">
                            <span>📄</span> Panduan Berbelanja
                        </a>
                        <a href="#" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 transition">
                            <span>❔</span> FAQ
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <span>🛒</span> UMKM Market
                    </h3>
                    <p class="text-gray-400 text-sm">Platform e-commerce terpercaya untuk produk UMKM Indonesia</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Tentang Kami</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Tentang UMKM Market</a></li>
                        <li><a href="#" class="hover:text-white transition">Karir</a></li>
                        <li><a href="#" class="hover:text-white transition">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="hover:text-white transition">Pusat Bantuan</a></li>
                        <li><a href="#" class="hover:text-white transition">Cara Berbelanja</a></li>
                        <li><a href="#" class="hover:text-white transition">Hubungi Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Ikuti Kami</h4>
                    <div class="flex gap-3">
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-purple-600 transition">📘</a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-purple-600 transition">📷</a>
                        <a href="#" class="w-10 h-10 bg-gray-700 rounded-full flex items-center justify-center hover:bg-purple-600 transition">🐦</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 pt-8 text-center text-gray-400 text-sm">
                <p>© 2025 UMKM Market. Platform e-commerce untuk UMKM Indonesia.</p>
            </div>
        </div>
    </footer>

</body>
</html>
