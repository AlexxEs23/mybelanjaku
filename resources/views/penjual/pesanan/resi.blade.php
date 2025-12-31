<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Resi - Penjual</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-2">
                    <span class="text-3xl">🛒</span>
                    <h1 class="text-2xl font-bold text-purple-700">UMKM Market - Penjual</h1>
                </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('penjual.pesanan.index') }}" class="px-4 py-2 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                        ← Kembali ke Pesanan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📦 Input Nomor Resi</h1>
            <p class="text-gray-600">Masukkan nomor resi untuk pesanan #{{ $pesanan->id }}</p>
        </div>

        <!-- Order Info Card -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Detail Pesanan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Produk:</p>
                    <p class="font-semibold text-gray-800">{{ $pesanan->produk->nama_produk }}</p>
                    <p class="text-sm text-gray-600">Jumlah: {{ $pesanan->jumlah }} unit</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total:</p>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($pesanan->total, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Penerima:</p>
                    <p class="font-semibold text-gray-800">{{ $pesanan->nama_penerima }}</p>
                    <p class="text-sm text-gray-600">{{ $pesanan->no_hp }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Ekspedisi:</p>
                    <p class="font-semibold text-gray-800 uppercase">{{ $pesanan->ekspedisi }}</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-600">Alamat Pengiriman:</p>
                    <p class="text-sm text-gray-800">{{ $pesanan->alamat }}</p>
                </div>
            </div>
        </div>

        <!-- Form Input Resi -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Pengiriman</h2>
            
            <form action="{{ route('penjual.pesanan.kirim', $pesanan->id) }}" method="POST">
                @csrf
                
                <div class="mb-6">
                    <label for="resi" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Resi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="resi" 
                           name="resi" 
                           required
                           class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 transition"
                           placeholder="Masukkan nomor resi pengiriman"
                           value="{{ old('resi') }}">
                    @error('resi')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        💡 Pastikan nomor resi sudah benar. Nomor ini akan dikirimkan kepada pembeli.
                    </p>
                </div>

                <div class="bg-purple-50 border-l-4 border-purple-600 p-4 rounded-lg mb-6">
                    <div class="flex items-start gap-2">
                        <span class="text-xl">ℹ️</span>
                        <div class="text-sm text-gray-700">
                            <p class="font-semibold mb-1">Informasi Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Pastikan barang sudah dikemas dengan baik</li>
                                <li>Verifikasi nomor resi sebelum mengirim</li>
                                <li>Status pesanan akan berubah menjadi "Dikirim" setelah submit</li>
                                <li>Pembeli akan menerima notifikasi nomor resi</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4">
                    <a href="{{ route('penjual.pesanan.index') }}" class="flex-1 px-6 py-3 border-2 border-purple-600 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition text-center">
                        Batal
                    </a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg font-semibold hover:shadow-lg transition">
                        📦 Kirim Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
