{{-- Footer --}}
<footer class="bg-gray-900 text-gray-300 mt-auto" role="contentinfo">
    {{-- Main Footer --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- About Section --}}
            <div>
                <h2 class="text-white text-lg font-bold mb-4 flex items-center gap-2">
                    <span class="text-2xl" aria-hidden="true">🛒</span>
                    <span>UMKM Marketplace</span>
                </h2>
                <p class="text-sm leading-relaxed mb-4">
                    Platform jual beli online yang menghubungkan UMKM Indonesia dengan pembeli di seluruh nusantara. Dukung ekonomi lokal dengan berbelanja produk berkualitas.
                </p>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition" aria-label="Facebook">
                        <span aria-hidden="true">📘</span>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition" aria-label="Instagram">
                        <span aria-hidden="true">📷</span>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition" aria-label="Twitter">
                        <span aria-hidden="true">🐦</span>
                    </a>
                    <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center hover:bg-purple-600 transition" aria-label="YouTube">
                        <span aria-hidden="true">📺</span>
                    </a>
                </div>
            </div>
            
            {{-- Untuk Pembeli --}}
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Untuk Pembeli</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('home') }}" class="hover:text-purple-400 transition">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Cara Berbelanja
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Kebijakan Pengembalian
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Metode Pembayaran
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Lacak Pesanan
                        </a>
                    </li>
                </ul>
            </div>
            
            {{-- Untuk Penjual --}}
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Untuk Penjual</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="{{ route('register') }}" class="hover:text-purple-400 transition">
                            Daftar Sebagai Penjual
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Panduan Penjual
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Pusat Edukasi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Biaya & Komisi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Tips Sukses Jualan
                        </a>
                    </li>
                </ul>
            </div>
            
            {{-- Bantuan & Informasi --}}
            <div>
                <h3 class="text-white text-lg font-semibold mb-4">Bantuan & Informasi</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Pusat Bantuan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Hubungi Kami
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Syarat & Ketentuan
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Kebijakan Privasi
                        </a>
                    </li>
                    <li>
                        <a href="#" class="hover:text-purple-400 transition">
                            Tentang Kami
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    
    {{-- Bottom Footer --}}
    <div class="border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm">
                <div class="text-center md:text-left">
                    <p>&copy; {{ date('Y') }} UMKM Marketplace. All rights reserved.</p>
                    <p class="text-xs text-gray-400 mt-1">
                        Platform e-commerce untuk mendukung UMKM Indonesia
                    </p>
                </div>
                <div class="flex flex-wrap justify-center gap-4 text-xs">
                    <a href="#" class="hover:text-purple-400 transition">Kebijakan Privasi</a>
                    <span>|</span>
                    <a href="#" class="hover:text-purple-400 transition">Syarat Layanan</a>
                    <span>|</span>
                    <a href="#" class="hover:text-purple-400 transition">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>
