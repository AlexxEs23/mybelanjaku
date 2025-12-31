<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Masuk - Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    @include('components.sidebar')

    <div class="ml-64 p-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">🛍️ Pesanan Masuk</h2>
                    <p class="text-gray-600 mt-1">Pesanan produk Anda via WhatsApp</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if($pesanan->count() > 0)
                <div class="grid gap-4">
                    @foreach($pesanan as $item)
                        <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-purple-400 transition">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <span class="text-sm font-semibold text-gray-500">Pesanan #{{ $item->id }}</span>
                                        @if($item->status === 'pending')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($item->status === 'di proses')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Di Proses
                                            </span>
                                        @elseif($item->status === 'di kirim')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                Di Kirim
                                            </span>
                                        @elseif($item->status === 'di terima')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Di Terima
                                            </span>
                                        @elseif($item->status === 'dibatalkan')
                                            <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                Dibatalkan
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $item->produk->nama_produk }}</h3>
                                    
                                    <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-600 mb-4">
                                        <div>
                                            <p class="mb-1"><span class="font-semibold">Pembeli:</span> {{ $item->user ? $item->user->name : 'Guest' }}</p>
                                            <p class="mb-1"><span class="font-semibold">Tanggal:</span> {{ $item->created_at->format('d M Y, H:i') }}</p>
                                        </div>
                                        <div>
                                            <p class="mb-1"><span class="font-semibold">Jumlah:</span> {{ $item->jumlah }} unit</p>
                                            <p class="mb-1"><span class="font-semibold">Harga Satuan:</span> Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-purple-50 rounded-lg p-4">
                                        <p class="text-sm text-gray-600 mb-1">Total Pembayaran:</p>
                                        <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                                    </div>

                                    @if($item->catatan)
                                        <div class="mt-4 bg-gray-50 rounded-lg p-3">
                                            <p class="text-sm text-gray-600"><span class="font-semibold">Catatan:</span> {{ $item->catatan }}</p>
                                        </div>
                                    @endif

                                    @if($item->status === 'di proses')
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <form action="{{ route('penjual.pesanan.kirim', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition duration-200">
                                                    Tandai Sudah Dikirim
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($item->status === 'pending')
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <p class="text-sm text-gray-500 italic">Menunggu admin memproses pesanan</p>
                                        </div>
                                    @elseif($item->status === 'di kirim')
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <p class="text-sm text-gray-500 italic">Menunggu pembeli mengkonfirmasi penerimaan</p>
                                        </div>
                                    @elseif($item->status === 'di terima')
                                        <div class="mt-4 pt-4 border-t border-gray-200">
                                            <p class="text-sm text-green-600 font-medium">✓ Pesanan telah diterima pembeli</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $pesanan->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📦</div>
                    <p class="text-gray-500 text-lg">Belum ada pesanan masuk</p>
                    <p class="text-gray-400 text-sm mt-2">Pesanan akan muncul setelah pembeli melakukan checkout via WhatsApp</p>
                </div>
            @endif
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
            <div class="flex items-start gap-4">
                <div class="text-4xl">ℹ️</div>
                <div>
                    <h3 class="font-bold text-blue-900 mb-2">Informasi Penting</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Data pesanan ini bersifat informasi untuk tracking pesanan yang masuk</li>
                        <li>• Transaksi dan konfirmasi pembayaran dilakukan melalui WhatsApp</li>
                        <li>• Pastikan Anda merespon pesan WhatsApp dari pembeli dengan cepat</li>
                        <li>• Stok produk sudah berkurang otomatis saat pembeli klik pesan WhatsApp</li>
                    </ul>
                </div>
            </div>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                        ← Kembali ke Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📦 Pesanan Produk Saya</h1>
            <p class="text-gray-600">Kelola pesanan pelanggan untuk produk Anda</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-xl">✅</span>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-xl">⚠️</span>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-yellow-100 rounded-lg p-4">
                <div class="text-2xl mb-2">⏳</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $pesanan->where('status', 'menunggu')->count() }}</div>
                <div class="text-sm text-yellow-600">Menunggu</div>
            </div>
            <div class="bg-blue-100 rounded-lg p-4">
                <div class="text-2xl mb-2">🔄</div>
                <div class="text-2xl font-bold text-blue-700">{{ $pesanan->where('status', 'diproses')->count() }}</div>
                <div class="text-sm text-blue-600">Siap Kirim</div>
            </div>
            <div class="bg-purple-100 rounded-lg p-4">
                <div class="text-2xl mb-2">🚚</div>
                <div class="text-2xl font-bold text-purple-700">{{ $pesanan->where('status', 'dikirim')->count() }}</div>
                <div class="text-sm text-purple-600">Dikirim</div>
            </div>
            <div class="bg-green-100 rounded-lg p-4">
                <div class="text-2xl mb-2">✅</div>
                <div class="text-2xl font-bold text-green-700">{{ $pesanan->where('status', 'selesai')->count() }}</div>
                <div class="text-sm text-green-600">Selesai</div>
            </div>
            <div class="bg-red-100 rounded-lg p-4">
                <div class="text-2xl mb-2">❌</div>
                <div class="text-2xl font-bold text-red-700">{{ $pesanan->where('status', 'dibatalkan')->count() }}</div>
                <div class="text-sm text-red-600">Dibatalkan</div>
            </div>
        </div>

        <!-- Orders Grid -->
        <div class="grid grid-cols-1 gap-4">
            @forelse($pesanan as $item)
                <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-bold text-gray-800">Pesanan #{{ $item->id }}</h3>
                                @if($item->status == 'menunggu')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        ⏳ Menunggu Diproses
                                    </span>
                                @elseif($item->status == 'diproses')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        🔄 Siap Dikirim
                                    </span>
                                @elseif($item->status == 'dikirim')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        🚚 Dalam Pengiriman
                                    </span>
                                @elseif($item->status == 'selesai')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        ✅ Selesai
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        ❌ Dibatalkan
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-4 mb-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Produk:</p>
                                <p class="font-semibold text-gray-800">{{ $item->produk->nama_produk }}</p>
                                <p class="text-sm text-gray-600">Jumlah: {{ $item->jumlah }} unit</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Pembeli:</p>
                                <p class="font-semibold text-gray-800">{{ $item->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $item->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Penerima:</p>
                                <p class="font-semibold text-gray-800">{{ $item->nama_penerima }}</p>
                                <p class="text-sm text-gray-600">{{ $item->no_hp }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 mb-1">Ekspedisi:</p>
                                <p class="font-semibold text-gray-800 uppercase">{{ $item->ekspedisi }}</p>
                                <p class="text-sm text-gray-600">{{ $item->metode_pembayaran == 'transfer_bank' ? 'Transfer Bank' : 'COD' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm text-gray-600 mb-1">Alamat Pengiriman:</p>
                                <p class="text-sm text-gray-800">{{ $item->alamat }}</p>
                            </div>
                            @if($item->catatan_pembeli)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600 mb-1">Catatan Pembeli:</p>
                                    <p class="text-sm text-gray-800 italic">{{ $item->catatan_pembeli }}</p>
                                </div>
                            @endif
                            @if($item->resi)
                                <div class="md:col-span-2">
                                    <p class="text-sm text-gray-600 mb-1">No. Resi:</p>
                                    <p class="font-semibold text-purple-600">{{ $item->resi }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($item->status == 'diproses')
                        <div class="border-t border-gray-200 pt-4">
                            <a href="{{ route('penjual.pesanan.resi-form', $item->id) }}" class="inline-block px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg font-semibold hover:shadow-lg transition">
                                📦 Input Nomor Resi & Kirim
                            </a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-xl shadow-md p-12 text-center">
                    <div class="text-6xl mb-4">📦</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-600">Pesanan untuk produk Anda akan muncul di sini</p>
                </div>
            @endforelse
>>>>>>> 5853d8b8a2aaca5bd65654069268449123eb8540
        </div>
    </div>
</body>
</html>
