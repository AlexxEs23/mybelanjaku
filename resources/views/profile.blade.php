@extends('layouts.dashboard')

@section('title', 'Profil Saya - UMKM Marketplace')
@section('meta_description', 'Kelola informasi profil dan pengaturan akun Anda di UMKM Marketplace')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <header class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi profil Anda</p>
    </header>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded" role="alert">
            <div class="flex items-center mb-2">
                <span class="text-red-500 mr-2 text-xl" aria-hidden="true">✕</span>
                <p class="text-sm text-red-700 font-semibold">Terjadi kesalahan:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Profile Card --}}
    <article class="bg-white rounded-xl shadow-lg overflow-hidden">
        {{-- Profile Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-8 py-6">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl">
                    @if($user->role === 'admin')
                        👨‍💼
                    @elseif($user->role === 'penjual')
                        🏪
                    @else
                        👤
                    @endif
                </div>
                <div class="text-white">
                    <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                    <p class="text-purple-200">{{ ucfirst($user->role) }}</p>
                </div>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.update') }}" class="p-8" aria-label="Form Update Profil">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name"
                        name="name" 
                        value="{{ old('name', $user->name) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('name') border-red-500 @enderror" 
                        required
                        aria-required="true"
                        aria-invalid="@error('name') true @else false @enderror"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email"
                        name="email" 
                        value="{{ old('email', $user->email) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('email') border-red-500 @enderror" 
                        required
                        aria-required="true"
                        aria-invalid="@error('email') true @else false @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- No HP --}}
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-gray-700 mb-2">
                        No. HP <span class="text-red-500">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="no_hp"
                        name="no_hp" 
                        value="{{ old('no_hp', $user->no_hp) }}" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('no_hp') border-red-500 @enderror" 
                        required
                        aria-required="true"
                        aria-invalid="@error('no_hp') true @else false @enderror"
                    >
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                        id="alamat"
                        name="alamat" 
                        rows="3" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('alamat') border-red-500 @enderror" 
                        required
                        aria-required="true"
                        aria-invalid="@error('alamat') true @else false @enderror"
                    >{{ old('alamat', $user->alamat) }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Divider --}}
                <div class="border-t border-gray-200 pt-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ubah Password</h2>
                    <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                </div>

                {{-- Password Baru --}}
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <input 
                        type="password" 
                        id="password"
                        name="password" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('password') border-red-500 @enderror"
                        autocomplete="new-password"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation"
                        name="password_confirmation" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent"
                        autocomplete="new-password"
                    >
                </div>

                {{-- Buttons --}}
                <div class="flex gap-4 pt-6">
                    <button type="submit" 
                        class="flex-1 px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-semibold">
                        💾 Simpan Perubahan
                    </button>
                    <a href="{{ route('dashboard') }}" 
                        class="px-8 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold">
                        ← Kembali
                    </a>
                </div>
            </div>
        </form>
    </article>

    {{-- Info Box --}}
    <aside class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded" role="complementary">
        <div class="flex items-start">
            <span class="text-blue-500 mr-2 text-xl" aria-hidden="true">ℹ️</span>
            <div class="text-sm text-blue-700">
                <p class="font-semibold mb-1">Informasi</p>
                <ul class="list-disc list-inside space-y-1">
                    <li>Role Anda: <strong>{{ ucfirst($user->role) }}</strong></li>
                    <li>Status Akun: <strong>{{ ucfirst($user->status) }}</strong></li>
                    @if($user->role === 'penjual')
                        <li>Status Approval: 
                            @if($user->status_approval === 'approved')
                                <strong class="text-green-600">Disetujui ✓</strong>
                            @elseif($user->status_approval === 'pending')
                                <strong class="text-yellow-600">Menunggu Persetujuan ⏳</strong>
                            @else
                                <strong class="text-red-600">Ditolak ✗</strong>
                            @endif
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </aside>

    {{-- Daftar Sebagai Penjual (hanya untuk user biasa) --}}
    @if($user->role === 'user')
        <aside class="mt-6 bg-gradient-to-r from-purple-50 to-purple-100 border-2 border-purple-300 p-6 rounded-xl" role="complementary">
            <div class="flex items-start gap-4">
                <div class="text-5xl" aria-hidden="true">🏪</div>
                <div class="flex-1">
                    <h2 class="text-xl font-bold text-purple-800 mb-2">Ingin Menjadi Penjual?</h2>
                    <p class="text-sm text-purple-700 mb-4">
                        Bergabunglah dengan ribuan UMKM di platform kami! Daftar sebagai penjual dan mulai jual produk Anda ke seluruh Indonesia.
                    </p>
                    <ul class="text-sm text-purple-700 space-y-1 mb-4">
                        <li>✓ Gratis biaya pendaftaran</li>
                        <li>✓ Dashboard lengkap untuk kelola produk</li>
                        <li>✓ Jangkau jutaan pembeli potensial</li>
                    </ul>
                    <form method="POST" action="{{ route('profile.apply-seller') }}" onsubmit="return confirm('Yakin ingin mendaftar sebagai penjual? Akun Anda akan menunggu persetujuan admin.')">
                        @csrf
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition font-semibold">
                            🏪 Daftar Sebagai Penjual
                        </button>
                    </form>
                </div>
            </div>
        </aside>
    @endif
</div>
@endsection
