@extends('layouts.dashboard')

@section('content')
<div class="w-full">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 rounded-2xl shadow-2xl overflow-hidden mb-6">
        <div class="p-8">
            <div class="flex items-center gap-4">
                <a href="{{ route('penjual.pesanan.index') }}" class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center text-white hover:bg-white/30 transition">
                    ←
                </a>
                <div>
                    <h2 class="text-3xl font-bold text-white mb-1">Input Nomor Resi</h2>
                    <p class="text-purple-200">Pesanan #{{ $pesanan->id }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">❌</span>
                <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Order Details Card -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan</h3>
        
        <div class="flex flex-col md:flex-row gap-6">
            @if($pesanan->produk->gambar)
                <div class="w-full md:w-32 h-32 rounded-xl overflow-hidden flex-shrink-0 shadow-md">
                    <img src="{{ $pesanan->produk->image_url }}" alt="{{ $pesanan->produk->nama_produk }}" class="w-full h-full object-cover">
                </div>
            @endif

            <div class="flex-1">
                <h4 class="text-lg font-bold text-gray-800 mb-3">{{ $pesanan->produk->nama_produk }}</h4>
                
                <div class="grid md:grid-cols-2 gap-4 text-sm mb-4">
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-gray-600 mb-1"><span class="font-semibold">👤 Pembeli:</span></p>
                        <p class="text-gray-800 font-medium">{{ $pesanan->user->name }}</p>
                        @if($pesanan->user->no_hp)
                            <p class="text-gray-600 text-xs mt-1">📱 {{ $pesanan->user->no_hp }}</p>
                        @endif
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3">
                        <p class="text-gray-600 mb-1"><span class="font-semibold">📦 Jumlah:</span></p>
                        <p class="text-gray-800 font-medium">{{ $pesanan->jumlah }} unit</p>
                    </div>
                </div>

                @if($pesanan->user->alamat)
                    <div class="bg-blue-50 rounded-xl p-3 mb-4">
                        <p class="text-gray-600 mb-1"><span class="font-semibold">📍 Alamat Pengiriman:</span></p>
                        <p class="text-gray-800">{{ $pesanan->user->alamat }}</p>
                    </div>
                @endif
                
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-4 border-2 border-purple-200">
                    <p class="text-sm text-gray-600 mb-1 font-medium">Total Pembayaran:</p>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                </div>

                @if($pesanan->catatan)
                    <div class="mt-4 bg-yellow-50 border-l-4 border-yellow-400 rounded-lg p-3">
                        <p class="text-sm text-gray-700"><span class="font-semibold">📝 Catatan:</span> {{ $pesanan->catatan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Form Input Resi -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Input Nomor Resi Pengiriman</h3>
        
        <form action="{{ route('penjual.pesanan.kirim', $pesanan->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="resi" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nomor Resi <span class="text-red-500">*</span>
                </label>
                <input 
                    type="text" 
                    id="resi" 
                    name="resi" 
                    value="{{ old('resi') }}"
                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-200 transition-all @error('resi') border-red-500 @enderror" 
                    placeholder="Contoh: JNE123456789" 
                    required
                    autofocus
                >
                @error('resi')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 text-xs mt-2">Masukkan nomor resi dari ekspedisi yang Anda gunakan (JNE, J&T, SiCepat, dll)</p>
            </div>

            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <span class="text-2xl">ℹ️</span>
                    <div>
                        <p class="text-sm text-blue-800 font-semibold mb-2">Informasi Penting:</p>
                        <ul class="text-xs text-blue-700 space-y-1">
                            <li>✓ Pastikan nomor resi yang diinput sudah benar</li>
                            <li>✓ Status pesanan akan berubah menjadi "Di Kirim"</li>
                            <li>✓ Pembeli akan menerima notifikasi tentang pengiriman</li>
                            <li>✓ Nomor resi dapat dilihat oleh pembeli untuk tracking</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex gap-3">
                <button 
                    type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                >
                    <span>📦</span>
                    <span>Konfirmasi Pengiriman</span>
                </button>
                
                <a 
                    href="{{ route('penjual.pesanan.index') }}"
                    class="px-6 py-4 bg-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-300 transition-all duration-200 flex items-center justify-center"
                >
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
