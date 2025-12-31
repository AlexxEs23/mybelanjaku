<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat dengan {{ $partner->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .chat-container {
            height: calc(100vh - 300px);
            min-height: 500px;
        }
        .message-sent {
            background: linear-gradient(to right, #9333ea, #7e22ce);
            color: white;
        }
        .message-received {
            background: #f3f4f6;
            color: #1f2937;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    @include('components.sidebar')

    <div class="ml-64 p-8">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header Chat -->
            <div class="bg-purple-600 text-white p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('chat.index') }}" class="hover:bg-purple-700 p-2 rounded-lg transition">
                            ← Kembali
                        </a>
                        <div class="w-12 h-12 bg-purple-800 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ strtoupper(substr($partner->name, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-xl font-bold">{{ $partner->name }}</h2>
                            <p class="text-purple-200 text-sm">{{ ucfirst($partner->role) }}</p>
                        </div>
                    </div>

                    @if($chat->pesanan_id)
                        <a href="{{ route('admin.pesanan.index') }}" class="bg-purple-700 hover:bg-purple-800 px-4 py-2 rounded-lg text-sm transition">
                            📦 Pesanan #{{ $chat->pesanan_id }}
                        </a>
                    @endif
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="chat-container overflow-y-auto p-6 space-y-4 bg-gray-50" id="chatMessages">
                @forelse($messages as $message)
                    @php
                        $isSent = $message->pengirim_id === Auth::id();
                    @endphp

                    <div class="flex {{ $isSent ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md">
                            @if(!$isSent)
                                <div class="text-xs text-gray-500 mb-1 ml-2">{{ $message->pengirim->name }}</div>
                            @endif
                            
                            <div class="{{ $isSent ? 'message-sent' : 'message-received' }} px-4 py-3 rounded-lg shadow-sm">
                                <p class="break-words">{{ $message->pesan }}</p>
                            </div>

                            <div class="flex items-center gap-2 mt-1 {{ $isSent ? 'justify-end' : 'justify-start' }} text-xs text-gray-500 px-2">
                                <span>{{ $message->created_at->format('H:i') }}</span>
                                @if($isSent)
                                    <span>{{ $message->dibaca ? '✓✓ Dibaca' : '✓ Terkirim' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="text-6xl mb-4">💬</div>
                        <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Pesan</h3>
                        <p class="text-gray-500">Mulai percakapan dengan mengirim pesan</p>
                    </div>
                @endforelse
            </div>

            <!-- Input Form -->
            <div class="bg-white border-t p-4">
                @if(session('success'))
                    <div class="mb-3 bg-green-50 border-l-4 border-green-500 p-3 rounded text-sm text-green-700">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-3 bg-red-50 border-l-4 border-red-500 p-3 rounded text-sm text-red-700">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('chat.kirim', $chat->id) }}" method="POST" class="flex gap-3">
                    @csrf
                    <textarea 
                        name="pesan" 
                        required 
                        rows="2"
                        placeholder="Ketik pesan..." 
                        class="flex-1 px-4 py-3 border rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                        onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"
                    ></textarea>
                    <button 
                        type="submit" 
                        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-bold"
                    >
                        Kirim →
                    </button>
                </form>
                <p class="text-xs text-gray-500 mt-2">Tekan Enter untuk mengirim, Shift+Enter untuk baris baru</p>
            </div>
        </div>
    </div>

    <script>
        // Auto scroll to bottom
        function scrollToBottom() {
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }
        
        // Scroll on load
        window.addEventListener('load', scrollToBottom);

        // Auto refresh every 10 seconds to get new messages
        setInterval(function() {
            location.reload();
        }, 10000);
    </script>
</body>
</html>
