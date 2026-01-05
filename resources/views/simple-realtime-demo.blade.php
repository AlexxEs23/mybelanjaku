<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Real-Time Demo (No NPM)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Real-time via CDN --}}
    <x-realtime-cdn />
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-xl font-bold">🔥 Real-Time Demo (Tanpa NPM)</h1>
                <span class="text-gray-600">{{ auth()->user()->name }}</span>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-4">🔔 Test Notifikasi Real-Time</h2>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-900">
                    <strong>✨ Cara Test:</strong><br>
                    1. Klik tombol "Kirim Notifikasi" di bawah<br>
                    2. Tunggu 1-2 detik<br>
                    3. Notifikasi akan <strong>muncul OTOMATIS</strong> di pojok kanan atas (tanpa refresh!) 🎉
                </p>
            </div>

            <form id="send-notification-form" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium mb-1">Judul</label>
                    <input 
                        type="text" 
                        name="judul" 
                        value="Test Real-Time Notification"
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Pesan</label>
                    <textarea 
                        name="pesan" 
                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                        rows="3"
                        required
                    >Notifikasi ini muncul TANPA refresh halaman! 🚀</textarea>
                </div>
                
                <button 
                    type="submit"
                    class="w-full bg-blue-500 text-white px-4 py-3 rounded-lg hover:bg-blue-600 font-semibold"
                >
                    🚀 Kirim Notifikasi
                </button>
            </form>
            
            <div id="status" class="mt-4 p-3 rounded-lg hidden"></div>

            {{-- Status Panel --}}
            <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-semibold mb-3">📊 Status Koneksi:</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>WebSocket:</span>
                        <span id="ws-status" class="font-mono">Checking...</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Echo:</span>
                        <span id="echo-status" class="font-mono">Checking...</span>
                    </div>
                    <div class="flex justify-between">
                        <span>User ID:</span>
                        <span class="font-mono text-blue-600">{{ auth()->id() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Notifications Received:</span>
                        <span id="notif-count" class="font-mono text-green-600">0</span>
                    </div>
                </div>
            </div>

            {{-- Event Log --}}
            <div class="mt-6">
                <h3 class="font-semibold mb-2">📝 Event Log:</h3>
                <div id="event-log" class="bg-gray-900 text-green-400 rounded p-3 h-48 overflow-y-auto font-mono text-xs">
                    <div>[{{ date('H:i:s') }}] Waiting for events...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let notificationCount = 0;

        // Check connection status
        setTimeout(() => {
            if (typeof window.Echo !== 'undefined') {
                document.getElementById('echo-status').textContent = '✓ Connected';
                document.getElementById('echo-status').className = 'font-mono text-green-600';
                addLog('✅ Laravel Echo initialized');

                if (window.Echo.connector.pusher.connection.state === 'connected') {
                    document.getElementById('ws-status').textContent = '✓ Connected';
                    document.getElementById('ws-status').className = 'font-mono text-green-600';
                    addLog('✅ WebSocket connected to Reverb');
                } else {
                    document.getElementById('ws-status').textContent = '✗ Disconnected';
                    document.getElementById('ws-status').className = 'font-mono text-red-600';
                    addLog('⚠️ WebSocket not connected');
                }
            } else {
                document.getElementById('echo-status').textContent = '✗ Not Found';
                document.getElementById('echo-status').className = 'font-mono text-red-600';
                addLog('❌ Laravel Echo not found');
            }
        }, 2000);

        // Listen for notifications
        window.addEventListener('notification-received', (e) => {
            notificationCount++;
            document.getElementById('notif-count').textContent = notificationCount;
            addLog(`🔔 Notification received: ${e.detail.judul}`, 'success');
        });

        // Form submit handler
        document.getElementById('send-notification-form').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const judul = formData.get('judul');
            const pesan = formData.get('pesan');
            
            addLog('📤 Sending notification...');
            showStatus('⏳ Mengirim notifikasi...', 'info');
            
            try {
                const response = await fetch('/test/notification/{{ auth()->id() }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    showStatus('✅ Notifikasi berhasil dikirim! Tunggu beberapa detik, akan muncul otomatis di pojok kanan atas!', 'success');
                    addLog('✅ Notification sent successfully', 'success');
                } else {
                    showStatus('❌ Gagal mengirim notifikasi', 'error');
                    addLog('❌ Failed to send notification', 'error');
                }
            } catch (error) {
                showStatus('❌ Error: ' + error.message, 'error');
                addLog('❌ Error: ' + error.message, 'error');
            }
        });

        function showStatus(message, type) {
            const statusDiv = document.getElementById('status');
            statusDiv.textContent = message;
            statusDiv.className = 'mt-4 p-3 rounded-lg ';
            
            if (type === 'success') {
                statusDiv.className += 'bg-green-100 text-green-800 border border-green-200';
            } else if (type === 'info') {
                statusDiv.className += 'bg-blue-100 text-blue-800 border border-blue-200';
            } else {
                statusDiv.className += 'bg-red-100 text-red-800 border border-red-200';
            }
            
            statusDiv.classList.remove('hidden');
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
    </script>
</body>
</html>
