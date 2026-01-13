@extends('layouts.dashboard')

@section('content')
<script type="module">
        // 1. Import Firebase SDK (versi 10.7.1)
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

        // 2. Firebase Configuration
        const firebaseConfig = {
            apiKey: "AIzaSyCMKwa144N9ve0JnxmNv4wGKSrIB8zkA2A",
            authDomain: "ecommerceumkm-4dbc3.firebaseapp.com",
            projectId: "ecommerceumkm-4dbc3",
            storageBucket: "ecommerceumkm-4dbc3.appspot.com",
            messagingSenderId: "638039749336",
            appId: "1:638039749336:web:53276b6703f8dfc842ddad",
        };

        // VAPID Key from Firebase Console
        const VAPID_KEY = "BOwt2zTQ2vDTYlfG7dL9RxNPNKFIgeTWMfPRxwelU0b-6LN6S1F8xAiw0dde-8YKG696R7P24cQIxfsjjmYxnms";

        // 3. Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        // 4. Fungsi untuk Request Permission dan Get Token (dipanggil saat tombol diklik)
        window = async function() {
            const button = document.getElementById('enable-notif-btn');
            const statusText = document.getElementById('notif-status');
            
            try {
                // Request permission dari browser
                const permission = await Notification.requestPermission();
                
                if (permission === 'granted') {
                    console.log('✅ Notifikasi diizinkan');
                    
                    // Register Service Worker
                    const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                    console.log('✅ Service Worker registered:', registration);
                    
                    // Ambil FCM Token
                    const token = await getToken(messaging, { 
                        vapidKey: VAPID_KEY,
                        serviceWorkerRegistration: registration
                    });
                    
                    if (token) {
                        console.log('🔥 FCM Token:', token);
                        
                        // Kirim token ke Laravel backend
                        const response = await fetch('/save-fcm-token', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({ fcm_token: token })
                        });
                        
                        const data = await response.json();
                        console.log('✅ Token disimpan:', data);
                        
                        // Update UI
                        if (button) button.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg> Notifikasi Aktif';
                        if (button) button.disabled = true;
                        if (button) button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                        if (button) button.classList.add('bg-green-600', 'cursor-not-allowed');
                        if (statusText) statusText.textContent = '✅ Notifikasi sudah aktif';
                        if (statusText) statusText.classList.remove('text-gray-500');
                        if (statusText) statusText.classList.add('text-green-600');
                        
                    } else {
                        throw new Error('Token tidak tersedia');
                    }
                    
                } else if (permission === 'denied') {
                    console.log('❌ Notifikasi ditolak oleh user');
                    if (statusText) statusText.textContent = '❌ Notifikasi ditolak. Aktifkan di pengaturan browser.';
                    if (statusText) statusText.classList.add('text-red-600');
                } else {
                    console.log('⚠️ Notifikasi default (belum ditentukan)');
                }
                
            } catch (error) {
                console.error('❌ Error saat enable notifikasi:', error);
                if (statusText) statusText.textContent = '❌ Gagal mengaktifkan notifikasi: ' + error.message;
                if (statusText) statusText.classList.add('text-red-600');
            }
        };

        // 5. Terima notifikasi saat website/tab sedang aktif (foreground)
        onMessage(messaging, (payload) => {
            console.log('📩 Notifikasi masuk (foreground):', payload);
            
            // Tampilkan notifikasi browser
            const notificationTitle = payload.notification?.title || 'Notifikasi Baru';
            const notificationOptions = {
                body: payload.notification?.body || '',
                icon: payload.notification?.icon || '/favicon.ico',
                badge: '/favicon.ico',
                tag: 'notification-' + Date.now(),
                requireInteraction: true
            };
            
            new Notification(notificationTitle, notificationOptions);
        });

        // 6. Check current permission status saat halaman dimuat
        window.addEventListener('DOMContentLoaded', () => {
            const statusText = document.getElementById('notif-status');
            const button = document.getElementById('enable-notif-btn');
            
            if ('Notification' in window) {
                if (Notification.permission === 'granted') {
                    if (statusText) statusText.textContent = '✅ Notifikasi sudah aktif';
                    if (statusText) statusText.classList.add('text-green-600');
                    if (button) button.disabled = true;
                    if (button) button.innerHTML = '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"/></svg> Notifikasi Aktif';
                    if (button) button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                    if (button) button.classList.add('bg-green-600', 'cursor-not-allowed');
                } else if (Notification.permission === 'denied') {
                    if (statusText) statusText.textContent = '❌ Notifikasi ditolak. Aktifkan di pengaturan browser.';
                    if (statusText) statusText.classList.add('text-red-600');
                    if (button) button.disabled = true;
                }
            } else {
                if (statusText) statusText.textContent = '❌ Browser tidak mendukung notifikasi';
                if (statusText) statusText.classList.add('text-red-600');
                if (button) button.disabled = true;
            }
        });
    </script>

<div class="w-full">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 rounded-2xl shadow-2xl overflow-hidden mb-6 relative">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-10 overflow-hidden">
            <svg class="absolute top-0 right-0 transform translate-x-1/2 -translate-y-1/2" width="400" height="400">
                <circle cx="200" cy="200" r="150" fill="white" opacity="0.1"/>
            </svg>
            <svg class="absolute bottom-0 left-0 transform -translate-x-1/2 translate-y-1/2" width="300" height="300">
                <circle cx="150" cy="150" r="100" fill="white" opacity="0.1"/>
            </svg>
        </div>
        
        <div class="relative p-6 md:p-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 md:gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3 md:mb-4">
                        <div class="w-12 h-12 md:w-16 md:h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-white text-2xl md:text-3xl shadow-lg">
                            🏪
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-1">
                                Dashboard Penjual
                            </h1>
                            <p class="text-purple-200 text-sm md:text-base">Selamat datang, {{ Auth::user()->name }}!</p>
                        </div>
                    </div>
                    <p class="text-purple-100 text-sm md:text-base lg:text-lg">
                        Kelola toko dan produk UMKM Anda dengan mudah. Pantau penjualan dan kembangkan bisnis Anda.
                    </p>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-2 md:gap-3 w-full sm:w-auto">
                    <a href="{{ url('/produk/create') }}" class="inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-white text-purple-700 rounded-xl hover:bg-purple-50 transition-all duration-200 font-bold shadow-lg hover:shadow-xl text-sm md:text-base">
                        <span>➕</span>
                        <span>Tambah Produk</span>
                    </a>
                    <a href="{{ url('/produk') }}" class="inline-flex items-center justify-center gap-2 px-4 md:px-6 py-2 md:py-3 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all duration-200 font-bold text-sm md:text-base">
                        <span>📦</span>
                        <span>Lihat Produk</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

@if (session('success'))
    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-xl">✅</span>
            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
        </div>
    </div>
@endif

@if (session('info'))
    <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-xl">ℹ️</span>
            <p class="text-sm font-medium text-blue-700">{{ session('info') }}</p>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-xl">❌</span>
            <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
        </div>
    </div>
@endif

@if(Auth::user()->status_approval === 'pending')
            <div class="mb-6 bg-yellow-50 border-2 border-yellow-400 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="text-5xl">⏳</div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-yellow-800 mb-2">Akun Menunggu Persetujuan Admin</h3>
                        <p class="text-yellow-700 mb-3">
                            Terima kasih telah mendaftar sebagai penjual di MyBelanjaMu! Akun Anda saat ini sedang dalam proses verifikasi oleh tim admin kami.
                        </p>
                        <div class="bg-yellow-100 border border-yellow-300 rounded-xl p-4">
                            <p class="text-sm text-yellow-800 font-semibold mb-2">⚠️ Fitur yang Terbatas:</p>
                            <ul class="text-sm text-yellow-700 space-y-1 ml-4">
                                <li>• Anda belum dapat menambahkan produk</li>
                                <li>• Anda belum dapat mengelola toko</li>
                                <li>• Anda dapat melihat dashboard dan informasi akun</li>
                            </ul>
                        </div>
                        <p class="text-sm text-yellow-600 mt-3">
                            Proses verifikasi biasanya memakan waktu 1-2 hari kerja. Anda akan menerima notifikasi melalui email setelah akun disetujui.
                        </p>
                    </div>
                </div>
            </div>
        @elseif(Auth::user()->status_approval === 'rejected')
            <div class="mb-6 bg-red-50 border-2 border-red-400 rounded-2xl p-6 shadow-lg">
                <div class="flex items-start gap-4">
                    <div class="text-5xl">❌</div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-red-800 mb-2">Akun Tidak Disetujui</h3>
                        <p class="text-red-700 mb-3">
                            Mohon maaf, pendaftaran Anda sebagai penjual tidak dapat disetujui. Silakan hubungi admin untuk informasi lebih lanjut.
                        </p>
                    </div>
                </div>
            </div>
        @endif

<!-- Stats Cards Section -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-1">
        <div class="text-4xl md:text-5xl mb-3">📦</div>
        <h3 class="text-2xl md:text-3xl font-bold mb-1">{{ \App\Models\Produk::where('user_id', Auth::id())->count() }}</h3>
        <p class="text-sm opacity-90">Total Produk</p>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-1">
        <div class="text-4xl md:text-5xl mb-3">💰</div>
        <h3 class="text-2xl md:text-3xl font-bold mb-1">Rp 0</h3>
        <p class="text-sm opacity-90">Total Penjualan</p>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-1">
        <div class="text-4xl md:text-5xl mb-3">⭐</div>
        <h3 class="text-2xl md:text-3xl font-bold mb-1">5.0</h3>
        <p class="text-sm opacity-90">Rating Toko</p>
    </div>
</div>

<!-- Menu Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">
            <!-- Kelola Produk -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-purple-500 hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-1">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-2xl">📦</span> Produk Saya
                </h3>
                <div class="space-y-3">
                    <a href="{{ url('/produk/create') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-purple-600 hover:bg-purple-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">➕</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Tambah Produk Baru</h4>
                                <p class="text-sm text-gray-600">Upload produk baru ke toko Anda</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-purple-600 hover:bg-purple-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📦</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Daftar Produk</h4>
                                <p class="text-sm text-gray-600">Lihat dan kelola semua produk Anda</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-purple-600 hover:bg-purple-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📊</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Stok Produk</h4>
                                <p class="text-sm text-gray-600">Kelola stok dan ketersediaan produk</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Penjualan via WhatsApp -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-green-500 hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-1">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-2xl">💬</span> Penjualan WhatsApp
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('penjual.pesanan.index') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-green-600 hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🛍️</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Pesanan Masuk</h4>
                                <p class="text-sm text-gray-600">Lihat pesanan yang masuk via WhatsApp</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-green-600 hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📱</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Nomor WhatsApp</h4>
                                <p class="text-sm text-gray-600">Atur nomor WA untuk setiap produk</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-green-600 hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🛒</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Lihat Toko</h4>
                                <p class="text-sm text-gray-600">Lihat tampilan produk di halaman utama</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.show') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-green-600 hover:bg-green-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">👤</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Profil Saya</h4>
                                <p class="text-sm text-gray-600">Edit informasi akun Anda</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Laporan & Keuangan -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border-t-4 border-blue-500 hover:shadow-2xl transition-all duration-200 transform hover:-translate-y-1">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="text-2xl">📊</span> Laporan & Analisis
                </h3>
                <div class="space-y-3">
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-blue-600 hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">💰</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Stok & Penjualan</h4>
                                <p class="text-sm text-gray-600">Monitor stok dan penjualan produk</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-blue-600 hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📈</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Produk Populer</h4>
                                <p class="text-sm text-gray-600">Lihat produk yang paling diminati</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="block p-4 border-2 border-gray-200 rounded-xl hover:border-blue-600 hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🏪</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Tampilan Toko</h4>
                                <p class="text-sm text-gray-600">Cek tampilan produk Anda</p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

<!-- Tips untuk Penjual -->
<div class="bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 rounded-2xl shadow-xl p-4 md:p-6 border-2 border-purple-200 mb-6">
    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <span>💡</span> Tips Sukses Berjualan
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl p-4 md:p-5 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 border-t-4 border-yellow-400">
            <div class="text-3xl md:text-4xl mb-3">📸</div>
            <h4 class="font-semibold text-gray-800 mb-2">Foto Produk Menarik</h4>
            <p class="text-sm text-gray-600">Gunakan foto berkualitas tinggi dan jelas untuk menarik pembeli</p>
        </div>
        <div class="bg-white rounded-xl p-4 md:p-5 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 border-t-4 border-blue-400">
            <div class="text-3xl md:text-4xl mb-3">💬</div>
            <h4 class="font-semibold text-gray-800 mb-2">Deskripsi Lengkap</h4>
            <p class="text-sm text-gray-600">Jelaskan detail produk dengan lengkap dan jujur</p>
        </div>
        <div class="bg-white rounded-xl p-4 md:p-5 shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-1 border-t-4 border-green-400">
            <div class="text-3xl md:text-4xl mb-3">⚡</div>
            <h4 class="font-semibold text-gray-800 mb-2">Respon Cepat</h4>
            <p class="text-sm text-gray-600">Balas pesan WhatsApp dari pembeli dengan cepat</p>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-2xl shadow-xl p-6 md:p-8">
    <h3 class="text-lg md:text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <span>📋</span> Aktivitas Terbaru
    </h3>
    <div class="text-center py-12 md:py-16 text-gray-400">
        <div class="text-5xl md:text-7xl mb-4">📊</div>
        <p class="text-lg md:text-xl mb-2 font-semibold">Belum ada aktivitas</p>
        <p class="text-sm">Mulai tambahkan produk untuk melihat aktivitas toko Anda</p>
    </div>
</div>
</div>
@endsection
