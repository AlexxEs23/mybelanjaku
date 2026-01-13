@extends('layouts.dashboard')

@section('content')
<style>
    .notification-unread {
        background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
        border-left: 4px solid #f59e0b;
    }
    .notification-read {
        background: #ffffff;
        border-left: 4px solid #e5e7eb;
    }
</style>

<div class="max-w-full">
    <!-- Header Card -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-2xl shadow-xl p-6 mb-6 text-white">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <span class="text-4xl">🔔</span>
                    <h2 class="text-2xl md:text-3xl font-bold">Notifikasi</h2>
                </div>
                <p class="text-purple-100">
                    @if($unreadCount > 0)
                        <span class="inline-flex items-center gap-2 bg-white/20 px-3 py-1 rounded-full text-sm">
                            <span class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></span>
                            <span class="font-semibold">{{ $unreadCount }}</span> notifikasi belum dibaca
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 text-sm">
                            <span>✓</span> Semua notifikasi sudah dibaca
                        </span>
                    @endif
                </p>
            </div>
            @if($unreadCount > 0)
                <form action="{{ route('notifikasi.markAllAsRead') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full md:w-auto px-6 py-3 bg-white text-purple-700 rounded-xl hover:bg-purple-50 transition font-semibold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        ✓ Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
            <div class="flex items-center gap-2">
                <span class="text-xl">✅</span>
                <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if($notifikasis->count() > 0)
        <div class="space-y-4">
            @foreach($notifikasis as $item)
                <div class="{{ $item->dibaca ? 'notification-read' : 'notification-unread' }} rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
                    <div class="p-5">
                        <div class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <div class="flex items-start gap-3 mb-3">
                                    @if(!$item->dibaca)
                                        <span class="flex-shrink-0 w-3 h-3 bg-orange-500 rounded-full animate-pulse mt-1"></span>
                                    @endif
                                    <div class="flex-1">
                                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $item->judul }}</h3>
                                        <p class="text-gray-700 leading-relaxed">{{ $item->pesan }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-3">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>

                                @if($item->link)
                                    <a href="{{ $item->link }}" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition text-sm font-medium shadow-sm hover:shadow-md">
                                        <span>Lihat Detail</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>

                            <div class="flex md:flex-col gap-2 md:items-end">
                                @if(!$item->dibaca)
                                    <form action="{{ route('notifikasi.markAsRead', $item->id) }}" method="POST" class="flex-1 md:flex-none">
                                        @csrf
                                        <button type="submit" class="w-full md:w-auto px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg hover:bg-green-100 transition text-sm font-medium whitespace-nowrap">
                                            ✓ Tandai Dibaca
                                        </button>
                                    </form>
                                @endif
                                
                                <form action="{{ route('notifikasi.delete', $item->id) }}" method="POST" onsubmit="return confirm('Hapus notifikasi ini?')" class="flex-1 md:flex-none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full md:w-auto px-4 py-2 bg-red-50 text-red-700 border border-red-200 rounded-lg hover:bg-red-100 transition text-sm font-medium whitespace-nowrap">
                                        🗑 Hapus
                                    </button>
                                </form>
                            </div>
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
        <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
            <div class="text-7xl mb-4">🔕</div>
            <h3 class="text-2xl font-bold text-gray-700 mb-2">Tidak Ada Notifikasi</h3>
            <p class="text-gray-500">Anda belum memiliki notifikasi apapun</p>
        </div>
    @endif
</div>
@endsection
