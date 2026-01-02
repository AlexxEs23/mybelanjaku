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
<!-- Hero Header -->
<div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 rounded-2xl shadow-2xl p-8 mb-8 text-white relative overflow-hidden">
    <div class="absolute top-0 right-0 opacity-10">
        <svg class="w-64 h-64" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
        </svg>
    </div>
    <div class="relative z-10">
        <div class="flex items-center gap-3 mb-4">
            <span class="text-5xl">👑</span>
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-2">Dashboard Administrator</h1>
                <p class="text-purple-100">Selamat datang kembali, {{ Auth::user()->name }}!</p>
            </div>
        </div>
        <div class="mt-6 flex flex-wrap gap-4">
            <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                <p class="text-sm text-purple-100">Waktu Akses</p>
                <p class="font-semibold">{{ now()->format('d M Y, H:i') }}</p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                <p class="text-sm text-purple-100">Status Sistem</p>
                <p class="font-semibold flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    Online
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Stats Cards --}}
<section class="mb-8" aria-label="Statistik Ringkasan">
    <div class="grid md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex justify-between items-start mb-4">
                <div class="text-4xl">👥</div>
                <span class="bg-white/20 px-2 py-1 rounded text-xs">Total</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ \App\Models\User::count() }}</h3>
            <p class="text-sm opacity-90">Total Pengguna</p>
        </div>
        
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex justify-between items-start mb-4">
                <div class="text-4xl">🏪</div>
                <span class="bg-white/20 px-2 py-1 rounded text-xs">Aktif</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ \App\Models\User::where('role', 'penjual')->count() }}</h3>
            <p class="text-sm opacity-90">Total Penjual</p>
        </div>
        
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex justify-between items-start mb-4">
                <div class="text-4xl">📦</div>
                <span class="bg-white/20 px-2 py-1 rounded text-xs">Items</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ \App\Models\Produk::count() }}</h3>
            <p class="text-sm opacity-90">Total Produk</p>
        </div>
        
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white">
            <div class="flex justify-between items-start mb-4">
                <div class="text-4xl">🛍️</div>
                <span class="bg-white/20 px-2 py-1 rounded text-xs">Orders</span>
            </div>
            <h3 class="text-3xl font-bold mb-1">{{ \App\Models\Pesanan::count() }}</h3>
            <p class="text-sm opacity-90">Total Pesanan</p>
        </div>
    </div>
</section>

        {{-- Menu Grid --}}
        <section class="mb-8" aria-label="Menu Navigasi Admin">
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Kelola User --}}
                <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">👥</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Kelola Pengguna</h3>
                    <p class="text-gray-600 mb-4 text-sm">Manage semua user, penjual dan admin</p>
                    <a href="{{ route('admin.users.index') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-semibold">
                        Lihat Pengguna
                    </a>
                </article>

                {{-- Kelola Produk --}}
                <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">📦</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Kelola Produk</h3>
                    <p class="text-gray-600 mb-4 text-sm">Monitor dan moderasi semua produk</p>
                    <a href="{{ url ('/produk') }}" class="inline-block px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-semibold">
                        Lihat Produk
                    </a>
                </article>

                {{-- Kelola Pesanan --}}
                <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">🛍️</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Data Pesanan</h3>
                    <p class="text-gray-600 mb-4 text-sm">Monitor semua pesanan via WhatsApp</p>
                    <a href="{{ route('admin.pesanan.index') }}" class="inline-block px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm font-semibold">
                        Lihat Pesanan
                    </a>
                </article>

                {{-- Kelola Kategori --}}
                <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">🏷️</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Kelola Kategori</h3>
                    <p class="text-gray-600 mb-4 text-sm">Tambah dan edit kategori produk</p>
                    <a href="#" class="inline-block px-6 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition text-sm font-semibold">
                        Lihat Kategori
                    </a>
                </article>

                {{-- Laporan --}}
                <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">📊</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Laporan & Statistik</h3>
                    <p class="text-gray-600 mb-4 text-sm">Lihat laporan penjualan dan statistik</p>
                    <a href="#" class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition text-sm font-semibold">
                        Lihat Laporan
                    </a>
                </article>

                {{-- Pengaturan --}}
                <article class="bg-white rounded-xl shadow-lg p-6 hover:shadow-2xl transition">
                    <div class="text-5xl mb-4">⚙️</div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Pengaturan Sistem</h3>
                    <p class="text-gray-600 mb-4 text-sm">Konfigurasi dan setting platform</p>
                    <a href="#" class="inline-block px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition text-sm font-semibold">
                        Pengaturan
                    </a>
                </article>
            </div>
        </section>

{{-- Recent Activity --}}
<section class="bg-white rounded-xl shadow-lg p-8" aria-label="Aktivitas Terbaru">
    <div class="flex items-center gap-3 mb-6">
        <span class="text-3xl">📊</span>
        <h2 class="text-2xl font-bold text-gray-800">Aktivitas Terbaru</h2>
    </div>
    <div class="text-center py-12">
        <div class="text-6xl mb-4">📈</div>
        <p class="text-gray-500 text-lg">Belum ada aktivitas terbaru</p>
        <p class="text-gray-400 text-sm mt-2">Log aktivitas akan muncul di sini</p>
    </div>
</section>
@endsection
