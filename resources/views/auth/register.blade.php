<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - CheckoutAja.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-purple-800 min-h-screen flex items-center justify-center p-3 sm:p-5 overflow-x-hidden">
    <div class="flex flex-col lg:flex-row max-w-4xl w-full bg-white rounded-2xl overflow-hidden shadow-2xl my-4 mx-auto">
        <!-- Left Section -->
        <div class="flex-1 bg-gradient-to-br from-purple-600 to-purple-800 p-6 sm:p-8 lg:p-12 text-white flex flex-col justify-center">
            <div class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-5 flex items-center gap-2">
                <span class="text-3xl sm:text-4xl">🛒</span>
                CheckoutAja.com
            </div>
            <h2 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4">Mulai Berjualan Sekarang!</h2>
            <p class="text-sm sm:text-base opacity-90 leading-relaxed break-words">
                Daftarkan bisnis UMKM Anda dan jangkau jutaan pembeli di seluruh Indonesia. Proses pendaftaran mudah dan cepat!
            </p>
            <div class="mt-6 sm:mt-8 space-y-3 sm:space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Gratis biaya pendaftaran</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Dashboard lengkap & mudah digunakan</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Dukungan pelanggan 24/7</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">✓</div>
                    <span class="text-sm sm:text-base break-words">Sistem pembayaran aman & terpercaya</span>
                </div>
            </div>
        </div>

        <!-- Right Section -->
        <div class="flex-1 p-6 sm:p-8 lg:p-12 overflow-y-auto max-h-[90vh] lg:max-h-screen">
            <div class="mb-6 sm:mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Buat Akun Baru</h1>
                <p class="text-gray-600 text-xs sm:text-sm">Lengkapi formulir di bawah untuk mendaftar</p>
            </div>

            @if ($errors->any())
                <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-start">
                        <span class="text-red-500 mr-2 mt-0.5">⚠️</span>
                        <div class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <p class="mb-1">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <div class="mb-4 sm:mb-5">
                    <label for="name" class="block mb-2 text-gray-700 font-medium text-sm">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="name"
                        id="name" 
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama lengkap Anda" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition @error('name') border-red-500 @enderror"
                    >
                </div>

                <div class="mb-4 sm:mb-5">
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

                <div class="mb-4 sm:mb-5">
                    <label for="no_hp" class="block mb-2 text-gray-700 font-medium text-sm">Nomor HP/WhatsApp</label>
                    <input 
                        type="tel" 
                        name="no_hp"
                        id="no_hp" 
                        value="{{ old('no_hp') }}"
                        placeholder="08xxxxxxxxxx" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition @error('no_hp') border-red-500 @enderror"
                    >
                </div>

                <div class="mb-4 sm:mb-5">
                    <label for="alamat" class="block mb-2 text-gray-700 font-medium text-sm">Alamat Lengkap</label>
                    <textarea 
                        name="alamat"
                        id="alamat" 
                        rows="3"
                        placeholder="Masukkan alamat lengkap Anda"
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition @error('alamat') border-red-500 @enderror"
                    >{{ old('alamat') }}</textarea>
                </div>

                <div class="mb-4 sm:mb-5">
                    <label for="password" class="block mb-2 text-gray-700 font-medium text-sm">Password</label>
                    <input 
                        type="password" 
                        name="password"
                        id="password" 
                        placeholder="Minimal 6 karakter" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition @error('password') border-red-500 @enderror"
                    >
                </div>

                <div class="mb-4 sm:mb-5">
                    <label for="password_confirmation" class="block mb-2 text-gray-700 font-medium text-sm">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation"
                        id="password_confirmation" 
                        placeholder="Masukkan ulang password" 
                        required
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg text-sm focus:outline-none focus:border-purple-600 focus:ring-4 focus:ring-purple-100 transition"
                    >
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-purple-800 text-white py-3 rounded-lg text-base font-semibold hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0 transition transform"
                >
                    Daftar Sekarang
                </button>
            </form>

            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500">atau daftar dengan</span>
                </div>
            </div>

            <a href="{{ route('auth.google') }}" class="flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-300 rounded-lg bg-white text-sm hover:border-purple-600 hover:bg-purple-50 transition mb-6">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Daftar dengan Google</span>
            </a>

            <div class="text-center text-gray-600 text-sm">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-purple-600 font-semibold hover:underline">
                    Masuk Sekarang
                </a>
            </div>
        </div>
    </div>

    <script>
        // Real-time validation
        const password = document.getElementById('password');
        const passwordConfirm = document.getElementById('password_confirmation');
        const phoneInput = document.getElementById('no_hp');
        
        // Validasi nomor HP (hanya angka)
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            if (this.value.length > 0 && !this.value.startsWith('0')) {
                this.setCustomValidity('Nomor HP harus diawali dengan 0');
            } else if (this.value.length > 0 && this.value.length < 10) {
                this.setCustomValidity('Nomor HP minimal 10 digit');
            } else if (this.value.length > 15) {
                this.setCustomValidity('Nomor HP maksimal 15 digit');
            } else {
                this.setCustomValidity('');
            }
        });
        
        // Validasi password match
        passwordConfirm.addEventListener('input', function() {
            if (this.value !== password.value) {
                this.setCustomValidity('Password tidak cocok');
            } else {
                this.setCustomValidity('');
            }
        });
        
        password.addEventListener('input', function() {
            if (passwordConfirm.value && passwordConfirm.value !== this.value) {
                passwordConfirm.setCustomValidity('Password tidak cocok');
            } else {
                passwordConfirm.setCustomValidity('');
            }
        });
    </script>
</body>
</html>