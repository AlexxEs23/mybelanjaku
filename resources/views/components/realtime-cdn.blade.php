{{-- 
    Real-Time Setup via CDN (NO NPM NEEDED!)
    Tambahkan di <head> atau sebelum </body>
--}}

@auth
    {{-- User ID --}}
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Load Laravel Echo & Pusher via CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/pusher-js@8.4.0-rc2/dist/web/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>

    <script>
        // Initialize Echo dengan Reverb
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env('REVERB_APP_KEY') }}',
            wsHost: '{{ env('REVERB_HOST', 'localhost') }}',
            wsPort: {{ env('REVERB_PORT', 8080) }},
            wssPort: {{ env('REVERB_PORT', 8080) }},
            forceTLS: {{ env('REVERB_SCHEME', 'http') === 'https' ? 'true' : 'false' }},
            enabledTransports: ['ws', 'wss'],
        });

        const userId = {{ auth()->id() }};

        // 🔔 Subscribe ke Notifications
        Echo.private(`user.${userId}`)
            .listen('.notification.sent', (notification) => {
                console.log('✅ Notification received:', notification);
                
                // Show toast notification
                showToastNotification(notification);
                
                // Update badge count
                updateNotificationBadge();
                
                // Trigger custom event
                window.dispatchEvent(new CustomEvent('notification-received', { 
                    detail: notification 
                }));
            });

        console.log('✅ Real-time notifications active for user:', userId);

        // Show toast function
        function showToastNotification(notification) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 max-w-sm z-50 animate-slide-in';
            toast.style.animation = 'slideIn 0.3s ease-out';
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
                toast.style.transition = 'opacity 0.3s';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Update badge count
        function updateNotificationBadge() {
            const badge = document.querySelector('[data-notification-badge]');
            if (badge) {
                const currentCount = parseInt(badge.textContent || '0');
                badge.textContent = currentCount + 1;
                badge.classList.remove('hidden');
            }
        }
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>
@endauth
