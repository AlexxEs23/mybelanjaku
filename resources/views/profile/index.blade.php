@extends('layouts.dashboard')

@section('title', 'Pendaftaran Penjual UMKM')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Pendaftaran Penjual UMKM</h1>
        <p class="text-gray-600 mt-2">Daftarkan UMKM Anda untuk mulai berjualan di CheckoutAja</p>
    </div>

    @if($existingProfile)
        <!-- Sudah Terdaftar -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                @if($existingProfile->status_verifikasi === 'verified')
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">UMKM Anda Sudah Terverifikasi!</h2>
                    <p class="text-gray-600 mb-6">{{ $existingProfile->nama_umkm }} telah berhasil diverifikasi dan aktif.</p>
                @elseif($existingProfile->status_verifikasi === 'pending')
                    <div class="w-20 h-20 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Menunggu Verifikasi Admin</h2>
                    <p class="text-gray-600 mb-6">{{ $existingProfile->nama_umkm }} sedang dalam proses verifikasi. Mohon tunggu konfirmasi dari admin kami.</p>
                @else
                    <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-10 h-10 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Ditolak</h2>
                    <p class="text-gray-600 mb-6">Maaf, pendaftaran {{ $existingProfile->nama_umkm }} tidak dapat diverifikasi. Silakan hubungi admin untuk informasi lebih lanjut.</p>
                @endif
                
                <div class="bg-gray-50 rounded-lg p-6 text-left max-w-2xl mx-auto">
                    <h3 class="font-semibold text-gray-800 mb-3">Informasi UMKM:</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><strong>Nama UMKM:</strong> {{ $existingProfile->nama_umkm }}</p>
                        <p><strong>Kategori:</strong> {{ $existingProfile->kategori->nama_kategori ?? '-' }}</p>
                        <p><strong>Pemilik:</strong> {{ $existingProfile->nama_pemilik }}</p>
                        <p><strong>Wilayah:</strong> {{ $existingProfile->wilayah }}</p>
                        <p><strong>No. HP:</strong> {{ $existingProfile->no_hp }}</p>
                        <p><strong>Tahun Berdiri:</strong> {{ $existingProfile->tahun_berdiri }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Form Pendaftaran -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <form action="{{ route('profile-umkm.store') }}" method="POST">
                @csrf
                
                <div class="space-y-6">
                    <!-- Nama UMKM -->
                    <div>
                        <label for="nama_umkm" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama UMKM <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_umkm" id="nama_umkm" value="{{ old('nama_umkm') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_umkm') border-red-500 @enderror"
                            placeholder="Contoh: Warung Makan Bu Siti">
                        @error('nama_umkm')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori UMKM <span class="text-red-500">*</span>
                        </label>
                        <select name="kategori_id" id="kategori_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('kategori_id') border-red-500 @enderror">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kategori)
                                <option value="{{ $kategori->id }}" {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                    {{ $kategori->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Pemilik -->
                    <div>
                        <label for="nama_pemilik" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Pemilik <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama_pemilik') border-red-500 @enderror"
                            placeholder="Nama lengkap pemilik UMKM">
                        @error('nama_pemilik')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- No HP -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP/WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('no_hp') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wilayah -->
                    <div>
                        <label for="wilayah" class="block text-sm font-medium text-gray-700 mb-2">
                            Wilayah/Alamat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="wilayah" id="wilayah" value="{{ old('wilayah') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('wilayah') border-red-500 @enderror"
                            placeholder="Contoh: Bandung, Jawa Barat">
                        @error('wilayah')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Berdiri -->
                    <div>
                        <label for="tahun_berdiri" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Berdiri <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="tahun_berdiri" id="tahun_berdiri" value="{{ old('tahun_berdiri') }}" 
                            min="1900" max="{{ date('Y') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('tahun_berdiri') border-red-500 @enderror"
                            placeholder="{{ date('Y') }}">
                        @error('tahun_berdiri')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi_umkm" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi UMKM (Opsional)
                        </label>
                        <textarea name="deskripsi_umkm" id="deskripsi_umkm" rows="4" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('deskripsi_umkm') border-red-500 @enderror"
                            placeholder="Ceritakan tentang UMKM Anda...">{{ old('deskripsi_umkm') }}</textarea>
                        @error('deskripsi_umkm')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Proses Verifikasi</p>
                            <p>Setelah mendaftar, data Anda akan diverifikasi oleh admin dalam 1-3 hari kerja. Anda akan menerima notifikasi melalui email setelah proses verifikasi selesai.</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex gap-4">
                    <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition duration-200 font-medium">
                        Daftar Sekarang
                    </button>
                    <a href="{{ route('dashboard') }}" 
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    @endif
</div>
@endsection
