<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    @endauth

    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Vite Assets untuk Laravel Echo & Real-time -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 h-full">

    <!-- Alert Notifications -->
    @if(session('success'))
    <div id="alert-success" class="fixed top-4 right-4 z-50 max-w-md bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 animate-slide-in">
        <span class="text-2xl">✅</span>
        <div class="flex-1">
            <p class="font-semibold">Berhasil!</p>
            <p class="text-sm">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div id="alert-error" class="fixed top-4 right-4 z-50 max-w-md bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 animate-slide-in">
        <span class="text-2xl">❌</span>
        <div class="flex-1">
            <p class="font-semibold">Gagal!</p>
            <p class="text-sm">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    @if(session('warning'))
    <div id="alert-warning" class="fixed top-4 right-4 z-50 max-w-md bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 animate-slide-in">
        <span class="text-2xl">⚠️</span>
        <div class="flex-1">
            <p class="font-semibold">Peringatan!</p>
            <p class="text-sm">{{ session('warning') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div id="alert-errors" class="fixed top-4 right-4 z-50 max-w-md bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl animate-slide-in">
        <div class="flex items-start gap-3">
            <span class="text-2xl">❌</span>
            <div class="flex-1">
                <p class="font-semibold mb-2">Terjadi Kesalahan:</p>
                <ul class="text-sm list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-gray-200">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <!-- Mobile Header dengan Menu Button -->
    <header class="lg:hidden fixed top-0 left-0 right-0 bg-white shadow-md z-40 px-4 py-3 flex items-center justify-between">
        <button id="mobile-menu-btn" class="text-purple-800 p-2 hover:bg-purple-50 rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-lg font-bold text-purple-800">CheckoutAja</h1>
        <div class="w-10"></div> <!-- Spacer for center alignment -->
    </header>

    <!-- Sidebar Overlay untuk Mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden"></div>

    @include('components.sidebar')

    <main class="lg:ml-64 w-full lg:w-auto min-h-screen bg-gray-50 pt-20 lg:pt-6 px-4 md:px-6 pb-6 overflow-x-hidden">
        @yield('content')
    </main>

    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCMKwa144N9ve0JnxmNv4wGKSrIB8zkA2A",
            authDomain: "ecommerceumkm-4dbc3.firebaseapp.com",
            projectId: "ecommerceumkm-4dbc3",
            messagingSenderId: "638039749336",
            appId: "1:638039749336:web:53276b6703f8dfc842ddad"
        };

        const VAPID_KEY = "BOwt2zTQ2vDTYlfG7dL9RxNPNKFIgeTWMfPRxwelU0b-6LN6S1F8xAiw0dde-8YKG696R7P24cQIxfsjjmYxnms";

        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

        // FUNGSI INTERNAL DI MODULE
        async function enableNotifications() {
            try {
                console.log('🔔 Meminta izin notifikasi...');
                
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    alert('❌ Notifikasi ditolak');
                    return;
                }

                console.log('✅ Izin diberikan, registrasi service worker...');
                const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
                
                console.log('⏳ Menunggu service worker siap...');
                await navigator.serviceWorker.ready;

                console.log('🔑 Mendapatkan FCM token...');
                const token = await getToken(messaging, {
                    vapidKey: VAPID_KEY,
                    serviceWorkerRegistration: registration
                });

                if (!token) {
                    throw new Error('Token FCM kosong');
                }

                console.log('🔥 FCM Token:', token);

                await fetch('/save-fcm-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ fcm_token: token })
                });

                const statusEl = document.getElementById('notif-status');
                if (statusEl) {
                    statusEl.innerText = '✅ Notifikasi aktif';
                }

                alert('✅ Notifikasi berhasil diaktifkan!');

            } catch (e) {
                console.error('❌ Error:', e);
                alert('❌ Gagal mengaktifkan notifikasi: ' + e.message);
            }
        }

        // ATTACH EVENT LISTENER KE BUTTON
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('enable-notif-btn');
            if (btn) {
                btn.addEventListener('click', enableNotifications);
                console.log('✅ Event listener terpasang ke button');
            } else {
                console.error('❌ Button tidak ditemukan!');
            }
        });
    </script>

    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>

    <script>
        // Auto dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('[id^="alert-"]');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    alert.style.transition = 'all 0.3s ease-out';
                    setTimeout(() => alert.remove(), 300);
                }, 5000);
            });
        });
        
        // Sidebar Toggle untuk Mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const overlay = document.getElementById('sidebar-overlay');
            
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
            
            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', toggleSidebar);
            }
            
            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }
            
            // Close sidebar when clicking a link on mobile
            const sidebarLinks = sidebar.querySelectorAll('a');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        toggleSidebar();
                    }
                });
            });
        });

        // 🔥 REAL-TIME NOTIFICATION LISTENER
        document.addEventListener('DOMContentLoaded', function() {
            const userId = {{ Auth::id() }};
            const userRole = '{{ Auth::user()->role }}';

            if (window.Echo) {
                console.log('🔥 Listening to notifications for user:', userId);

                // Subscribe to user's private notification channel
                window.Echo.private(`user.${userId}`)
                    .listen('.notification.sent', (e) => {
                        console.log('🔔 New notification received:', e);

                        // Update badge
                        const badgeId = userRole === 'admin' ? 'notif-badge-admin' : 'notif-badge-penjual';
                        const badge = document.getElementById(badgeId);
                        
                        if (badge) {
                            // Fetch current count
                            fetch('{{ route("notifikasi.index") }}')
                                .then(response => response.text())
                                .then(html => {
                                    const parser = new DOMParser();
                                    const doc = parser.parseFromString(html, 'text/html');
                                    const unreadCount = doc.querySelectorAll('.bg-blue-50').length;
                                    
                                    if (unreadCount > 0) {
                                        badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                                        badge.classList.remove('hidden');
                                    }
                                });
                        }

                        // Show browser notification
                        if ('Notification' in window && Notification.permission === 'granted') {
                            new Notification(e.judul, {
                                body: e.pesan,
                                icon: '/favicon.ico',
                                tag: 'notification-' + e.id
                            });
                        }

                        // Optional: Show toast notification in UI
                        showToast(e.judul, e.pesan);
                    });
            }

            // Simple toast notification function
            function showToast(title, message) {
                const toast = document.createElement('div');
                toast.className = 'fixed top-24 right-4 bg-purple-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 max-w-sm animate-slide-in';
                toast.innerHTML = `
                    <div class="flex items-start gap-3">
                        <span class="text-2xl">🔔</span>
                        <div class="flex-1">
                            <p class="font-bold mb-1">${title}</p>
                            <p class="text-sm text-purple-100">${message}</p>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-white hover:text-purple-200">
                            ✕
                        </button>
                    </div>
                `;
                
                document.body.appendChild(toast);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }
        });
    </script>

    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
    </style>

</body>
</html>
