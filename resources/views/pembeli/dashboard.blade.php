@extends('layouts.dashboard')

@section('title', 'Dashboard - CheckoutAja')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Banner Ajakan Daftar Penjual -->
    @php
        try {
            $existingProfile = \App\Models\ProfileUmkm::where('user_id', Auth::id())->first();
        } catch (\Exception $e) {
            $existingProfile = null;
        }
    @endphp
    
    @if(!$existingProfile)
        <div class="mb-6 bg-green-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">Punya UMKM? Jualan di CheckoutAja Yuk! 🚀</h3>
                        <p class="text-white/90 text-sm">Raih lebih banyak pelanggan dengan mendaftar sebagai penjual. Gratis & mudah!</p>
                    </div>
                </div>
                <a href="{{ route('profile-umkm.index') }}" class="bg-white text-green-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg whitespace-nowrap">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    @elseif($existingProfile && $existingProfile->status_verifikasi === 'pending')
        <div class="mb-6 bg-yellow-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 p-4 rounded-full">
                    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-1">Pendaftaran UMKM Anda Sedang Diproses ⏳</h3>
                    <p class="text-white/90 text-sm">Tim kami sedang memverifikasi <strong>{{ $existingProfile->nama_umkm }}</strong>. Mohon tunggu 1-3 hari kerja.</p>
                </div>
            </div>
        </div>
    @elseif($existingProfile && $existingProfile->status_verifikasi === 'verified')
        <div class="mb-6 bg-blue-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="bg-white/20 p-4 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold mb-1">{{ $existingProfile->nama_umkm }} Sudah Terverifikasi! ✅</h3>
                        <p class="text-white/90 text-sm">Selamat! Anda sudah bisa mulai upload produk dan berjualan.</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-bold hover:bg-gray-100 transition shadow-lg whitespace-nowrap">
                    Kelola Toko
                </a>
            </div>
        </div>
    @endif

    <!-- Header Sederhana -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Halo, {{ Auth::user()->name }}!</h1>
                <p class="text-gray-600 text-sm mt-1">Belanja dengan tenang di CheckoutAja</p>
            </div>
            <a href="{{ route('home') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition">
                🛍️ Belanja Lagi
            </a>
        </div>
    </div>

    <!-- Total Pengeluaran Real-time - Highlight Utama -->
    <div class="bg-purple-600 rounded-2xl shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-black font-bold text-sm mb-2">Total Pengeluaran Anda</p>
                <h2 class="text-black text-4xl font-bold mb-1" id="total-spending">
                    Rp {{ number_format($totalSpent, 0, ',', '.') }}
                </h2>
                <p class="text-black font-bold text-xs">Update otomatis setiap pembelian baru</p>
            </div>
            <div class="text-6xl opacity-20">💳</div>
        </div>
    </div>

    <!-- Ringkasan Pesanan Sederhana -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Pesanan -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-blue-500">
            <div class="text-2xl mb-2">📦</div>
            <p class="text-gray-600 text-xs mb-1">Total Pesanan</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $totalOrders }}</h3>
        </div>

        <!-- Diproses -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-yellow-500">
            <div class="text-2xl mb-2">⏳</div>
            <p class="text-gray-600 text-xs mb-1">Diproses</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $diprosesOrders }}</h3>
        </div>

        <!-- Dikirim -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-purple-500">
            <div class="text-2xl mb-2">🚚</div>
            <p class="text-gray-600 text-xs mb-1">Dikirim</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $dikirimOrders }}</h3>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-xl shadow-md p-5 border-l-4 border-green-500">
            <div class="text-2xl mb-2">✅</div>
            <p class="text-gray-600 text-xs mb-1">Selesai</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ $completedOrders }}</h3>
        </div>
    </div>


    <!-- Daftar Pesanan Terbaru -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-gray-800">📋 Pesanan Terbaru</h2>
            <a href="{{ route('pembeli.pesanan.index') }}" class="text-purple-600 text-sm font-medium hover:text-purple-700">
                Lihat Semua →
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="space-y-3">
                @foreach($recentOrders as $order)
                <div class="border border-gray-200 rounded-lg p-4 hover:border-purple-300 transition">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-800">{{ $order->produk->nama }}</h3>
                            <p class="text-sm text-gray-600">{{ $order->jumlah }} item × Rp {{ number_format($order->harga_satuan, 0, ',', '.') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-purple-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                            @if($order->status == 'pending')
                                <span class="inline-block px-3 py-1 bg-yellow-100 text-yellow-700 text-xs rounded-full mt-1">Menunggu</span>
                            @elseif($order->status == 'diproses')
                                <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full mt-1">Diproses</span>
                            @elseif($order->status == 'dikirim')
                                <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-xs rounded-full mt-1">Dikirim</span>
                            @elseif($order->status == 'selesai')
                                <span class="inline-block px-3 py-1 bg-green-100 text-green-700 text-xs rounded-full mt-1">Selesai</span>
                            @else
                                <span class="inline-block px-3 py-1 bg-gray-100 text-gray-700 text-xs rounded-full mt-1">{{ ucfirst($order->status) }}</span>
                            @endif
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <div class="text-5xl mb-3">🛒</div>
                <p class="text-gray-600 mb-4">Belum ada pesanan</p>
                <a href="{{ route('home') }}" class="inline-block px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition">
                    Mulai Belanja
                </a>
            </div>
        @endif
    </div>

    <!-- Aksi Cepat & Informasi Akun -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Aksi Cepat -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">⚡ Aksi Cepat</h2>
            <div class="space-y-3">
                <a href="{{ route('home') }}" class="flex items-center gap-3 p-3 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                    <span class="text-2xl">🛍️</span>
                    <div>
                        <p class="font-semibold text-gray-800">Belanja Lagi</p>
                        <p class="text-xs text-gray-600">Temukan produk UMKM terbaik</p>
                    </div>
                </a>
                <a href="{{ route('pembeli.pesanan.index') }}" class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                    <span class="text-2xl">📦</span>
                    <div>
                        <p class="font-semibold text-gray-800">Lihat Pesanan</p>
                        <p class="text-xs text-gray-600">Lacak status pesanan Anda</p>
                    </div>
                </a>
            </div>
        </div>

        <!-- Informasi Akun -->
        <div class="bg-purple-50 rounded-xl shadow-md p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">👤 Informasi Akun</h2>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600 mb-1">Nama</p>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-600 mb-1">Email</p>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                </div>
                @if(Auth::user()->no_hp)
                <div>
                    <p class="text-xs text-gray-600 mb-1">No. HP</p>
                    <p class="font-semibold text-gray-800">{{ Auth::user()->no_hp }}</p>
                </div>
                @endif
                <a href="{{ route('profile.show') }}" class="inline-block mt-2 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium hover:bg-purple-700 transition">
                    Edit Profil
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-refresh total spending setiap 30 detik untuk mendapatkan data terbaru
    setInterval(function() {
        fetch('{{ route("pembeli.dashboard") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            // Parse HTML response dan update total spending
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newSpending = doc.getElementById('total-spending');
            if (newSpending) {
                document.getElementById('total-spending').innerHTML = newSpending.innerHTML;
            }
        })
        .catch(error => {}); // Silent fail
    }, 30000); // Refresh setiap 30 detik
</script>
@endsection
