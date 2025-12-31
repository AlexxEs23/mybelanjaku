<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - UMKM Marketplace</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-purple-600 to-purple-800 min-h-screen flex items-center justify-center p-5">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Reset Password</h1>
                <p class="text-gray-600 text-sm">Masukkan password baru untuk akun Anda</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            @foreach ($errors->all() as $error)
                                <p class="text-sm text-red-700 font-medium">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('password.update') }}" method="POST" class="space-y-5">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="email_display" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </span>
                        <input 
                            type="email" 
                            id="email_display" 
                            value="{{ $email }}"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-50 cursor-not-allowed"
                            disabled
                        >
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password Baru
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Minimal 8 karakter"
                            required
                            autofocus
                        >
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Password minimal 8 karakter</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </span>
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition"
                            placeholder="Ulangi password baru"
                            required
                        >
                    </div>
                </div>

                <button 
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-purple-800 text-white font-semibold py-3 rounded-lg hover:from-purple-700 hover:to-purple-900 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                >
                    Reset Password
                </button>
            </form>

            <!-- Password Requirements -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Persyaratan Password:</h3>
                <ul class="text-xs text-blue-800 space-y-1">
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Minimal 8 karakter
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Gunakan kombinasi huruf, angka, dan simbol untuk keamanan lebih baik
                    </li>
                    <li class="flex items-start">
                        <svg class="w-4 h-4 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Jangan gunakan password yang mudah ditebak
                    </li>
                </ul>
            </div>

            <!-- Back to Login -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center text-sm text-purple-600 hover:text-purple-800 font-medium transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Login
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 text-white text-sm">
            <p>&copy; {{ date('Y') }} UMKM Marketplace. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
