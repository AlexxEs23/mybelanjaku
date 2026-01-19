@extends('layouts.dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl mb-6 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-xl">✅</span>
            <p class="font-medium text-green-700">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl mb-6 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-xl">❌</span>
            <p class="font-medium text-red-700">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Header Card -->
    <div class="bg-purple-700 rounded-2xl shadow-xl p-8 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-4xl">👥</span>
                        <h2 class="text-3xl font-bold">Manajemen User</h2>
                    </div>
                    <p class="text-purple-100">Kelola semua pengguna sistem dengan mudah</p>
                </div>
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-purple-700 rounded-xl hover:bg-purple-50 transition font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span>➕</span>
                    <span>Tambah User Baru</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm mb-1">Total User</p>
                    <p class="text-3xl font-bold">{{ $users->total() }}</p>
                </div>
                <div class="text-5xl opacity-80">👥</div>
            </div>
        </div>
        <div class="bg-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm mb-1">Admin</p>
                    <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'admin')->count() }}</p>
                </div>
                <div class="text-5xl opacity-80">👑</div>
            </div>
        </div>
        <div class="bg-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm mb-1">Penjual</p>
                    <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'penjual')->count() }}</p>
                </div>
                <div class="text-5xl opacity-80">🏪</div>
            </div>
        </div>
        <div class="bg-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white text-sm mb-1">Pembeli</p>
                    <p class="text-3xl font-bold">{{ \App\Models\User::where('role', 'pembeli')->count() }}</p>
                </div>
                <div class="text-5xl opacity-80">🛒</div>
            </div>
        </div>
    </div>

    <!-- User Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
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
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
