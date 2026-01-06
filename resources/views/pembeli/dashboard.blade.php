@extends('layouts.dashboard')

@section('title', 'Dashboard Pembeli - MyBelanjaMu')

@section('content')
<div class="max-w-7xl mx-auto">
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
                                                <img src="{{ $order->produk->image_url }}" 
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
</div>
@endsection
