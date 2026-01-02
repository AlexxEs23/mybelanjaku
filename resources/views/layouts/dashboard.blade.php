<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    @include('components.sidebar')

    <main class="ml-64 p-6">
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

</body>
</html>
