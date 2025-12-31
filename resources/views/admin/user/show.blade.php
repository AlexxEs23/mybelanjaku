<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail User - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">👤</span>
                    <h1 class="text-xl font-bold">Admin Panel - Detail User</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">
                        ← Kembali
                    </a>
                    <span>{{ Auth::user()->name }}</span>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-8 py-6">
                <div class="flex items-center gap-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center">
                        <span class="text-4xl">
                            @if($user->role === 'admin') 👑
                            @elseif($user->role === 'penjual') 🏪
                            @else 🛒
                            @endif
                        </span>
                    </div>
                    <div class="text-white">
                        <h2 class="text-3xl font-bold">{{ $user->name }}</h2>
                        <p class="text-purple-200 mt-1">{{ $user->email }}</p>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Role -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm text-gray-500 mb-1">Role</p>
                        @if($user->role === 'admin')
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                👑 Admin
                            </span>
                        @elseif($user->role === 'penjual')
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                🏪 Penjual
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                🛒 Pembeli
                            </span>
                        @endif
                    </div>

                    <!-- Email -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->email }}</p>
                    </div>

                    <!-- No. Telp -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm text-gray-500 mb-1">Nomor Telepon</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->no_telp ?? '-' }}</p>
                    </div>

                    <!-- Terdaftar -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm text-gray-500 mb-1">Terdaftar Sejak</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->created_at->format('d F Y, H:i') }}</p>
                    </div>

                    <!-- Last Update -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm text-gray-500 mb-1">Terakhir Diupdate</p>
                        <p class="text-lg font-medium text-gray-900">{{ $user->updated_at->format('d F Y, H:i') }}</p>
                    </div>

                    <!-- ID -->
                    <div class="border-b border-gray-200 pb-4">
                        <p class="text-sm text-gray-500 mb-1">User ID</p>
                        <p class="text-lg font-medium text-gray-900">#{{ $user->id }}</p>
                    </div>

                </div>

                <!-- Alamat -->
                <div class="mt-6">
                    <p class="text-sm text-gray-500 mb-2">Alamat</p>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-gray-900">{{ $user->alamat ?? 'Alamat belum diisi' }}</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-6 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                        ✏️ Edit User
                    </a>
                    @if($user->id !== Auth::id())
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-6 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            🗑️ Hapus User
                        </button>
                    </form>
                    @endif
                </div>
            </div>

        </div>

        <!-- Additional Info -->
        @if($user->role === 'penjual')
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Statistik Penjual</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Produk</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $user->produk()->count() }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Produk Aktif</p>
                    <p class="text-2xl font-bold text-green-600">{{ $user->produk()->where('status', true)->count() }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-purple-600">0</p>
                </div>
            </div>
        </div>
        @endif

    </div>

</body>
</html>
