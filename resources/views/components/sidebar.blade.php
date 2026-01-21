<!-- Sidebar -->
<aside id="sidebar" class="w-64 bg-gradient-to-b from-purple-800 to-purple-900 text-white fixed top-0 left-0 h-screen overflow-y-auto shadow-2xl z-40 transition-transform duration-300 lg:translate-x-0 -translate-x-full" style="background: linear-gradient(to bottom, #6b21a8, #581c87); color: white;">

    <!-- Logo & Brand -->
    <div class="p-6 border-b border-purple-700">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/sidelogo.png') }}" alt="CheckoutAja Logo" class="h-12 w-auto">
            <div>
                <h1 class="text-xl font-bold">CheckoutAja</h1>
                <p class="text-xs text-purple-300">
                    {{ ucfirst(Auth::user()->role) }} Panel
                </p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-purple-700">
        <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
        <p class="text-xs text-purple-300">{{ Auth::user()->email }}</p>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
            <span class="text-xl">📊</span>
            <span>Dashboard</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('profile.show') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
            <span class="text-xl">👤</span>
            <span>Profil Saya</span>
        </a>

        {{-- ================= ADMIN ================= --}}
        @if(Auth::user()->role === 'admin')
            <div class="pt-4 text-xs uppercase text-purple-300">
                Admin Menu
            </div>

            <a href="{{ route('notifikasi.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 relative">
                <span>🔔 Notifikasi</span>
                <span id="notif-badge-admin" class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"></span>
            </a>

            <a href="{{ route('admin.pesanan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                🛍️ Pesanan
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                👥 Pengguna
            </a>

            <a href="{{ route('admin.seller.approval') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                ✅ Approval Penjual
            </a>

            <a href="{{ route('produk.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                📦 Produk
            </a>

            <a href="{{ route('kategori.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                🏷️ Kelola Kategori
            </a>
        @endif

        {{-- ================= PENJUAL ================= --}}
        @if(Auth::user()->role === 'penjual' && Auth::user()->status_approval === 'approved')
            <div class="pt-4 text-xs uppercase text-purple-300">
                Penjual Menu
            </div>

            <a href="{{ route('notifikasi.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 relative">
                <span>🔔 Notifikasi</span>
                <span id="notif-badge-penjual" class="hidden absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-bold"></span>
            </a>

            <a href="{{ route('produk.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                📦 Produk Saya
            </a>

            <a href="{{ route('produk.create') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                ➕ Tambah Produk
            </a>

            <a href="{{ route('kategori.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                🏷️ Kelola Kategori
            </a>

            <a href="{{ route('penjual.pesanan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700">
                🛍️ Pesanan Masuk
            </a>
        @endif

        {{-- ================= USER (PEMBELI) ================= --}}
        @if(Auth::user()->role === 'user' || (Auth::user()->role === 'penjual' && Auth::user()->status_approval !== 'approved'))
            <div class="pt-4 text-xs uppercase text-purple-300">
                User Menu
            </div>

            <a href="{{ url('/') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">🛒</span>
                <span>Belanja</span>
            </a>

            <a href="{{ route('pembeli.pesanan.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">📦</span>
                <span>Pesanan Saya</span>
            </a>
        @endif

        <!-- ================= PUSH NOTIFICATION ================= -->
       <button
    id="enable-notif-btn"
    class="w-full bg-blue-600 text-white py-2 rounded">
    Aktifkan Notifikasi
</button>

<p id="notif-status"
   class="text-xs text-center text-gray-300 mt-2">
    Klik untuk mengaktifkan notifikasi
</p>


        <!-- Logout -->
        <div class="pt-6">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 py-2 rounded-lg font-semibold transition">
                    🚪 Logout
                </button>
            </form>
        </div>

    </nav>
</aside>
