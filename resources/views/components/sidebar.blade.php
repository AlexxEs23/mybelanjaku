<!-- Sidebar -->
<div class="w-64 bg-gradient-to-b from-purple-800 to-purple-900 text-white h-screen fixed left-0 top-0 overflow-y-auto overflow-x-hidden pb-6">
    <!-- Logo & Brand -->
    <div class="p-6 border-b border-purple-700">
        <div class="flex items-center gap-3">
            <span class="text-4xl">🛒</span>
            <div>
                <h1 class="text-xl font-bold">UMKM Market</h1>
                <p class="text-xs text-purple-300">{{ ucfirst(Auth::user()->role) }} Panel</p>
            </div>
        </div>
    </div>

    <!-- User Info -->
    <div class="p-4 border-b border-purple-700">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-2xl">
                👤
            </div>
            <div>
                <p class="font-semibold text-sm">{{ Auth::user()->name }}</p>
                <p class="text-xs text-purple-300">{{ Auth::user()->email }}</p>
            </div>
        </div>
    </div>

    <!-- Menu Navigation -->
    <nav class="p-4 space-y-2">
        <!-- Dashboard - Semua Role -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('dashboard') ? 'bg-purple-700' : '' }}">
            <span class="text-xl">📊</span>
            <span class="font-medium">Dashboard</span>
        </a>
        
        <!-- Profil - Semua Role -->
        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('profile.*') ? 'bg-purple-700' : '' }}">
            <span class="text-xl">👤</span>
            <span class="font-medium">Profil Saya</span>
        </a>

        <!-- Menu Admin -->
        @if(Auth::user()->role === 'admin')
            <div class="pt-4 pb-2 px-4">
                <p class="text-xs text-purple-400 uppercase font-semibold">Admin Menu</p>
            </div>

            <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('notifikasi.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">🔔</span>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Notifikasi</span>
                    @php
                        $unreadCount = Auth::user()->notifikasis()->where('dibaca', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('chat.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">💬</span>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Chat Penjual</span>
                    @php
                        $unreadMessages = \App\Models\PesanChat::whereHas('chat', function($q) {
                            $q->where('admin_id', Auth::id());
                        })->where('pengirim_id', '!=', Auth::id())->where('dibaca', false)->count();
                    @endphp
                    @if($unreadMessages > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadMessages }}</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('admin.users.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">👥</span>
                <span class="font-medium">Kelola Pengguna</span>
            </a>

            <a href="{{ route('admin.seller.approval') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('admin.seller.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">✅</span>
                <span class="font-medium">Approval Penjual</span>
            </a>

            <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('produk.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">📦</span>
                <span class="font-medium">Lihat Semua Produk</span>
            </a>

            <a href="{{ route('admin.pesanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('admin.pesanan.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">🛍️</span>
                <span class="font-medium">Data Pesanan</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">📂</span>
                <span class="font-medium">Kelola Kategori</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">📊</span>
                <span class="font-medium">Laporan</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">⚙️</span>
                <span class="font-medium">Pengaturan</span>
            </a>
        @endif

        <!-- Menu Penjual -->
        @if(Auth::user()->role === 'penjual')
            <div class="pt-4 pb-2 px-4">
                <p class="text-xs text-purple-400 uppercase font-semibold">Penjual Menu</p>
            </div>

            <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('notifikasi.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">🔔</span>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Notifikasi</span>
                    @php
                        $unreadCount = Auth::user()->notifikasis()->where('dibaca', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('chat.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('chat.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">💬</span>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Chat Admin</span>
                    @php
                        $unreadMessages = \App\Models\PesanChat::whereHas('chat', function($q) {
                            $q->where('penjual_id', Auth::id());
                        })->where('pengirim_id', '!=', Auth::id())->where('dibaca', false)->count();
                    @endphp
                    @if($unreadMessages > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadMessages }}</span>
                    @endif
                </div>
            </a>

            <a href="{{ route('produk.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('produk.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">📦</span>
                <span class="font-medium">Produk Saya</span>
            </a>

            <a href="{{ route('produk.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('produk.create') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">➕</span>
                <span class="font-medium">Tambah Produk</span>
            </a>

            <a href="{{ route('penjual.pesanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('penjual.pesanan.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">🛍️</span>
                <span class="font-medium">Pesanan Masuk</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">💰</span>
                <span class="font-medium">Pendapatan</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">📊</span>
                <span class="font-medium">Statistik</span>
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">🏪</span>
                <span class="font-medium">Profil Toko</span>
            </a>
        @endif

        <!-- Menu Pembeli -->
        @if(Auth::user()->role === 'pembeli')
            <div class="pt-4 pb-2 px-4">
                <p class="text-xs text-purple-400 uppercase font-semibold">Pembeli Menu</p>
            </div>

            <a href="{{ route('notifikasi.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('notifikasi.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">🔔</span>
                <div class="flex-1 flex items-center justify-between">
                    <span class="font-medium">Notifikasi</span>
                    @php
                        $unreadCount = Auth::user()->notifikasis()->where('dibaca', false)->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                    @endif
                </div>
            </a>

            <a href="{{ url('/') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition">
                <span class="text-xl">🛒</span>
                <span class="font-medium">Belanja</span>
            </a>

            <a href="{{ route('pembeli.pesanan.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-purple-700 transition {{ request()->routeIs('pembeli.pesanan.*') ? 'bg-purple-700' : '' }}">
                <span class="text-xl">📦</span>
                <span class="font-medium">Pesanan Saya</span>
            </a>
        @endif

        <!-- Push Notification Toggle - Semua Role -->
        <div class="pt-4 pb-2 px-4">
            <p class="text-xs text-purple-400 uppercase font-semibold">Notifikasi Push</p>
        </div>
        
        <div class="px-4 py-3 space-y-2">
           <button onclick="enableNotifications()"
    class="w-full bg-blue-600 text-white py-2 rounded">
    Aktifkan Notifikasi
</button>

            
            <p id="notif-status" class="text-xs text-gray-300 text-center">Klik untuk mengaktifkan notifikasi push</p>
        </div>

        <!-- Logout - Semua Role -->
        <div class="pt-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-red-600 transition bg-red-500">
                    <span class="text-xl">🚪</span>
                    <span class="font-medium">Logout</span>
                </button>
            </form>
        </div>
    </nav>
</div>
