@extends('layouts.app')

@section('title', $produk->nama_produk . ' - MyBelanjaMu')
@section('meta_description', Str::limit(strip_tags($produk->deskripsi), 155) . ' | Harga: Rp ' . number_format($produk->harga, 0, ',', '.') . ' | Belanja di MyBelanjaMu')
@section('meta_keywords', $produk->nama_produk . ', ' . $produk->kategori->nama_kategori . ', produk UMKM, belanja online, MyBelanjaMu')
@section('og_image', $produk->image_url ?? asset('images/og-default.png'))

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
    <!-- Breadcrumb -->
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('home') }}" class="hover:text-purple-600">Beranda</a>
            <span>/</span>
            <a href="{{ route('home') }}#{{ $produk->kategori->nama_kategori }}" class="hover:text-purple-600">{{ $produk->kategori->nama_kategori }}</a>
            <span>/</span>
            <span class="text-gray-900 font-medium">{{ $produk->nama_produk }}</span>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8 p-4 sm:p-6 lg:p-8">
            <!-- Product Image -->
            <div class="space-y-4">
                @if($produk->gambar)
                    <div class="aspect-square rounded-xl overflow-hidden bg-gray-100">
                        <img src="{{ $produk->image_url }}" alt="{{ $produk->nama_produk }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="aspect-square rounded-xl bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center">
                        <span class="text-8xl sm:text-9xl">📦</span>
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Category Badge -->
                <div>
                    <span class="inline-block px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs sm:text-sm font-semibold">
                        {{ $produk->kategori->nama_kategori }}
                    </span>
                </div>

                <!-- Product Name -->
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 leading-tight">{{ $produk->nama_produk }}</h1>

                <!-- Price -->
                <div class="bg-gray-50 rounded-lg p-4 sm:p-6">
                    <p class="text-sm text-gray-600 mb-1">Harga</p>
                    <p class="text-3xl sm:text-4xl font-bold text-purple-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                </div>

                <!-- Stock & Seller Info -->
                <div class="space-y-3 sm:space-y-4 border-t border-b py-4 sm:py-6">
                    <div class="flex items-center justify-between">
                        <span class="text-sm sm:text-base text-gray-600 font-medium">Stok Tersedia</span>
                        @if($produk->stok > 10)
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs sm:text-sm font-semibold">{{ $produk->stok }} unit</span>
                        @elseif($produk->stok > 0)
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs sm:text-sm font-semibold">{{ $produk->stok }} unit tersisa</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs sm:text-sm font-semibold">Stok Habis</span>
                        @endif
                    </div>

                    <div class="flex items-start justify-between">
                        <span class="text-sm sm:text-base text-gray-600 font-medium">Penjual</span>
                        <div class="text-right">
                            <p class="text-sm sm:text-base font-semibold text-gray-900">{{ $produk->user->name }}</p>
                            @if($produk->user->no_hp)
                                <p class="text-xs sm:text-sm text-gray-600 mt-1">{{ $produk->user->no_hp }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- WhatsApp Checkout Button -->
                @if($produk->stok > 0 && $produk->nomor_whatsapp)
                    <a href="{{ route('whatsapp.checkout.show', $produk->id) }}" 
                       class="group relative overflow-hidden w-full px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-2xl font-extrabold text-base sm:text-lg transition-all duration-300 shadow-xl hover:shadow-2xl flex items-center justify-center gap-3 transform hover:scale-105 active:scale-95">
                        <!-- Shimmer Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
                        
                        <svg class="w-7 h-7 relative z-10 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                        </svg>
                        <span class="relative z-10 tracking-wide">BELI SEKARANG VIA WHATSAPP</span>
                        <svg class="w-5 h-5 relative z-10 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                @elseif($produk->stok == 0)
                    <button disabled class="w-full px-6 py-4 bg-gray-300 text-gray-600 rounded-2xl font-bold text-base sm:text-lg cursor-not-allowed opacity-60 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Stok Habis
                    </button>
                @else
                    <button disabled class="w-full px-6 py-4 bg-gray-300 text-gray-600 rounded-2xl font-bold text-base sm:text-lg cursor-not-allowed opacity-60 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Tidak Tersedia
                    </button>
                @endif

                <!-- Description -->
                <div class="space-y-3 pt-4 sm:pt-6">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-900">Deskripsi Produk</h2>
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-line">{{ $produk->deskripsi }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Rating & Review Section -->
    <div class="mt-8 sm:mt-12">
        <x-product-rating-section :produk="$produk" />
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="mt-12 sm:mt-16">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Produk Terkait</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                @foreach($relatedProducts as $related)
                    <a href="{{ route('produk.detail', $related->slug) }}" class="bg-white border border-gray-200 rounded-xl hover:shadow-xl transition overflow-hidden group">
                        <div class="aspect-square bg-gradient-to-br from-purple-100 to-purple-200 flex items-center justify-center overflow-hidden">
                            @if($related->gambar)
                                <img src="{{ $related->image_url }}" alt="{{ $related->nama_produk }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                            @else
                                <span class="text-5xl sm:text-6xl group-hover:scale-110 transition duration-300">📦</span>
                            @endif
                        </div>
                        <div class="p-3 sm:p-4">
                            <h3 class="font-semibold text-sm sm:text-base text-gray-800 mb-1 sm:mb-2 line-clamp-2">{{ $related->nama_produk }}</h3>
                            <p class="text-base sm:text-lg font-bold text-purple-600">Rp {{ number_format($related->harga, 0, ',', '.') }}</p>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2">{{ $related->user->name }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<!-- Quantity Modal -->
<div id="quantityModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-md w-full">
        <h3 class="text-xl sm:text-2xl font-bold text-gray-800 mb-4">Masukkan Jumlah Pesanan</h3>
        <p class="text-sm sm:text-base text-gray-600 mb-4">Produk: <span id="modalProdukName" class="font-semibold"></span></p>
        <p class="text-sm sm:text-base text-gray-600 mb-6">Stok tersedia: <span id="modalMaxStock" class="font-semibold text-purple-600"></span> unit</p>
        
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
            <input type="number" id="quantityInput" min="1" value="1" 
                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 text-base sm:text-lg font-semibold">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
            <textarea id="catatanInput" rows="3" placeholder="Tambahkan catatan untuk penjual..."
                      class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 text-sm sm:text-base resize-none"></textarea>
        </div>
        
        <div class="flex gap-3">
            <button onclick="closeModal()" class="flex-1 px-4 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition font-medium">
                Batal
            </button>
            <button onclick="submitCheckout()" class="flex-1 px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium">
                Lanjut Checkout
            </button>
        </div>
    </div>
</div>

<script>
    let currentFormId = null;
    let maxStock = 0;

    function showQuantityModal(produkId, produkName, stock) {
        currentFormId = 'checkoutForm-' + produkId;
        maxStock = stock;
        
        document.getElementById('modalProdukName').textContent = produkName;
        document.getElementById('modalMaxStock').textContent = stock;
        document.getElementById('quantityInput').max = stock;
        document.getElementById('quantityInput').value = 1;
        document.getElementById('catatanInput').value = '';
        document.getElementById('quantityModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('quantityModal').classList.add('hidden');
        currentFormId = null;
    }

    function submitCheckout() {
        if (!currentFormId) return;
        
        const form = document.getElementById(currentFormId);
        const quantity = parseInt(document.getElementById('quantityInput').value);
        const catatan = document.getElementById('catatanInput').value;
        
        if (quantity < 1) {
            alert('Jumlah minimal adalah 1');
            return;
        }
        
        if (quantity > maxStock) {
            alert(`Jumlah maksimal adalah ${maxStock} (stok tersedia)`);
            return;
        }
        
        form.querySelector('input[name="jumlah"]').value = quantity;
        form.querySelector('input[name="catatan"]').value = catatan;
        
        closeModal();
        form.submit();
    }

    // Close modal when clicking outside
    document.getElementById('quantityModal').addEventListener('click', function(e) {
        if (e.target.id === 'quantityModal') {
            closeModal();
        }
    });
</script>
@endsection
