<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 overflow-x-hidden">

    <!-- Mobile Header dengan Menu Button -->
    <header class="lg:hidden fixed top-0 left-0 right-0 bg-white shadow-md z-20 px-4 py-3 flex items-center justify-between">
        <button id="mobile-menu-btn" class="text-purple-800 p-2 hover:bg-purple-50 rounded-lg transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <h1 class="text-lg font-bold text-purple-800">UMKM Market</h1>
        <div class="w-10"></div> <!-- Spacer for center alignment -->
    </header>

    @include('components.sidebar')

    <main class="lg:ml-64 p-4 md:p-6 pt-20 lg:pt-6 min-h-screen overflow-x-hidden">
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

    <script>
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
    </script>

</body>
</html>
