<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penjual - UMKM Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
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
        window.enableNotifications = async function() {
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
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
<body class="bg-gray-100 min-h-screen">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="ml-64 p-8">
        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-green-500 mr-2 text-xl">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-blue-500 mr-2 text-xl">ℹ</span>
                    <p class="text-sm text-blue-700">{{ session('info') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-red-500 mr-2 text-xl">✕</span>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        @if(Auth::user()->status_approval === 'pending')
            <div class="mb-6 bg-yellow-50 border-2 border-yellow-400 rounded-xl p-6 shadow-lg">
                <div class="flex items-start">
                    <div class="text-5xl mr-4">⏳</div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-yellow-800 mb-2">Akun Menunggu Persetujuan Admin</h3>
                        <p class="text-yellow-700 mb-3">
                            Terima kasih telah mendaftar sebagai penjual di UMKM Market! Akun Anda saat ini sedang dalam proses verifikasi oleh tim admin kami.
                        </p>
                        <div class="bg-yellow-100 border border-yellow-300 rounded-lg p-4">
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
            <div class="mb-6 bg-red-50 border-2 border-red-400 rounded-xl p-6 shadow-lg">
                <div class="flex items-start">
                    <div class="text-5xl mr-4">❌</div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-red-800 mb-2">Akun Tidak Disetujui</h3>
                        <p class="text-red-700 mb-3">
                            Mohon maaf, pendaftaran Anda sebagai penjual tidak dapat disetujui. Silakan hubungi admin untuk informasi lebih lanjut.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang, {{ Auth::user()->name }}! 🎉</h2>
            <p class="text-gray-600 mb-6">Kelola toko dan produk UMKM Anda dengan mudah dari sini.</p>
            
            <!-- Stats Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                    <div class="text-4xl mb-3">📦</div>
                    <h3 class="text-2xl font-bold mb-1">{{ \App\Models\Produk::where('user_id', Auth::id())->count() }}</h3>
                    <p class="text-sm opacity-90">Total Produk</p>
                </div>
                
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
                    <div class="text-4xl mb-3">💰</div>
                    <h3 class="text-2xl font-bold mb-1">Rp 0</h3>
                    <p class="text-sm opacity-90">Total Penjualan</p>
                </div>
                
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white">
                    <div class="text-4xl mb-3">⭐</div>
                    <h3 class="text-2xl font-bold mb-1">5.0</h3>
                    <p class="text-sm opacity-90">Rating Toko</p>
                </div>
            </div>
        </div>

        <!-- Menu Grid -->
        <div class="grid md:grid-cols-3 gap-6">
            <!-- Kelola Produk -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📦 Produk Saya</h3>
                <div class="space-y-3">
                    <a href="{{ url('/produk/create') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">➕</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Tambah Produk Baru</h4>
                                <p class="text-sm text-gray-600">Upload produk baru ke toko Anda</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📦</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Daftar Produk</h4>
                                <p class="text-sm text-gray-600">Lihat dan kelola semua produk Anda</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
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
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">💬 Penjualan WhatsApp</h3>
                <div class="space-y-3">
                    <a href="{{ route('penjual.pesanan.index') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🛍️</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Pesanan Masuk</h4>
                                <p class="text-sm text-gray-600">Lihat pesanan yang masuk via WhatsApp</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📱</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Nomor WhatsApp</h4>
                                <p class="text-sm text-gray-600">Atur nomor WA untuk setiap produk</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">🛒</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Lihat Toko</h4>
                                <p class="text-sm text-gray-600">Lihat tampilan produk di halaman utama</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('profile.show') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-green-600 hover:bg-green-50 transition">
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
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-4">📊 Laporan & Analisis</h3>
                <div class="space-y-3">
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">💰</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Stok & Penjualan</h4>
                                <p class="text-sm text-gray-600">Monitor stok dan penjualan produk</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ url('/produk') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
                        <div class="flex items-center gap-3">
                            <span class="text-2xl">📈</span>
                            <div>
                                <h4 class="font-semibold text-gray-800">Produk Populer</h4>
                                <p class="text-sm text-gray-600">Lihat produk yang paling diminati</p>
                            </div>
                        </div>
                    </a>
                    <a href="{{ route('home') }}" class="block p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
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

        <!-- Tips untuk Penjual -->
        <div class="mt-6 bg-gradient-to-r from-purple-50 to-blue-50 rounded-xl shadow-lg p-6 border-2 border-purple-200">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                <span>💡</span> Tips Sukses Berjualan
            </h3>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg p-4">
                    <div class="text-3xl mb-2">📸</div>
                    <h4 class="font-semibold text-gray-800 mb-1">Foto Produk Menarik</h4>
                    <p class="text-sm text-gray-600">Gunakan foto berkualitas tinggi dan jelas untuk menarik pembeli</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <div class="text-3xl mb-2">💬</div>
                    <h4 class="font-semibold text-gray-800 mb-1">Deskripsi Lengkap</h4>
                    <p class="text-sm text-gray-600">Jelaskan detail produk dengan lengkap dan jujur</p>
                </div>
                <div class="bg-white rounded-lg p-4">
                    <div class="text-3xl mb-2">⚡</div>
                    <h4 class="font-semibold text-gray-800 mb-1">Respon Cepat</h4>
                    <p class="text-sm text-gray-600">Balas pesan WhatsApp dari pembeli dengan cepat</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4">📋 Aktivitas Terbaru</h3>
            <div class="text-center py-12 text-gray-400">
                <div class="text-6xl mb-4">📊</div>
                <p class="text-lg mb-2">Belum ada aktivitas</p>
                <p class="text-sm">Mulai tambahkan produk untuk melihat aktivitas toko Anda</p>
            </div>
        </div>
</html>
