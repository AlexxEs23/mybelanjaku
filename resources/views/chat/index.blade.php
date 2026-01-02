@extends('layouts.dashboard')

@section('content')
<style>
    .chat-unread {
        background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
        border-left: 4px solid #f59e0b;
    }
    .chat-read {
        background: #ffffff;
        border-left: 4px solid #e5e7eb;
    }
</style>

<div class="max-w-5xl mx-auto">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl shadow-xl p-8 mb-6 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 opacity-10">
            <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
            </svg>
        </div>
        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-4xl">💬</span>
                        <h2 class="text-3xl font-bold">Chat</h2>
                    </div>
                    <p class="text-purple-100">
                        @if(Auth::user()->role === 'admin')
                            Kelola komunikasi dengan penjual
                        @else
                            Komunikasi dengan admin
                        @endif
                    </p>
                </div>
                @if(Auth::user()->role === 'admin')
                    <button onclick="showCreateChatModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-purple-700 rounded-xl hover:bg-purple-50 transition font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        <span>+</span>
                        <span>Buat Chat Baru</span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">✅</span>
                <p class="font-medium text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">❌</span>
                <p class="font-medium text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if($chats->count() > 0)
        <div class="space-y-4">
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
                           class="{{ $hasUnread ? 'chat-unread' : 'chat-read' }} rounded-xl shadow-md hover:shadow-lg transition-all duration-300 block overflow-hidden">
                            <div class="p-6">
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
                <div class="bg-white rounded-2xl shadow-lg p-16 text-center">
                    <div class="text-7xl mb-4">💬</div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Chat</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
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
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Buat Chat Baru</h3>
                <form action="{{ route('chat.create') }}" method="POST">
                    @csrf
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Pilih Penjual</label>
                        <select name="penjual_id" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">-- Pilih Penjual --</option>
                            @foreach(\App\Models\User::where('role', 'penjual')->where('status_approval', 'approved')->get() as $seller)
                                <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Pesanan (Opsional)</label>
                        <select name="pesanan_id" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">-- Tidak Ada Pesanan --</option>
                            @foreach(\App\Models\Pesanan::latest()->take(20)->get() as $pesanan)
                                <option value="{{ $pesanan->id }}">
                                    #{{ $pesanan->id }} - {{ $pesanan->produk->nama_produk ?? 'Produk Dihapus' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="hideCreateChatModal()" class="flex-1 px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition font-semibold">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition font-semibold shadow-lg">
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
@endsection
