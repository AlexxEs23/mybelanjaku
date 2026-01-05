{{-- 
    Real-Time Chat via CDN (NO NPM NEEDED!)
    Tambahkan di halaman chat Anda
--}}

@auth
    <script>
        const chatId = {{ $chatId ?? 0 }};
        
        if (chatId > 0 && window.Echo) {
            // Subscribe ke Chat Room
            Echo.private(`chat.${chatId}`)
                .listen('.message.sent', (message) => {
                    console.log('💬 Message received:', message);
                    
                    // Append message to chat
                    appendMessageToChat(message);
                    
                    // Scroll to bottom
                    scrollChatToBottom();
                });

            console.log('✅ Listening to chat:', chatId);
        }

        // Append message to chat UI
        function appendMessageToChat(message) {
            const chatContainer = document.querySelector('[data-chat-messages]');
            if (!chatContainer) return;

            const currentUserId = parseInt(chatContainer.dataset.currentUser || '0');
            const isOwnMessage = message.pengirim_id === currentUserId;

            const messageElement = document.createElement('div');
            messageElement.className = `flex ${isOwnMessage ? 'justify-end' : 'justify-start'} mb-4`;
            messageElement.style.animation = 'fadeIn 0.3s ease-out';
            messageElement.innerHTML = `
                <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
                    isOwnMessage 
                        ? 'bg-blue-500 text-white' 
                        : 'bg-gray-200 text-gray-900'
                }">
                    ${!isOwnMessage ? `<p class="text-xs font-semibold mb-1">${escapeHtml(message.pengirim?.name || 'User')}</p>` : ''}
                    <p class="text-sm">${escapeHtml(message.pesan)}</p>
                    <p class="text-xs mt-1 ${isOwnMessage ? 'text-blue-100' : 'text-gray-500'}">
                        ${formatTime(message.created_at)}
                    </p>
                </div>
            `;

            chatContainer.appendChild(messageElement);
        }

        // Scroll chat to bottom
        function scrollChatToBottom() {
            const chatContainer = document.querySelector('[data-chat-messages]');
            if (chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        }

        // Escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Format time
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            return date.toLocaleTimeString('id-ID', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        }
    </script>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endauth
