<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - {{ Auth::user()->role }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .chat-unread {
            background: linear-gradient(to right, #fef3c7, #fffbeb);
            border-left: 4px solid #f59e0b;
        }
        .chat-read {
            background: #ffffff;
            border-left: 4px solid #e5e7eb;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">
    @include('components.sidebar')

    <div class="ml-64 p-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">💬 Chat</h2>
                    <p class="text-gray-600 mt-1">
                        @if(Auth::user()->role === 'admin')
                            Kelola komunikasi dengan penjual
                        @else
                            Komunikasi dengan admin
                        @endif
                    </p>
                </div>

                @if(Auth::user()->role === 'admin')
                    <button onclick="showCreateChatModal()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        + Buat Chat Baru
                    </button>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            @endif

            @if($chats->count() > 0)
                <div class="space-y-3">
                    @foreach($chats as $chat)
                        @php
                            $partner = Auth::user()->role === 'admin' ? $chat->penjual : $chat->admin;
                            $hasUnread = $chat->pesanChats()
                                ->where('pengirim_id', '!=', Auth::id())
                                ->where('dibaca', false)
                                ->exists();
                            $lastMessage = $chat->pesanChats()->latest()->first();
                        @endphp

                        <a href="{{ route('chat.show', $chat->id) }}" 
                           class="{{ $hasUnread ? 'chat-unread' : 'chat-read' }} p-5 rounded-lg shadow-sm hover:shadow-md transition block">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start gap-4 flex-1">
                                    <!-- Avatar -->
                                    <div class="w-14 h-14 bg-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl flex-shrink-0">
                                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-1">
                                            @if($hasUnread)
                                                <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                                            @endif
                                            <h3 class="font-bold text-gray-900 text-lg">{{ $partner->name }}</h3>
                                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                                {{ $partner->role }}
                                            </span>
                                        </div>

                                        @if($lastMessage)
                                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">
                                                @if($lastMessage->pengirim_id === Auth::id())
                                                    <span class="font-semibold">Anda:</span>
                                                @endif
                                                {{ $lastMessage->pesan }}
                                            </p>
                                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                                <span>🕒 {{ $lastMessage->created_at->diffForHumans() }}</span>
                                            </div>
                                        @else
                                            <p class="text-gray-400 text-sm italic">Belum ada pesan</p>
                                        @endif

                                        @if($chat->pesanan_id)
                                            <div class="mt-2">
                                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded">
                                                    📦 Pesanan #{{ $chat->pesanan_id }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($hasUnread)
                                    <div class="flex-shrink-0">
                                        <span class="bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                            {{ $chat->pesanChats()->where('pengirim_id', '!=', Auth::id())->where('dibaca', false)->count() }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $chats->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">💬</div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Belum Ada Chat</h3>
                    <p class="text-gray-500">
                        @if(Auth::user()->role === 'admin')
                            Chat akan otomatis dibuat saat Anda mengkonfirmasi pesanan
                        @else
                            Chat akan dibuat oleh admin setelah pesanan dikonfirmasi
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Create Chat (Admin Only) -->
    @if(Auth::user()->role === 'admin')
        <div id="createChatModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Buat Chat Baru</h3>
                <form action="{{ route('chat.create') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Penjual</label>
                        <select name="penjual_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Pilih Penjual --</option>
                            @foreach(\App\Models\User::where('role', 'penjual')->where('status_approval', 'approved')->get() as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesanan (Opsional)</label>
                        <select name="pesanan_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-purple-500">
                            <option value="">-- Tidak Ada Pesanan --</option>
                            @foreach(\App\Models\Pesanan::latest()->take(20)->get() as $pesanan)
                                <option value="{{ $pesanan->id }}">
                                    #{{ $pesanan->id }} - {{ $pesanan->produk->nama_produk ?? 'Produk Dihapus' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="hideCreateChatModal()" class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                            Buat Chat
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function showCreateChatModal() {
                document.getElementById('createChatModal').classList.remove('hidden');
            }
            function hideCreateChatModal() {
                document.getElementById('createChatModal').classList.add('hidden');
            }
        </script>
    @endif
</body>
</html>
