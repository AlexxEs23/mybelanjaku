<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - {{ Auth::user()->role }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .notification-unread {
            background: linear-gradient(to right, #fef3c7, #fffbeb);
            border-left: 4px solid #f59e0b;
        }
        .notification-read {
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
                    <h2 class="text-3xl font-bold text-gray-800">🔔 Notifikasi</h2>
                    <p class="text-gray-600 mt-1">
                        @if($unreadCount > 0)
                            Anda memiliki <span class="font-bold text-orange-600">{{ $unreadCount }}</span> notifikasi belum dibaca
                        @else
                            Semua notifikasi sudah dibaca
                        @endif
                    </p>
                </div>
                @if($unreadCount > 0)
                    <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            ✓ Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if($notifikasis->count() > 0)
                <div class="space-y-3">
                    @foreach($notifikasis as $item)
                        <div class="{{ $item->dibaca ? 'notification-read' : 'notification-unread' }} p-5 rounded-lg shadow-sm hover:shadow-md transition">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        @if(!$item->dibaca)
                                            <span class="w-2 h-2 bg-orange-500 rounded-full animate-pulse"></span>
                                        @endif
                                        <h3 class="font-bold text-gray-900 text-lg">{{ $item->judul }}</h3>
                                    </div>
                                    <p class="text-gray-700 mb-3">{{ $item->pesan }}</p>
                                    
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        <span>🕒 {{ $item->created_at->diffForHumans() }}</span>
                                        <span>📅 {{ $item->created_at->format('d M Y, H:i') }}</span>
                                    </div>

                                    @if($item->link)
                                        <a href="{{ $item->link }}" class="inline-block mt-3 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm">
                                            Lihat Detail →
                                        </a>
                                    @endif
                                </div>

                                <div class="flex flex-col gap-2 ml-4">
                                    @if(!$item->dibaca)
                                        <form action="{{ route('notifikasi.markAsRead', $item->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200 transition text-sm">
                                                ✓ Tandai Dibaca
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <form action="{{ route('notifikasi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200 transition text-sm">
                                            🗑 Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $notifikasis->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <div class="text-6xl mb-4">🔕</div>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Tidak Ada Notifikasi</h3>
                    <p class="text-gray-500">Anda belum memiliki notifikasi apapun</p>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
