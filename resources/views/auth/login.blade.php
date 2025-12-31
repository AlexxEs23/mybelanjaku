<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - UMKM Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-purple-800 min-h-screen flex items-center justify-center p-3 sm:p-5 overflow-x-hidden">
    <div class="flex flex-col lg:flex-row max-w-4xl w-full bg-white rounded-2xl overflow-hidden shadow-2xl mx-auto">
        <!-- Left Section -->
        <div class="flex-1 bg-gradient-to-br from-purple-600 to-purple-800 p-6 sm:p-8 lg:p-12 text-white flex flex-col justify-center">
            <div class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-5 flex items-center gap-2">
                <span class="text-3xl sm:text-4xl">🛒</span>
                UMKM Market
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">Selamat Datang Kembali!</h2>
            <p class="text-sm sm:text-base opacity-90 leading-relaxed break-words">
                Bergabunglah dengan ribuan UMKM Indonesia yang telah mempercayai platform kami untuk mengembangkan bisnis mereka.
            </p>
            <div class="mt-6 sm:mt-8 space-y-3 sm:space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold">✓</div>
                    <span>Jual produk lokal ke seluruh Indonesia</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Mudah dikelola & ramah UMKM</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Dukungan pembayaran lengkap</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Biaya rendah, profit maksimal</span>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex-1 p-6 sm:p-8 lg:p-12">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Masuk ke Akun Anda</h1>
                <p class="text-gray-600 text-xs sm:text-sm">Masukkan email dan password untuk melanjutkan</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-center">
                        <span class="text-red-500 mr-2">⚠️</span>
                        <div class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-5 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <div class="flex items-center">
                        <span class="text-green-500 mr-2">✓</span>
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-gray-700 font-medium text-sm">Email</label>
                    <input 
                        type="email" 
                        name="email"
                        id="email" 
                        value="{{ old('email') }}"
                        placeholder="nama@email.com" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition @error('email') border-red-500 @enderror"
                    >
                </div>

                <div class="mb-5">
                    <label for="password" class="block mb-2 text-gray-700 font-medium text-sm">Password</label>
                    <input 
                        type="password" 
                        name="password"
                        id="password" 
                        placeholder="Masukkan password Anda" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition @error('password') border-red-500 @enderror"
                    >
                </div>

                <div class="flex items-center justify-between mb-5">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="{{ url('/forgot-password') }}" class="text-purple-600 text-sm hover:underline">
                        Lupa Password?
                    </a>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-purple-800 text-white py-3 rounded-lg text-base font-semibold hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition transform"
                >
                    Masuk
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">atau masuk dengan</span>
                </div>
            </div>

            <!-- Google Login Button -->
            <a href="{{ route('auth.google') }}" 
                class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-300 text-gray-700 py-3 rounded-lg text-base font-semibold hover:bg-gray-50 hover:shadow-md transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="text-center text-gray-600 text-sm mt-6">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="text-purple-600 font-semibold hover:underline">
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </div>
</body>
</html>