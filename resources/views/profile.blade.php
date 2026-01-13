@extends('layouts.dashboard')

@section('title', 'Profil Saya - MyBelanjaMu')
@section('meta_description', 'Kelola informasi profil dan pengaturan akun Anda di MyBelanjaMu')

@section('content')
<div class="max-w-full">
    {{-- Header Card --}}
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 rounded-2xl shadow-2xl overflow-hidden mb-6 relative">
        <div class="relative p-6 md:p-8">
            <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
                <div class="w-20 h-20 md:w-24 md:h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-5xl md:text-6xl shadow-lg border-4 border-white/30">
                    @if($user->role === 'admin')
                        👨‍💼
                    @elseif($user->role === 'penjual')
                        🏪
                    @else
                        👤
                    @endif
                </div>
                <div class="text-white text-center md:text-left flex-1">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-2">{{ $user->name }}</h1>
                    <p class="text-purple-200 text-sm md:text-base mb-3">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs md:text-sm font-medium">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->role === 'penjual')
                            @if($user->status_approval === 'approved')
                                <span class="px-3 py-1 bg-green-400/30 backdrop-blur-sm rounded-full text-xs md:text-sm font-medium">
                                    ✓ Terverifikasi
                                </span>
                            @elseif($user->status_approval === 'pending')
                                <span class="px-3 py-1 bg-yellow-400/30 backdrop-blur-sm rounded-full text-xs md:text-sm font-medium">
                                    ⏳ Menunggu Approval
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-400/30 backdrop-blur-sm rounded-full text-xs md:text-sm font-medium">
                                    ✗ Ditolak
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm" role="alert">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xl" aria-hidden="true">❌</span>
                <p class="text-sm font-bold text-red-700">Terjadi kesalahan:</p>
            </div>
            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Form --}}
        <div class="lg:col-span-2">
            <article class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="p-6 md:p-8">
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                        <span>⚙️</span> Informasi Profil
                    </h2>

                    <form method="POST" action="{{ route('profile.update') }}" aria-label="Form Update Profil">
                        @csrf
                        @method('PUT')

                        <div class="space-y-5">
                            {{-- Nama --}}
                            <div>
                                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="name"
                                    name="name" 
                                    value="{{ old('name', $user->name) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition @error('name') border-red-500 @enderror" 
                                    required
                                    aria-required="true"
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <span>⚠️</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    id="email"
                                    name="email" 
                                    value="{{ old('email', $user->email) }}" 
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition @error('email') border-red-500 @enderror" 
                                    required
                                    aria-required="true"
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <span>⚠️</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- No HP --}}
                            <div>
                                <label for="no_hp" class="block text-sm font-bold text-gray-700 mb-2">
                                    No. HP <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    id="no_hp"
                                    name="no_hp" 
                                    value="{{ old('no_hp', $user->no_hp) }}" 
                                    placeholder="08xxxxxxxxxx"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition @error('no_hp') border-red-500 @enderror" 
                                    required
                                    aria-required="true"
                                >
                                @error('no_hp')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <span>⚠️</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Alamat --}}
                            <div>
                                <label for="alamat" class="block text-sm font-bold text-gray-700 mb-2">
                                    Alamat Lengkap <span class="text-red-500">*</span>
                                </label>
                                <textarea 
                                    id="alamat"
                                    name="alamat" 
                                    rows="3" 
                                    placeholder="Jalan, Kecamatan, Kabupaten/Kota, Provinsi"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition @error('alamat') border-red-500 @enderror" 
                                    required
                                    aria-required="true"
                                >{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <span>⚠️</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Divider --}}
                            <div class="border-t-2 border-gray-200 pt-6 mt-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center gap-2">
                                    <span>🔒</span> Ubah Password
                                </h3>
                                <p class="text-sm text-gray-600 mb-4">Kosongkan jika tidak ingin mengubah password</p>
                            </div>

                            {{-- Password Baru --}}
                            <div>
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-2">
                                    Password Baru
                                </label>
                                <input 
                                    type="password" 
                                    id="password"
                                    name="password" 
                                    placeholder="Minimal 8 karakter"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition @error('password') border-red-500 @enderror"
                                    autocomplete="new-password"
                                >
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 flex items-center gap-1">
                                        <span>⚠️</span> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Konfirmasi Password --}}
                            <div>
                                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-2">
                                    Konfirmasi Password Baru
                                </label>
                                <input 
                                    type="password" 
                                    id="password_confirmation"
                                    name="password_confirmation" 
                                    placeholder="Ulangi password baru"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-600 focus:border-purple-600 transition"
                                    autocomplete="new-password"
                                >
                            </div>

                            {{-- Buttons --}}
                            <div class="flex flex-col sm:flex-row gap-3 pt-6">
                                <button type="submit" 
                                    class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2">
                                    <span>💾</span>
                                    <span>Simpan Perubahan</span>
                                </button>
                                <a href="{{ route('dashboard') }}" 
                                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-200 font-bold text-center flex items-center justify-center gap-2">
                                    <span>←</span>
                                    <span>Kembali</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </article>
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1 space-y-6">
            {{-- Info Box --}}
            <aside class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 p-5 rounded-2xl shadow-lg" role="complementary">
                <div class="flex items-start gap-3">
                    <span class="text-3xl" aria-hidden="true">ℹ️</span>
                    <div class="text-sm text-blue-800">
                        <p class="font-bold mb-3 text-base">Informasi Akun</p>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span><strong>Role:</strong> {{ ucfirst($user->role) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                <span><strong>Status:</strong> {{ ucfirst($user->status) }}</span>
                            </div>
                            @if($user->role === 'penjual')
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                    <span><strong>Approval:</strong> 
                                        @if($user->status_approval === 'approved')
                                            <span class="text-green-600 font-bold">✓ Disetujui</span>
                                        @elseif($user->status_approval === 'pending')
                                            <span class="text-yellow-600 font-bold">⏳ Pending</span>
                                        @else
                                            <span class="text-red-600 font-bold">✗ Ditolak</span>
                                        @endif
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Security Tips --}}
            <aside class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 p-5 rounded-2xl shadow-lg">
                <div class="flex items-start gap-3">
                    <span class="text-3xl" aria-hidden="true">🛡️</span>
                    <div class="text-sm text-yellow-800">
                        <p class="font-bold mb-3 text-base">Tips Keamanan</p>
                        <ul class="space-y-2">
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">✓</span>
                                <span>Gunakan password minimal 8 karakter</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">✓</span>
                                <span>Jangan bagikan password ke siapapun</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="mt-0.5">✓</span>
                                <span>Update informasi secara berkala</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>

            {{-- Daftar Sebagai Penjual --}}
            @if($user->role === 'user')
                <aside class="bg-gradient-to-br from-purple-50 to-indigo-50 border-2 border-purple-300 p-5 rounded-2xl shadow-lg" role="complementary">
                    <div class="flex flex-col items-center text-center">
                        <div class="text-6xl mb-3" aria-hidden="true">🏪</div>
                        <h2 class="text-lg font-bold text-purple-800 mb-2">Jadi Penjual?</h2>
                        <p class="text-xs text-purple-700 mb-3">
                            Bergabung dengan UMKM lainnya dan mulai jual produk Anda!
                        </p>
                        <ul class="text-xs text-purple-700 space-y-1 mb-4 text-left">
                            <li class="flex items-center gap-2">
                                <span>✓</span> Gratis pendaftaran
                            </li>
                            <li class="flex items-center gap-2">
                                <span>✓</span> Dashboard lengkap
                            </li>
                            <li class="flex items-center gap-2">
                                <span>✓</span> Jangkau lebih luas
                            </li>
                        </ul>
                        <form method="POST" action="{{ route('profile.apply-seller') }}" onsubmit="return confirm('Yakin ingin mendaftar sebagai penjual?')" class="w-full">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                                <span>🏪</span>
                                <span>Daftar Sekarang</span>
                            </button>
                        </form>
                    </div>
                </aside>
            @endif
        </div>
    </div>
</div>
@endsection
