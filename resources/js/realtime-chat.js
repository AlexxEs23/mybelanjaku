/**
 * Real-Time Chat Handler
 * 
 * Handles real-time chat messages
 */

export function initializeRealtimeChat(chatId) {
    if (!window.Echo) {
        console.error('Laravel Echo not initialized');
        return;
    }

    console.log(`💬 Subscribing to chat ${chatId}`);

    // Subscribe to chat's private channel
    window.Echo.private(`chat.${chatId}`)
        .listen('.message.sent', (message) => {
            console.log('✅ Message received:', message);
            
            // Append message to chat
            appendMessageToChat(message);
            
            // Play notification sound
            playNotificationSound();
            
            // Scroll to bottom
            scrollChatToBottom();
            
            // Trigger custom event
            window.dispatchEvent(new CustomEvent('message-received', { 
                detail: message 
            }));
        })
        .error((error) => {
            console.error('❌ Error subscribing to chat:', error);
        });
}

/**
 * Append message to chat UI
 */
function appendMessageToChat(message) {
    const chatContainer = document.querySelector('[data-chat-messages]');
    if (!chatContainer) return;

    const currentUserId = parseInt(chatContainer.dataset.currentUser || '0');
    const isOwnMessage = message.pengirim_id === currentUserId;

    const messageElement = document.createElement('div');
    messageElement.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'} mb-4 animate-fade-in`;
    messageElement.innerHTML = `
        <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
            isOwnMessage 
                ? 'bg-blue-500 text-white' 
                : 'bg-gray-200 text-gray-900'
        }">
            ${!isOwnMessage ? `<p class="text-xs font-semibold mb-1">${message.pengirim?.name || 'User'}</p>` : ''}
            <p class="text-sm">${escapeHtml(message.pesan)}</p>
            <p class="text-xs mt-1 ${isOwnMessage ? 'text-blue-100' : 'text-gray-500'}">
                ${formatTime(message.created_at)}
            </p>
        </div>
    `;

    chatContainer.appendChild(messageElement);
}

/**
 * Scroll chat to bottom
 */
function scrollChatToBottom() {
    const chatContainer = document.querySelector('[data-chat-messages]');
    if (chatContainer) {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    }
}

/**
 * Play notification sound
 */
function playNotificationSound() {
    const audio = new Audio('/sounds/notification.mp3');
    audio.volume = 0.3;
    audio.play().catch(() => {
        // Ignore errors if audio can't play
    });
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Format timestamp
 */
function formatTime(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit' 
    });
}

/**
 * Leave chat channel
 */
export function leaveChat(chatId) {
    if (window.Echo) {
        window.Echo.leave(`chat.${chatId}`);
        console.log(`👋 Left chat ${chatId}`);
    }
}
