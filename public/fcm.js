import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";

console.log('🔥 FCM SCRIPT LOADED');

const firebaseConfig = {
    apiKey: "AIzaSyCMKwa144N9ve0JnxmNv4wGKSrIB8zkA2A",
    authDomain: "ecommerceumkm-4dbc3.firebaseapp.com",
    projectId: "ecommerceumkm-4dbc3",
    storageBucket: "ecommerceumkm-4dbc3.appspot.com",
    messagingSenderId: "638039749336",
    appId: "1:638039749336:web:53276b6703f8dfc842ddad",
};

const VAPID_KEY = "BOwt2zTQ2vDTYlfG7dL9RxNPNKFIgeTWMfPRxwelU0b-6LN6S1F8xAiw0dde-8YKG696R7P24cQIxfsjjmYxnms";

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Register Service Worker
const registration = await navigator.serviceWorker.register('/firebase-messaging-sw.js');
await navigator.serviceWorker.ready;

window.enableNotifications = async () => {
    console.log('🔔 Enable notification clicked');

    const permission = await Notification.requestPermission();
    if (permission !== 'granted') {
        alert('Notifikasi ditolak');
        return;
    }

    const token = await getToken(messaging, {
        vapidKey: VAPID_KEY,
        serviceWorkerRegistration: registration
    });

    console.log('🔥 FCM TOKEN:', token);

    await fetch('/save-fcm-token', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ fcm_token: token })
    });

    document.getElementById('notif-status').innerText = '✅ Notifikasi aktif';
};

onMessage(messaging, payload => {
    console.log('📩 Foreground message', payload);
    new Notification(payload.notification.title, {
        body: payload.notification.body,
        icon: '/favicon.ico'
    });
});
