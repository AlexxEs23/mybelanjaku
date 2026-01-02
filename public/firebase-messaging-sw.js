importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCMKwa144N9ve0JnxmNv4wGKSrIB8zkA2A",
    authDomain: "ecommerceumkm-4dbc3.firebaseapp.com",
    projectId: "ecommerceumkm-4dbc3",
    messagingSenderId: "638039749336",
    appId: "1:638039749336:web:53276b6703f8dfc842ddad"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(function(payload) {
    console.log('[SW] Background message received:', payload);
    
    const title = payload.notification?.title || 'Notifikasi Baru';
    const options = {
        body: payload.notification?.body || '',
        icon: '/favicon.ico',
        badge: '/favicon.ico',
        data: { url: payload.data?.url || '/' }
    };

    return self.registration.showNotification(title, options);
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    event.waitUntil(
        clients.openWindow(event.notification.data.url || '/')
    );
});
