<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- WAJIB -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

    @include('components.sidebar')

    <main class="ml-64 p-6">
        @yield('content')
    </main>

    <!-- 🔥 FIREBASE FCM (SATU-SATUNYA SCRIPT) -->
    <script type="module">
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

        // =============================
        // CONFIG
        // =============================
        const firebaseConfig = {
            apiKey: "AIzaSyCMKwa144N9ve0JnxmNv4wGKSrIB8zkA2A",
            authDomain: "ecommerceumkm-4dbc3.firebaseapp.com",
            projectId: "ecommerceumkm-4dbc3",
            messagingSenderId: "638039749336",
            appId: "1:638039749336:web:53276b6703f8dfc842ddad",
        };

        const VAPID_KEY = "BOwt2zTQ2vDTYlfG7dL9RxNPNKFIgeTWMfPRxwelU0b-6LN6S1F8xAiw0dde-8YKG696R7P24cQIxfsjjmYxnms";

        // =============================
        // INIT
        // =============================
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);

       window.enableNotifications = async () => {
    try {
        const permission = await Notification.requestPermission();
        if (permission !== 'granted') {
            alert('❌ Notifikasi ditolak');
            return;
        }

        // Register Service Worker
        const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');

        // ⛔ PENTING: jika SW belum active, TUNGGU controllerchange
        if (!navigator.serviceWorker.controller) {
            console.log('⏳ SW belum active, reload 1x...');
            alert('🔄 Sistem akan reload 1x untuk aktivasi notifikasi');
            window.location.reload();
            return;
        }

        // Tunggu benar-benar ready
        const readyRegistration = await navigator.serviceWorker.ready;

        const token = await getToken(messaging, {
            vapidKey: VAPID_KEY,
            serviceWorkerRegistration: readyRegistration
        });

        if (!token) {
            alert('❌ Token tidak didapat');
            return;
        }

        console.log('🔥 FCM TOKEN:', token);

        await fetch('/save-fcm-token', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute('content')
            },
            body: JSON.stringify({ fcm_token: token })
        });

        alert('✅ Notifikasi berhasil diaktifkan');

    } catch (error) {
        console.error(error);
        alert('❌ Gagal mengaktifkan notifikasi');
    }
};

            try {
                // 1️⃣ Request permission
                const permission = await Notification.requestPermission();
                if (permission !== 'granted') {
                    alert('❌ Notifikasi ditolak');
                    return;
                }

                // 2️⃣ Register SW
                const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');

                // 3️⃣ TUNGGU SAMPAI BENAR-BENAR ACTIVE
                const readyRegistration = await navigator.serviceWorker.ready;

                if (!readyRegistration.active) {
                    alert('❌ Service Worker belum aktif');
                    return;
                }

                console.log('✅ SW ACTIVE:', readyRegistration.active);

                // 4️⃣ Ambil token
                const token = await getToken(messaging, {
                    vapidKey: VAPID_KEY,
                    serviceWorkerRegistration: readyRegistration
                });

                if (!token) {
                    alert('❌ Token FCM tidak didapat');
                    return;
                }

                console.log('🔥 FCM TOKEN:', token);

                // 5️⃣ Kirim ke backend
                await fetch('/save-fcm-token', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ fcm_token: token })
                });

                alert('✅ Notifikasi berhasil diaktifkan');

            } catch (error) {
                console.error(error);
                alert('❌ Gagal mengaktifkan notifikasi');
            }
        };
    </script>

</body>
</html>
