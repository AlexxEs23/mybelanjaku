<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Test Real-Time Broadcasting</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">🔥 Real-Time Broadcasting Test Dashboard</h1>

        <!-- Status -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">📊 Broadcast Status</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="font-medium">Driver:</span>
                    <span class="ml-2 px-3 py-1 bg-blue-100 text-blue-800 rounded">{{ $broadcastDriver }}</span>
                </div>
                <div>
                    <span class="font-medium">Connection:</span>
                    <span id="connection-status" class="ml-2 px-3 py-1 bg-gray-100 text-gray-800 rounded">Checking...</span>
                </div>
            </div>
        </div>

        <!-- Test Notifications -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">🔔 Test Notifikasi</h2>
            <div class="space-y-4">
                @foreach($users as $user)
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <div class="font-medium">{{ $user->name }}</div>
                        <div class="text-sm text-gray-600">ID: {{ $user->id }} | Role: {{ $user->role }}</div>
                    </div>
                    <button 
                        onclick="testNotification({{ $user->id }})"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Kirim Notifikasi
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Test Chat -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">💬 Test Chat</h2>
            <div class="space-y-4">
                @foreach($chats as $chat)
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <div class="font-medium">Chat #{{ $chat->id }}</div>
                        <div class="text-sm text-gray-600">
                            Admin: {{ $chat->admin->name ?? 'N/A' }} | 
                            Penjual: {{ $chat->penjual->name ?? 'N/A' }}
                        </div>
                    </div>
                    <button 
                        onclick="testChat({{ $chat->id }})"
                        class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Kirim Pesan
                    </button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Console Log -->
        <div class="bg-gray-900 text-green-400 rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">📝 Console Log</h2>
                <button 
                    onclick="clearConsole()"
                    class="px-3 py-1 bg-red-500 text-white rounded text-sm hover:bg-red-600">
                    Clear
                </button>
            </div>
            <div id="console-log" class="font-mono text-sm space-y-2 max-h-96 overflow-y-auto">
                <div>[{{ date('H:i:s') }}] 🚀 Dashboard loaded. Waiting for events...</div>
            </div>
        </div>
    </div>

    @vite(['resources/js/app.js'])

    <script>
        // Fungsi untuk menambahkan log ke console
        function addLog(message, type = 'info') {
            const consoleLog = document.getElementById('console-log');
            const time = new Date().toLocaleTimeString('id-ID');
            const colors = {
                info: 'text-green-400',
                success: 'text-blue-400',
                error: 'text-red-400',
                warning: 'text-yellow-400'
            };
            const color = colors[type] || colors.info;
            
            const logEntry = document.createElement('div');
            logEntry.className = color;
            logEntry.textContent = `[${time}] ${message}`;
            consoleLog.appendChild(logEntry);
            consoleLog.scrollTop = consoleLog.scrollHeight;
        }

        function clearConsole() {
            document.getElementById('console-log').innerHTML = '';
            addLog('Console cleared');
        }

        // Test Notifikasi
        async function testNotification(userId) {
            try {
                addLog(`Mengirim notifikasi ke user ${userId}...`, 'info');
                const response = await fetch(`/test/notification/${userId}`);
                const data = await response.json();
                
                if (data.success) {
                    addLog(`✅ Notifikasi berhasil dikirim ke user ${userId}`, 'success');
                    addLog(`📡 Channel: ${data.channel} | Event: ${data.event}`, 'info');
                }
            } catch (error) {
                addLog(`❌ Error: ${error.message}`, 'error');
            }
        }

        // Test Chat
        async function testChat(chatId) {
            try {
                addLog(`Mengirim pesan ke chat ${chatId}...`, 'info');
                const response = await fetch(`/test/chat/${chatId}`);
                const data = await response.json();
                
                if (data.success) {
                    addLog(`✅ Pesan berhasil dikirim ke chat ${chatId}`, 'success');
                    addLog(`📡 Channel: ${data.channel} | Event: ${data.event}`, 'info');
                }
            } catch (error) {
                addLog(`❌ Error: ${error.message}`, 'error');
            }
        }

        // Listen untuk events (jika Echo tersedia)
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof Echo !== 'undefined') {
                addLog('✅ Laravel Echo loaded', 'success');
                document.getElementById('connection-status').textContent = 'Connected';
                document.getElementById('connection-status').className = 'ml-2 px-3 py-1 bg-green-100 text-green-800 rounded';
                
                // Subscribe ke events (contoh)
                @foreach($users as $user)
                Echo.private('user.{{ $user->id }}')
                    .listen('.notification.sent', (e) => {
                        addLog(`🔔 Notifikasi diterima untuk user {{ $user->id }}: ${e.judul}`, 'success');
                        console.log('Notification received:', e);
                    });
                @endforeach

                @foreach($chats as $chat)
                Echo.private('chat.{{ $chat->id }}')
                    .listen('.message.sent', (e) => {
                        addLog(`💬 Pesan diterima di chat {{ $chat->id }}: ${e.pesan}`, 'success');
                        console.log('Message received:', e);
                    });
                @endforeach
            } else {
                addLog('⚠️ Laravel Echo not found. Install and configure Echo.', 'warning');
                document.getElementById('connection-status').textContent = 'Echo Not Found';
                document.getElementById('connection-status').className = 'ml-2 px-3 py-1 bg-yellow-100 text-yellow-800 rounded';
            }
        });
    </script>
</body>
</html>
