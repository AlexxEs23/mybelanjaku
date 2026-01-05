/**
 * Real-Time Notifications Handler
 * 
 * Automatically listens to notification events and displays them
 */

export function initializeRealtimeNotifications(userId) {
    if (!window.Echo) {
        console.error('Laravel Echo not initialized');
        return;
    }

    console.log(`🔔 Subscribing to notifications for user ${userId}`);

    // Subscribe to user's private notification channel
    window.Echo.private(`user.${userId}`)
        .listen('.notification.sent', (notification) => {
            console.log('✅ Notification received:', notification);
            
            // Show browser notification if permitted
            showBrowserNotification(notification);
            
            // Show in-app notification
            showInAppNotification(notification);
            
            // Update notification badge
            updateNotificationBadge();
            
            // Trigger custom event for other components
            window.dispatchEvent(new CustomEvent('notification-received', { 
                detail: notification 
            }));
        })
        .error((error) => {
            console.error('❌ Error subscribing to notifications:', error);
        });
}

/**
 * Show browser notification
 */
function showBrowserNotification(notification) {
    if ('Notification' in window && Notification.permission === 'granted') {
        new Notification(notification.judul, {
            body: notification.pesan,
            icon: '/images/logo.png', // Ganti dengan logo Anda
            badge: '/images/badge.png',
            tag: `notification-${notification.id}`,
        });
    }
}

/**
 * Show in-app notification (toast/alert)
 */
function showInAppNotification(notification) {
    // Menggunakan Toastr atau alert biasa
    if (typeof toastr !== 'undefined') {
        toastr.info(notification.pesan, notification.judul);
    } else {
        // Fallback: buat toast sederhana
        createToast(notification);
    }
}

/**
 * Create simple toast notification
 */
function createToast(notification) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 max-w-sm z-50 animate-slide-in';
    toast.innerHTML = `
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
            </div>
            <div class="ml-3 w-0 flex-1">
                <p class="text-sm font-medium text-gray-900">${notification.judul}</p>
                <p class="mt-1 text-sm text-gray-500">${notification.pesan}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 flex-shrink-0">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

/**
 * Update notification badge count
 */
function updateNotificationBadge() {
    const badge = document.querySelector('[data-notification-badge]');
    if (badge) {
        const currentCount = parseInt(badge.textContent || '0');
        badge.textContent = currentCount + 1;
        badge.classList.remove('hidden');
    }
}

/**
 * Request browser notification permission
 */
export function requestNotificationPermission() {
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission().then(permission => {
            console.log('Notification permission:', permission);
        });
    }
}
