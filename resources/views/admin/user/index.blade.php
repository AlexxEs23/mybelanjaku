<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">👤</span>
                    <h1 class="text-xl font-bold">Admin Panel - Manajemen User</h1>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-white/20 rounded-lg hover:bg-white/30 transition">
                        🏠 Dashboard
                    </a>
                    <span>{{ Auth::user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 rounded-lg hover:bg-red-600 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center">
            <span>✅ {{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="text-green-700 font-bold">×</button>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex justify-between items-center">
            <span>❌ {{ session('error') }}</span>
            <button onclick="this.parentElement.remove()" class="text-red-700 font-bold">×</button>
        </div>
        @endif

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Daftar User</h2>
                    <p class="text-gray-600 mt-1">Kelola semua pengguna sistem</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-medium">
                    ➕ Tambah User Baru
                </a>
            </div>
        </div>

        <!-- User Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. HP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terdaftar</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $index => $user)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="text-xl">
                                            @if($user->role === 'admin') 👑
                                            @elseif($user->role === 'penjual') 🏪
                                            @else 🛒
                                            @endif
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'admin')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        👑 Admin
                                    </span>
                                @elseif($user->role === 'penjual')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        🏪 Penjual
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        🛒 Pembeli
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $user->no_hp ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                        👁️ Lihat
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600 transition">
                                        ✏️ Edit
                                    </a>
                                    @if($user->id !== Auth::id())
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <span class="text-6xl mb-4">👥</span>
                                    <p class="text-lg">Belum ada user terdaftar</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $users->links() }}
            </div>
            @endif
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total User</p>
                        <p class="text-3xl font-bold text-gray-800">{{ $users->total() }}</p>
                    </div>
                    <div class="text-4xl">👥</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Admin</p>
                        <p class="text-3xl font-bold text-red-600">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="text-4xl">👑</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Penjual</p>
                        <p class="text-3xl font-bold text-blue-600">{{ \App\Models\User::where('role', 'penjual')->count() }}</p>
                    </div>
                    <div class="text-4xl">🏪</div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pembeli</p>
                        <p class="text-3xl font-bold text-green-600">{{ \App\Models\User::where('role', 'pembeli')->count() }}</p>
                    </div>
                    <div class="text-4xl">🛒</div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
