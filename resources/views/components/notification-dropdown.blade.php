<!-- Notification Dropdown Component -->
<div x-data="notificationDropdown()" x-init="init()" class="relative">
    <!-- Notification Bell Button -->
    <button 
        @click="toggleDropdown()" 
        class="relative p-2 text-gray-600 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-200"
        :class="{ 'bg-purple-50 text-purple-600': isOpen }"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        
        <!-- Badge -->
        <span 
            x-show="unreadCount > 0" 
            x-text="unreadCount > 99 ? '99+' : unreadCount"
            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1 shadow-lg animate-pulse"
        ></span>
    </button>

    <!-- Dropdown Panel -->
    <div 
        x-show="isOpen" 
        @click.away="isOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-96 max-w-[calc(100vw-2rem)] bg-white rounded-xl shadow-2xl border border-gray-200 z-50"
        style="display: none;"
    >
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-purple-600 to-purple-700 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-white flex items-center gap-2">
                    <span>🔔</span>
                    <span>Notifikasi</span>
                    <span x-show="unreadCount > 0" class="bg-white/20 px-2 py-0.5 rounded-full text-sm" x-text="unreadCount"></span>
                </h3>
                <button 
                    @click="markAllAsRead()" 
                    x-show="unreadCount > 0"
                    class="text-xs text-white hover:underline"
                >
                    Tandai Semua Dibaca
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div x-show="loading" class="p-8 text-center">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-4 border-purple-600 border-t-transparent"></div>
            <p class="mt-2 text-sm text-gray-500">Memuat notifikasi...</p>
        </div>

        <!-- Notifications List -->
        <div x-show="!loading" class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="p-8 text-center">
                    <div class="text-5xl mb-2">🔕</div>
                    <p class="text-gray-500 text-sm">Tidak ada notifikasi</p>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <div 
                    class="p-4 border-b border-gray-100 hover:bg-purple-50 transition-colors cursor-pointer"
                    :class="{ 'bg-yellow-50 border-l-4 border-l-orange-500': !notif.dibaca }"
                    @click="openNotification(notif)"
                >
                    <div class="flex gap-3">
                        <!-- Icon -->
                        <div class="flex-shrink-0">
                            <span x-show="!notif.dibaca" class="w-2 h-2 bg-orange-500 rounded-full block mt-2"></span>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-sm mb-1" x-text="notif.judul"></h4>
                            <p class="text-gray-600 text-xs mb-2 line-clamp-2" x-text="notif.pesan"></p>
                            <p class="text-gray-400 text-xs" x-text="formatTime(notif.created_at)"></p>
                        </div>

                        <!-- Delete Button -->
                        <button 
                            @click.stop="deleteNotification(notif.id)"
                            class="flex-shrink-0 text-gray-400 hover:text-red-600 transition"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            <a href="{{ route('notifikasi.index') }}" class="block text-center text-sm text-purple-600 hover:text-purple-700 font-medium">
                Lihat Semua Notifikasi →
            </a>
        </div>
    </div>
</div>

<script>
function notificationDropdown() {
    return {
        isOpen: false,
        loading: false,
        unreadCount: 0,
        notifications: [],
        refreshInterval: null,

        init() {
            this.fetchNotifications();
            
            // Auto refresh every 30 seconds
            this.refreshInterval = setInterval(() => {
                this.fetchUnreadCount();
            }, 30000);

            // Listen for new notifications
            window.addEventListener('new-notification', (e) => {
                this.playNotificationSound();
                this.fetchNotifications();
            });
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.fetchNotifications();
            }
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const response = await fetch('{{ route("notifikasi.api.unread") }}');
                const data = await response.json();
                this.notifications = data.data || [];
                this.unreadCount = data.count || 0;
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            } finally {
                this.loading = false;
            }
        },

        async fetchUnreadCount() {
            try {
                const response = await fetch('{{ route("notifikasi.api.count") }}');
                const data = await response.json();
                const newCount = data.count || 0;
                
                // Play sound if count increased
                if (newCount > this.unreadCount) {
                    this.playNotificationSound();
                }
                
                this.unreadCount = newCount;
            } catch (error) {
                console.error('Failed to fetch notification count:', error);
            }
        },

        async markAllAsRead() {
            try {
                await fetch('{{ route("notifikasi.markAllAsRead") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });
                
                this.fetchNotifications();
                this.showToast('Semua notifikasi ditandai sebagai dibaca', 'success');
            } catch (error) {
                this.showToast('Gagal menandai notifikasi', 'error');
            }
        },

        async deleteNotification(id) {
            if (!confirm('Hapus notifikasi ini?')) return;
            
            try {
                await fetch(`/notifikasi/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                this.fetchNotifications();
                this.showToast('Notifikasi berhasil dihapus', 'success');
            } catch (error) {
                this.showToast('Gagal menghapus notifikasi', 'error');
            }
        },

        openNotification(notif) {
            // Mark as read
            if (!notif.dibaca) {
                fetch(`/notifikasi/${notif.id}/baca`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
            }

            // Navigate to link
            if (notif.link) {
                window.location.href = notif.link;
            } else {
                window.location.href = '{{ route("notifikasi.index") }}';
            }
        },

        formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diffMs = now - date;
            const diffMins = Math.floor(diffMs / 60000);
            const diffHours = Math.floor(diffMs / 3600000);
            const diffDays = Math.floor(diffMs / 86400000);

            if (diffMins < 1) return 'Baru saja';
            if (diffMins < 60) return `${diffMins} menit yang lalu`;
            if (diffHours < 24) return `${diffHours} jam yang lalu`;
            if (diffDays < 7) return `${diffDays} hari yang lalu`;
            
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
        },

        playNotificationSound() {
            // Simple notification sound using Web Audio API
            try {
                const context = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = context.createOscillator();
                const gainNode = context.createGain();

                oscillator.connect(gainNode);
                gainNode.connect(context.destination);

                oscillator.frequency.value = 800;
                oscillator.type = 'sine';

                gainNode.gain.setValueAtTime(0.3, context.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, context.currentTime + 0.5);

                oscillator.start(context.currentTime);
                oscillator.stop(context.currentTime + 0.5);
            } catch (e) {
                // Silent fail if audio not supported
            }
        },

        showToast(message, type = 'info') {
            // Emit event for toast notification
            window.dispatchEvent(new CustomEvent('show-toast', {
                detail: { message, type }
            }));
        }
    }
}
</script>

<!-- Include Alpine.js if not already included -->
@once
    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
@endonce
