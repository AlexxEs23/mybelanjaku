@extends('layouts.dashboard')

@section('content')
<div class="w-full">
    <!-- Header Card -->
    <div class="bg-purple-700 rounded-2xl shadow-2xl mb-6 p-8 text-white">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center text-white text-4xl shadow-lg">
                        🛍️
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-1">Pesanan Masuk</h2>
                        <p class="text-purple-200">Kelola pesanan produk Anda via WhatsApp</p>
                    </div>
                </div>
                <div class="bg-purple-600 px-4 py-2 rounded-xl text-white font-semibold">
                    {{ $pesanan->total() }} Total Pesanan
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">✅</span>
                <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($pesanan->count() > 0)
        <div class="grid gap-6">
            @foreach($pesanan as $item)
                <div class="bg-white border-2 border-gray-200 rounded-2xl shadow-lg p-6 hover:border-purple-400 hover:shadow-xl transition-all duration-200">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-gray-500">Pesanan #{{ $item->id }}</span>
                            @if($item->status === 'pending')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-500 text-white shadow-md">
                                    ⏳ Pending
                                </span>
                            @elseif($item->status === 'diproses' || $item->status === 'di proses')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-500 text-white shadow-md">
                                    🔄 Di Proses
                                </span>
                            @elseif($item->status === 'di kirim')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-500 text-white shadow-md">
                                    📦 Di Kirim
                                </span>
                            @elseif($item->status === 'di terima')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-500 text-white shadow-md">
                                    ✅ Di Terima
                                </span>
                            @elseif($item->status === 'dibatalkan')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-500 text-white shadow-md">
                                    ❌ Dibatalkan
                                </span>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">{{ $item->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Product Image -->
                        @if($item->produk->gambar)
                            <div class="w-full md:w-32 h-32 rounded-xl overflow-hidden flex-shrink-0 shadow-md">
                                <img src="{{ $item->produk->image_url }}" alt="{{ $item->produk->nama_produk }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $item->produk->nama_produk }}</h3>
                            
                            <div class="grid md:grid-cols-2 gap-4 text-sm mb-4">
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-600 mb-1"><span class="font-semibold">👤 Pembeli:</span></p>
                                    <p class="text-gray-800 font-medium">{{ $item->user ? $item->user->name : 'Guest' }}</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3">
                                    <p class="text-gray-600 mb-1"><span class="font-semibold">📦 Jumlah:</span></p>
                                    <p class="text-gray-800 font-medium">{{ $item->jumlah }} unit × Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            
                            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 border-2 border-purple-200">
                                <p class="text-sm text-gray-600 mb-1 font-medium">Total Pembayaran:</p>
                                <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($item->total, 0, ',', '.') }}</p>
                            </div>

                            @if($item->catatan)
                                <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-3">
                                    <p class="text-sm text-gray-700"><span class="font-semibold">📝 Catatan:</span> {{ $item->catatan }}</p>
                                </div>
                            @endif

                            @if($item->status === 'diproses' || $item->status === 'di proses')
                                <div class="mt-4 pt-4 border-t border-gray-200 flex gap-3">
                                    <a href="{{ route('penjual.pesanan.resi-form', $item->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        <span>📦</span>
                                        <span>Input Resi & Kirim</span>
                                    </a>
                                </div>
                            @elseif($item->status === 'pending')
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-gray-500 italic flex items-center gap-2">
                                        <span>⏳</span> Menunggu admin memproses pesanan
                                    </p>
                                </div>
                            @elseif($item->status === 'dikirim' || $item->status === 'di kirim')
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-purple-600 font-medium flex items-center gap-2">
                                        <span>🚚</span> Menunggu pembeli mengkonfirmasi penerimaan
                                    </p>
                                    @if($item->resi)
                                        <div class="mt-2 bg-purple-50 rounded-lg p-3">
                                            <p class="text-sm text-gray-600"><span class="font-semibold">Resi:</span> {{ $item->resi }}</p>
                                        </div>
                                    @endif
                                </div>
                            @elseif($item->status === 'diterima' || $item->status === 'di terima' || $item->status === 'selesai')
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <p class="text-sm text-green-600 font-bold flex items-center gap-2">
                                        <span>✅</span> Pesanan telah diterima pembeli
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $pesanan->links() }}
        </div>
    @else
        <div class="bg-white rounded-2xl shadow-xl p-16 text-center">
            <div class="text-8xl mb-6">📦</div>
            <h3 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Pesanan</h3>
            <p class="text-gray-500 mb-2 text-lg">Pesanan dari pembeli akan muncul di sini</p>
            <p class="text-gray-400 text-sm mb-6">Pesanan akan muncul setelah pembeli melakukan checkout via WhatsApp</p>
            <a href="{{ url('/produk') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl">
                <span>📦</span>
                <span>Lihat Produk Saya</span>
            </a>
        </div>
    @endif

    <!-- Info Box -->
    <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="text-5xl">ℹ️</div>
            <div>
                <h3 class="font-bold text-blue-900 mb-3 text-lg">Informasi Penting</h3>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">📊</span>
                        <span>Data pesanan ini bersifat informasi untuk tracking pesanan yang masuk</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">💰</span>
                        <span>Transaksi dan konfirmasi pembayaran dilakukan melalui WhatsApp</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">⚡</span>
                        <span>Pastikan Anda merespon pesan WhatsApp dari pembeli dengan cepat</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="mt-0.5">📦</span>
                        <span>Stok produk sudah berkurang otomatis saat pembeli klik pesan WhatsApp</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
