<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - UMKM Market</title>
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
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">📦 Pesanan Saya</h1>
                    <p class="text-gray-600">Pantau dan kelola semua pesanan Anda</p>
                </div>
                <a href="{{ route('pembeli.dashboard') }}" class="px-6 py-3 bg-white border-2 border-purple-600 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition flex items-center gap-2">
                    <span>←</span>
                    <span>Kembali ke Dashboard</span>
                </a>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="mb-6 bg-white rounded-xl shadow-md p-4">
            <div class="flex gap-2 overflow-x-auto">
                <button class="px-6 py-2 bg-purple-600 text-white rounded-lg font-semibold whitespace-nowrap" data-filter="all">
                    Semua ({{ $pesanan->count() }})
                </button>
                <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 whitespace-nowrap" data-filter="menunggu">
                    ⏳ Menunggu ({{ $pesanan->where('status', 'menunggu')->count() }})
                </button>
                <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 whitespace-nowrap" data-filter="diproses">
                    🔄 Diproses ({{ $pesanan->where('status', 'diproses')->count() }})
                </button>
                <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 whitespace-nowrap" data-filter="dikirim">
                    🚚 Dikirim ({{ $pesanan->where('status', 'dikirim')->count() }})
                </button>
                <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 whitespace-nowrap" data-filter="selesai">
                    ✅ Selesai ({{ $pesanan->where('status', 'selesai')->count() }})
                </button>
                <button class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 whitespace-nowrap" data-filter="dibatalkan">
                    ❌ Dibatalkan ({{ $pesanan->where('status', 'dibatalkan')->count() }})
                </button>
            </div>
        </div>

        <!-- Orders List -->
        <div class="space-y-4">
            @forelse($pesanan as $order)
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition order-card" data-status="{{ $order->status }}">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-bold text-gray-800">Pesanan #{{ $order->id }}</h3>
                                @if($order->status == 'menunggu')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        ⏳ Menunggu Konfirmasi
                                    </span>
                                @elseif($order->status == 'diproses')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        🔄 Diproses
                                    </span>
                                @elseif($order->status == 'dikirim')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        🚚 Dalam Pengiriman
                                    </span>
                                @elseif($order->status == 'selesai')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        ✅ Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        ❌ Dibatalkan
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 rounded-lg overflow-hidden bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center flex-shrink-0">
                                @if($order->produk->gambar)
                                    <img src="{{ asset('storage/' . $order->produk->gambar) }}" 
                                         alt="{{ $order->produk->nama_produk }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl">📦</span>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-800 mb-1">{{ $order->produk->nama_produk }}</h4>
                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($order->produk->deskripsi, 80) }}</p>
                                <div class="flex items-center gap-4 text-sm">
                                    <span class="text-gray-600">Jumlah: {{ $order->jumlah }} unit</span>
                                    <span class="text-gray-600">•</span>
                                    <span class="text-purple-600 font-semibold">{{ $order->produk->kategori->nama_kategori ?? 'Umum' }}</span>
                                </div>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600">Penjual: <span class="font-semibold text-gray-800">{{ $order->produk->user->name }}</span></p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Info -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-600">Penerima:</p>
                                    <p class="font-semibold text-gray-800">{{ $order->nama_penerima }}</p>
                                    <p class="text-gray-600">{{ $order->no_hp }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-600">Ekspedisi:</p>
                                    <p class="font-semibold text-gray-800 uppercase">{{ $order->ekspedisi }}</p>
                                    <p class="text-gray-600">{{ $order->metode_pembayaran == 'transfer_bank' ? 'Transfer Bank' : 'COD' }}</p>
                                </div>
                                @if($order->resi)
                                    <div>
                                        <p class="text-gray-600">No. Resi:</p>
                                        <p class="font-semibold text-purple-600">{{ $order->resi }}</p>
                                        <button onclick="copyResi('{{ $order->resi }}')" class="text-xs text-purple-600 hover:text-purple-700">
                                            📋 Salin Resi
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 pt-4 border-t border-gray-200 flex gap-3">
                            <button onclick="showDetail({{ $order->id }})" class="px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition text-sm font-semibold">
                                Lihat Detail
                            </button>
                            
                            @if($order->resi)
                                <a href="#" class="px-4 py-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition text-sm font-semibold">
                                    Lacak Paket
                                </a>
                            @endif
                            
                            @if($order->status == 'selesai')
                                <button class="px-4 py-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition text-sm font-semibold">
                                    Beri Ulasan
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Hidden Detail Panel -->
                    <div id="detail-{{ $order->id }}" class="hidden mt-4 pt-4 border-t border-gray-200">
                        <h4 class="font-bold text-gray-800 mb-3">Detail Lengkap</h4>
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Alamat Pengiriman:</p>
                                <p class="text-sm text-gray-800">{{ $order->alamat }}</p>
                            </div>
                            @if($order->catatan_pembeli)
                                <div>
                                    <p class="text-sm text-gray-600">Catatan Anda:</p>
                                    <p class="text-sm text-gray-800 italic">{{ $order->catatan_pembeli }}</p>
                                </div>
                            @endif
                            <div>
                                <p class="text-sm text-gray-600">Rincian Pembayaran:</p>
                                <div class="space-y-1 mt-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Harga ({{ $order->jumlah }}x)</span>
                                        <span class="text-gray-800">Rp {{ number_format($order->produk->harga * $order->jumlah, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Ongkir</span>
                                        <span class="text-gray-800">Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm font-bold border-t pt-2">
                                        <span>Total</span>
                                        <span class="text-purple-600">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="text-6xl mb-4">📦</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-600 mb-6">Yuk mulai belanja produk UMKM favorit Anda!</p>
                    <a href="{{ route('home') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Mulai Belanja
                    </a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center">
                <p class="text-gray-400">© 2025 UMKM Market. Platform e-commerce untuk UMKM Indonesia.</p>
            </div>
        </div>
    </footer>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('[data-filter]');
        const orderCards = document.querySelectorAll('.order-card');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                const filter = button.dataset.filter;
                
                // Update active button
                filterButtons.forEach(btn => {
                    btn.classList.remove('bg-purple-600', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                });
                button.classList.remove('bg-gray-100', 'text-gray-700');
                button.classList.add('bg-purple-600', 'text-white');

                // Filter cards
                orderCards.forEach(card => {
                    if (filter === 'all' || card.dataset.status === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        // Show detail
        function showDetail(id) {
            const detail = document.getElementById('detail-' + id);
            detail.classList.toggle('hidden');
        }

        // Copy resi
        function copyResi(resi) {
            navigator.clipboard.writeText(resi).then(() => {
                alert('Nomor resi berhasil disalin: ' + resi);
            });
        }
    </script>
</body>
</html>
