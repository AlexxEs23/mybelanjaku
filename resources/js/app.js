import './bootstrap';
import { initializeRealtimeNotifications, requestNotificationPermission } from './realtime-notifications';
import { initializeRealtimeChat } from './realtime-chat';

// Export to window for easy access from Blade templates
window.initializeRealtimeNotifications = initializeRealtimeNotifications;
window.initializeRealtimeChat = initializeRealtimeChat;
window.requestNotificationPermission = requestNotificationPermission;

// Auto-initialize if user is logged in
document.addEventListener('DOMContentLoaded', () => {
    const userId = document.querySelector('meta[name="user-id"]')?.content;
    
    if (userId) {
        // Initialize notifications
        initializeRealtimeNotifications(userId);
        
        // Request notification permission
        requestNotificationPermission();
        
        console.log('✅ Real-time features initialized for user:', userId);
    }
});
