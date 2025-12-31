<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-purple-600 to-purple-800 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">👤</span>
                    <h1 class="text-xl font-bold">Admin Panel - Tambah User</h1>
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
        
        <div class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Formulir Tambah User Baru</h2>

            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <!-- Nama -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Minimal 8 karakter</p>
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent">
                </div>

                <!-- Role -->
                <div class="mb-6">
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        Role <span class="text-red-500">*</span>
                    </label>
                    <select name="role" id="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('role') border-red-500 @enderror">
                        <option value="">Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Admin</option>
                        <option value="penjual" {{ old('role') == 'penjual' ? 'selected' : '' }}>🏪 Penjual</option>
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>🛍️ User</option>
                    </select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No. HP -->
                <div class="mb-6">
                    <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor HP
                    </label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('no_hp') border-red-500 @enderror">
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>
                    <textarea name="alamat" id="alamat" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('alamat') border-red-500 @enderror">{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-4">
                    <a href="{{ route('admin.users.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-800 text-white rounded-lg hover:shadow-lg transition">
                        💾 Simpan User
                    </button>
                </div>
            </form>
        </div>

    </div>

</body>
</html>
