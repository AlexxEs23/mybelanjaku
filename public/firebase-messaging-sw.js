importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyCMKwa144N9ve0JnxmNv4wGKSrIB8zkA2A",
    authDomain: "ecommerceumkm-4dbc3.firebaseapp.com",
    projectId: "ecommerceumkm-4dbc3",
    messagingSenderId: "638039749336",
    appId: "1:638039749336:web:53276b6703f8dfc842ddad",
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage(() => {});
