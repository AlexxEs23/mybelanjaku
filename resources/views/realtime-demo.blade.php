<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo Real-Time - {{ config('app.name') }}</title>
    
    {{-- Real-time Meta Tags --}}
    <x-realtime-meta />
    
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">Real-Time Demo</h1>
                
                @auth
                    <div class="flex items-center gap-4">
                        <span class="text-gray-600">{{ auth()->user()->name }}</span>
                        
                        {{-- Notification Bell --}}
                        <x-notification-bell 
                            :notifications="auth()->user()->notifikasis()->latest()->take(5)->get()"
                            :unreadCount="auth()->user()->notifikasis()->where('dibaca', false)->count()"
                        />
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {{-- Notification Test Panel --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">🔔 Test Notifikasi Real-Time</h2>
                <p class="text-gray-600 mb-4">
                    Klik tombol untuk mengirim notifikasi. 
                    <strong>Notifikasi akan muncul otomatis tanpa refresh!</strong>
                </p>
                
                <form id="send-notification-form" class="space-y-4">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Judul</label>
                        <input 
                            type="text" 
                            name="judul" 
                            value="Test Notifikasi Real-Time"
                            class="w-full px-3 py-2 border rounded-lg"
                            required
                        >
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Pesan</label>
                        <textarea 
                            name="pesan" 
                            class="w-full px-3 py-2 border rounded-lg"
                            rows="3"
                            required
                        >Ini adalah notifikasi real-time yang muncul tanpa refresh!</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1">Tipe</label>
                        <select name="tipe" class="w-full px-3 py-2 border rounded-lg">
                            <option value="info">Info</option>
                            <option value="success">Success</option>
                            <option value="warning">Warning</option>
                            <option value="error">Error</option>
                        </select>
                    </div>
                    
                    <button 
                        type="submit"
                        class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600"
                    >
                        Kirim Notifikasi
                    </button>
                </form>
                
                <div id="notification-status" class="mt-4 p-3 rounded-lg hidden"></div>
            </div>
            
            {{-- Real-Time Status --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-2xl font-bold mb-4">📊 Status Real-Time</h2>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span class="font-medium">WebSocket Status:</span>
                        <span id="websocket-status" class="px-3 py-1 bg-gray-200 text-gray-800 rounded text-sm">
                            Checking...
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span class="font-medium">Echo Status:</span>
                        <span id="echo-status" class="px-3 py-1 bg-gray-200 text-gray-800 rounded text-sm">
                            Checking...
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span class="font-medium">User ID:</span>
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded text-sm">
                            {{ auth()->id() }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded">
                        <span class="font-medium">Notifications Received:</span>
                        <span id="notification-count" class="px-3 py-1 bg-green-100 text-green-800 rounded text-sm">
                            0
                        </span>
                    </div>
                </div>
                
                {{-- Event Log --}}
                <div class="mt-6">
                    <h3 class="font-semibold mb-2">📝 Event Log:</h3>
                    <div id="event-log" class="bg-gray-900 text-green-400 rounded p-3 h-64 overflow-y-auto font-mono text-xs">
                        <div>[{{ date('H:i:s') }}] Waiting for events...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let notificationReceivedCount = 0;
        
        // Check Echo and WebSocket status
        document.addEventListener('DOMContentLoaded', () => {
            checkEchoStatus();
            setupNotificationListener();
            setupFormHandler();
        });
        
        function checkEchoStatus() {
            if (typeof window.Echo !== 'undefined') {
                document.getElementById('echo-status').innerHTML = 
                    '<span class="text-green-600">✓ Connected</span>';
                document.getElementById('echo-status').classList.remove('bg-gray-200');
                document.getElementById('echo-status').classList.add('bg-green-100');
                
                addLog('✅ Laravel Echo initialized');
            } else {
                document.getElementById('echo-status').innerHTML = 
                    '<span class="text-red-600">✗ Not Found</span>';
                document.getElementById('echo-status').classList.remove('bg-gray-200');
                document.getElementById('echo-status').classList.add('bg-red-100');
                
                addLog('❌ Laravel Echo not found');
            }
            
            // Check WebSocket connection
            setTimeout(() => {
                if (window.Echo && window.Echo.connector.pusher.connection.state === 'connected') {
                    document.getElementById('websocket-status').innerHTML = 
                        '<span class="text-green-600">✓ Connected</span>';
                    document.getElementById('websocket-status').classList.remove('bg-gray-200');
                    document.getElementById('websocket-status').classList.add('bg-green-100');
                    
                    addLog('✅ WebSocket connected to Reverb');
                } else {
                    document.getElementById('websocket-status').innerHTML = 
                        '<span class="text-yellow-600">⚠ Disconnected</span>';
                    document.getElementById('websocket-status').classList.remove('bg-gray-200');
                    document.getElementById('websocket-status').classList.add('bg-yellow-100');
                    
                    addLog('⚠️ WebSocket not connected. Is Reverb running?');
                }
            }, 2000);
        }
        
        function setupNotificationListener() {
            window.addEventListener('notification-received', (e) => {
                notificationReceivedCount++;
                document.getElementById('notification-count').textContent = notificationReceivedCount;
                
                addLog(`🔔 Notification received: ${e.detail.judul}`, 'success');
            });
        }
        
        function setupFormHandler() {
            const form = document.getElementById('send-notification-form');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);
                
                addLog(`📤 Sending notification...`);
                
                try {
                    const response = await fetch('/test/notification/' + data.user_id, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        showStatus('✅ Notifikasi berhasil dikirim! Tunggu beberapa detik...', 'success');
                        addLog(`✅ Notification sent successfully`, 'success');
                    }
                } catch (error) {
                    showStatus('❌ Error: ' + error.message, 'error');
                    addLog(`❌ Error: ${error.message}`, 'error');
                }
            });
        }
        
        function showStatus(message, type) {
            const statusDiv = document.getElementById('notification-status');
            statusDiv.textContent = message;
            statusDiv.className = 'mt-4 p-3 rounded-lg ';
            
            if (type === 'success') {
                statusDiv.className += 'bg-green-100 text-green-800';
            } else {
                statusDiv.className += 'bg-red-100 text-red-800';
            }
            
            statusDiv.classList.remove('hidden');
            
            setTimeout(() => {
                statusDiv.classList.add('hidden');
            }, 5000);
        }
        
        function addLog(message, type = 'info') {
            const log = document.getElementById('event-log');
            const time = new Date().toLocaleTimeString('id-ID');
            const entry = document.createElement('div');
            
            const colors = {
                info: 'text-green-400',
                success: 'text-blue-400',
                error: 'text-red-400',
            };
            
            entry.className = colors[type] || colors.info;
            entry.textContent = `[${time}] ${message}`;
            
            log.appendChild(entry);
            log.scrollTop = log.scrollHeight;
        }
        
        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    </script>
</body>
</html>
