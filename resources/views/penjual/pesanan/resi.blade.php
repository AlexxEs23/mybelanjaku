@extends('layouts.dashboard')

@section('content')
<div class="w-full">
    <!-- Header Card -->
    <div class="bg-purple-700 rounded-2xl shadow-2xl mb-6 p-6 text-white">
        <div class="p-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white text-4xl shadow-lg">
                    📦
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-white mb-1">Input Nomor Resi</h1>
                    <p class="text-purple-200">Masukkan nomor resi untuk pesanan #{{ $pesanan->id }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Info Card -->
    <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-t-4 border-purple-500">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <span>📋</span> Detail Pesanan
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-600 mb-1 font-medium">📦 Produk:</p>
                <p class="font-bold text-gray-800 text-lg">{{ $pesanan->produk->nama_produk }}</p>
                <p class="text-sm text-gray-600 mt-1">Jumlah: {{ $pesanan->jumlah }} unit</p>
            </div>
            <div class="bg-purple-50 rounded-xl p-4 border-2 border-purple-200">
                <p class="text-sm text-gray-600 mb-1 font-medium">💰 Total:</p>
                <p class="text-3xl font-bold text-purple-600">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-600 mb-1 font-medium">👤 Penerima:</p>
                <p class="font-semibold text-gray-800">{{ $pesanan->nama_penerima }}</p>
                <p class="text-sm text-gray-600 mt-1">📱 {{ $pesanan->no_hp }}</p>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-600 mb-1 font-medium">🚚 Ekspedisi:</p>
                <p class="font-bold text-gray-800 uppercase text-lg">{{ $pesanan->ekspedisi }}</p>
            </div>
            <div class="md:col-span-2 bg-gray-50 rounded-xl p-4">
                <p class="text-sm text-gray-600 mb-2 font-medium">📍 Alamat Pengiriman:</p>
                <p class="text-sm text-gray-800 leading-relaxed">{{ $pesanan->alamat }}</p>
            </div>
        </div>
    </div>

    <!-- Form Input Resi -->
    <div class="bg-white rounded-2xl shadow-xl p-8 border-t-4 border-indigo-500">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
            <span>🚚</span> Informasi Pengiriman
        </h2>
        
        <form action="{{ route('penjual.pesanan.kirim', $pesanan->id) }}" method="POST">
            @csrf
            
            <div class="mb-6">
                <label for="resi" class="block text-sm font-bold text-gray-700 mb-3 flex items-center gap-2">
                    <span>📋</span> Nomor Resi <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="resi" 
                       name="resi" 
                       required
                       class="w-full px-5 py-4 border-2 border-gray-300 rounded-xl focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-all duration-200 text-lg"
                       placeholder="Contoh: JNE123456789012"
                       value="{{ old('resi') }}">
                @error('resi')
                    <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                        <span>⚠️</span> {{ $message }}
                    </p>
                @enderror
                <p class="mt-3 text-sm text-gray-600 bg-gray-50 rounded-lg p-3 flex items-start gap-2">
                    <span class="text-lg">💡</span>
                    <span>Pastikan nomor resi sudah benar. Nomor ini akan dikirimkan kepada pembeli untuk melacak paket.</span>
                </p>
            </div>

            <div class="bg-purple-50 border-l-4 border-purple-600 rounded-xl p-5 mb-8">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">ℹ️</span>
                    <div class="text-sm text-gray-700">
                        <p class="font-bold mb-2 text-base">Informasi Penting:</p>
                        <ul class="space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">📦</span>
                                <span>Pastikan barang sudah dikemas dengan baik dan aman</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">✅</span>
                                <span>Verifikasi nomor resi dengan ekspedisi sebelum mengirim</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">🔄</span>
                                <span>Status pesanan akan berubah menjadi "Dikirim" setelah submit</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">🔔</span>
                                <span>Pembeli akan menerima notifikasi nomor resi secara otomatis</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('penjual.pesanan.index') }}" class="flex-1 px-6 py-4 border-2 border-purple-600 text-purple-600 rounded-xl font-bold hover:bg-purple-50 transition-all duration-200 text-center flex items-center justify-center gap-2">
                    <span>←</span>
                    <span>Batal</span>
                </a>
                <button type="submit" class="flex-1 px-6 py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                    <span>📦</span>
                    <span>Kirim Pesanan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
