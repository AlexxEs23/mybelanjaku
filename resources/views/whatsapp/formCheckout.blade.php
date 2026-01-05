<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Checkout - {{ $produk->nama_produk }}</title>
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
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-2">
                    <span class="text-3xl">🛒</span>
                    <h1 class="text-2xl font-bold text-purple-700">MyBelanjaMu</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    @auth
                        <div class="relative group">
                            <button class="flex items-center gap-2 px-4 py-2 bg-purple-100 rounded-lg hover:bg-purple-200 transition">
                                <span class="text-2xl">👤</span>
                                <div class="text-left">
                                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-600">{{ ucfirst(Auth::user()->role) }}</p>
                                </div>
                                <span class="text-gray-500">▼</span>
                            </button>
                            
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
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Login
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- WhatsApp Checkout Container -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <nav class="flex items-center gap-2 text-sm text-gray-600">
                <a href="{{ route('home') }}" class="hover:text-purple-600 transition">🏠 Beranda</a>
                <span>›</span>
                <a href="{{ route('produk.detail', $produk->slug) }}" class="hover:text-purple-600 transition">{{ $produk->nama_produk }}</a>
                <span>›</span>
                <span class="text-purple-600 font-semibold">WhatsApp Checkout</span>
            </nav>
        </div>

        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">💬 Checkout via WhatsApp</h1>
            <p class="text-gray-600">Lengkapi informasi pengiriman untuk melanjutkan ke WhatsApp</p>
        </div>

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-xl">⚠️</span>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-start gap-2">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <p class="font-semibold mb-2">Terdapat kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-6">
            <!-- Product Info Card -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span>📦</span> Detail Produk
                </h2>
                
                <div class="flex gap-4">
                    <div class="w-24 h-24 rounded-lg overflow-hidden bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center flex-shrink-0">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/' . $produk->gambar) }}" 
                                 alt="{{ $produk->nama_produk }}"
                                 class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl">📦</span>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $produk->nama_produk }}</h3>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($produk->deskripsi, 100) }}</p>
                        <div class="flex items-center gap-4 text-sm">
                            <span class="text-purple-600 font-semibold">📂 {{ $produk->kategori->nama_kategori ?? 'Umum' }}</span>
                            <span class="text-gray-600">📦 Stok: {{ $produk->stok }}</span>
                        </div>
                        <div class="mt-2">
                            <span class="text-2xl font-bold text-purple-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            <span class="text-sm text-gray-500">/ unit</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Checkout -->
            <form action="{{ route('whatsapp.checkout') }}" method="POST" class="space-y-6" id="checkoutForm" onsubmit="return confirmCheckout(event)">
                @csrf
                <input type="hidden" name="produk_id" value="{{ $produk->id }}">

                <!-- Quantity Selection -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>🔢</span> Jumlah Pembelian
                    </h2>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Produk</label>
                            <div class="flex items-center gap-4">
                                <button type="button" onclick="decreaseQty()" class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition font-bold">-</button>
                                <input type="number" 
                                       id="jumlah" 
                                       name="jumlah" 
                                       value="{{ old('jumlah', 1) }}" 
                                       min="1" 
                                       max="{{ $produk->stok }}" 
                                       class="w-24 text-center px-4 py-2 border-2 border-purple-600 rounded-lg focus:outline-none focus:border-purple-700 font-semibold text-lg"
                                       onchange="updateTotal()">
                                <button type="button" onclick="increaseQty()" class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition font-bold">+</button>
                                <span class="text-sm text-gray-600">Maksimal: {{ $produk->stok }} unit</span>
                            </div>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700 font-medium">Total Harga:</span>
                                <span id="totalPrice" class="text-2xl font-bold text-purple-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>📍</span> Informasi Pengiriman
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="nama_penerima" class="block text-sm font-semibold text-gray-700 mb-2">Nama Penerima <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="nama_penerima" 
                                       name="nama_penerima" 
                                       required
                                       value="{{ old('nama_penerima', Auth::check() ? Auth::user()->name : '') }}"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                                       placeholder="Nama lengkap penerima">
                            </div>
                            <div>
                                <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">Nomor HP <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       id="no_hp" 
                                       name="no_hp" 
                                       required
                                       value="{{ old('no_hp') }}"
                                       class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                                       placeholder="08xxxxxxxxxx">
                            </div>
                        </div>
                        
                        <div>
                            <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                            <textarea id="alamat" 
                                      name="alamat" 
                                      rows="4" 
                                      required
                                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                                      placeholder="Masukkan alamat lengkap pengiriman (Nama Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Provinsi, Kode Pos)">{{ old('alamat') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">💡 Pastikan alamat lengkap dan benar untuk memudahkan pengiriman</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-xl shadow-lg p-8 border-2 border-green-200">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-1 font-medium">Total Pembayaran</p>
                            <p id="totalPriceFinal" class="text-4xl font-extrabold text-purple-600 mb-2">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Gratis konsultasi via WhatsApp
                            </p>
                        </div>
                        
                        <button type="submit" class="group relative overflow-hidden bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105 active:scale-95">
                            <!-- Shimmer Effect -->
                            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                            
                            <!-- WhatsApp Icon with Animation -->
                            <div class="flex items-center gap-3 relative z-10">
                                <svg class="w-7 h-7 animate-bounce" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                                <span class="font-extrabold tracking-wide">CHECKOUT SEKARANG</span>
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </div>
                        </button>
                    </div>
                    
                    <!-- Info Text -->
                    <div class="mt-6 flex items-start gap-3 bg-white/70 backdrop-blur-sm rounded-lg p-4 border border-green-200">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-gray-800 mb-1">✨ Proses Cepat & Mudah</p>
                            <p class="text-xs text-gray-600">Setelah checkout, Anda akan langsung terhubung dengan penjual via WhatsApp untuk konfirmasi dan pembayaran. Pesanan akan diproses segera setelah konfirmasi.</p>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const hargaSatuan = {{ $produk->harga }};
        const maxStok = {{ $produk->stok }};

        function confirmCheckout(event) {
            event.preventDefault();
            
            // Ambil data form
            const jumlah = document.getElementById('jumlah').value;
            const namaPenerima = document.getElementById('nama_penerima').value;
            const noHp = document.getElementById('no_hp').value;
            const alamat = document.getElementById('alamat').value;
            const total = hargaSatuan * jumlah;
            
            // Validasi form
            if (!namaPenerima || !noHp || !alamat) {
                alert('⚠️ Mohon lengkapi semua data pengiriman!');
                return false;
            }
            
            // Tampilkan popup konfirmasi custom
            const confirmMessage = `
╔═══════════════════════════════════╗
║   📦 KONFIRMASI PESANAN           ║
╚═══════════════════════════════════╝

Produk: {{ $produk->nama_produk }}
Jumlah: ${jumlah} unit
Total: Rp ${total.toLocaleString('id-ID')}

📍 Info Pengiriman:
Nama: ${namaPenerima}
HP: ${noHp}
Alamat: ${alamat.substring(0, 50)}${alamat.length > 50 ? '...' : ''}

⚠️ PERHATIAN:
✓ Pesanan akan masuk ke sistem
✓ Stok produk akan berkurang
✓ Anda akan diarahkan ke WhatsApp penjual

Apakah data sudah benar dan yakin melanjutkan?
            `.trim();
            
            if (confirm(confirmMessage)) {
                // Submit form jika dikonfirmasi
                document.getElementById('checkoutForm').submit();
                return true;
            }
            
            return false;
        }

        function updateTotal() {
            const jumlah = parseInt(document.getElementById('jumlah').value) || 1;
            const total = hargaSatuan * jumlah;
            
            document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('totalPriceFinal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function increaseQty() {
            const input = document.getElementById('jumlah');
            let value = parseInt(input.value) || 1;
            
            if (value < maxStok) {
                input.value = value + 1;
                updateTotal();
            }
        }

        function decreaseQty() {
            const input = document.getElementById('jumlah');
            let value = parseInt(input.value) || 1;
            
            if (value > 1) {
                input.value = value - 1;
                updateTotal();
            }
        }

        // Initialize total on page load
        updateTotal();
    </script>
</body>
</html>
