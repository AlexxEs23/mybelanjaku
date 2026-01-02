@extends('layouts.dashboard')

@section('content')
<style>
    .chat-container {
        height: calc(100vh - 350px);
        min-height: 500px;
    }
    .message-sent {
        background: linear-gradient(135deg, #9333ea 0%, #7e22ce 100%);
        color: white;
        border-radius: 18px 18px 4px 18px;
    }
    .message-received {
        background: #ffffff;
        color: #1f2937;
        border: 1px solid #e5e7eb;
        border-radius: 18px 18px 18px 4px;
    }
    .chat-bubble-enter {
        animation: slideUp 0.3s ease-out;
    }
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="max-w-5xl mx-auto">
    <!-- Chat Header Card -->
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 rounded-2xl shadow-2xl overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <a href="{{ route('chat.index') }}" class="hover:bg-white/20 p-2 rounded-xl transition text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        <span class="hidden md:inline">Kembali</span>
                    </a>
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                        {{ strtoupper(substr($partner->name, 0, 1)) }}
                    </div>
                    <div class="text-white">
                        <h2 class="text-xl md:text-2xl font-bold flex items-center gap-2">
                            {{ $partner->name }}
                            <span class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></span>
                        </h2>
                        <p class="text-purple-200 text-sm">{{ ucfirst($partner->role) }}</p>
                    </div>
                </div>

                @if($chat->pesanan_id)
                    <a href="{{ route('admin.pesanan.index') }}" class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm hover:bg-white/30 px-4 py-2 rounded-xl text-white text-sm transition font-medium">
                        <span>📦</span>
                        <span>Pesanan #{{ $chat->pesanan_id }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
        <!-- Chat Messages -->
        <div class="chat-container overflow-y-auto p-6 space-y-4 bg-gradient-to-b from-gray-50 to-white" id="chatMessages">
            @forelse($messages as $message)
                @php
                    $isSent = $message->pengirim_id === Auth::id();
                @endphp

                <div class="flex {{ $isSent ? 'justify-end' : 'justify-start' }} chat-bubble-enter">
                    <div class="max-w-md md:max-w-xl">
                        @if(!$isSent)
                            <div class="text-xs font-medium text-gray-600 mb-1 ml-3">{{ $message->pengirim->name }}</div>
                        @endif
                        
                        <div class="{{ $isSent ? 'message-sent' : 'message-received' }} px-5 py-3 shadow-md">
                            <p class="break-words text-sm leading-relaxed">{{ $message->pesan }}</p>
                        </div>

                        <div class="flex items-center gap-2 mt-1 {{ $isSent ? 'justify-end' : 'justify-start' }} text-xs text-gray-500 px-3">
                            <span>{{ $message->created_at->format('H:i') }}</span>
                            @if($isSent)
                                @if($message->dibaca)
                                    <span class="text-blue-500">✓✓</span>
                                @else
                                    <span class="text-gray-400">✓</span>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <div class="text-center py-16">
                        <div class="text-7xl mb-4">💬</div>
                        <h3 class="text-2xl font-bold text-gray-700 mb-2">Belum Ada Pesan</h3>
                        <p class="text-gray-500">Mulai percakapan dengan mengirim pesan pertama</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Input Form -->
        <div class="bg-gradient-to-r from-gray-50 to-white border-t border-gray-200 p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">✅</span>
                        <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                    <div class="flex items-center gap-2">
                        <span class="text-xl">❌</span>
                        <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <form action="{{ route('chat.kirim', $chat->id) }}" method="POST" class="flex flex-col md:flex-row gap-3">
                @csrf
                <textarea 
                    name="pesan" 
                    required 
                    rows="2"
                    placeholder="Ketik pesan..." 
                    class="flex-1 px-5 py-4 border-2 border-gray-200 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none transition-all duration-200 text-sm"
                    onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); this.form.submit(); }"
                ></textarea>
                <button 
                    type="submit" 
                    class="px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-2xl hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 font-bold shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2"
                >
                    <span>Kirim</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>
            <p class="text-xs text-gray-500 mt-3 text-center md:text-left">
                <span class="inline-flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tekan Enter untuk mengirim, Shift+Enter untuk baris baru
                </span>
            </p>
        </div>
    </div>
</div>

<script>
    // Auto scroll to bottom with smooth animation
    function scrollToBottom() {
        const chatMessages = document.getElementById('chatMessages');
        chatMessages.scrollTo({
            top: chatMessages.scrollHeight,
            behavior: 'smooth'
        });
    }
    
    // Scroll on load
    window.addEventListener('load', function() {
        setTimeout(scrollToBottom, 100);
    });

    // 🔥 REAL-TIME CHAT DENGAN LARAVEL ECHO
    const chatId = {{ $chat->id }};
    const currentUserId = {{ Auth::id() }};
    
    // Subscribe ke private channel chat
    window.Echo.private(`chat.${chatId}`)
        .listen('.message.sent', (e) => {
            console.log('🔥 New message received:', e);
            
            // Jangan tampilkan pesan dari diri sendiri (sudah ada di UI)
            if (e.pengirim_id === currentUserId) return;
            
            // Buat elemen message bubble
            const isSent = e.pengirim_id === currentUserId;
            const messageHTML = `
                <div class="flex ${isSent ? 'justify-end' : 'justify-start'} chat-bubble-enter">
                    <div class="max-w-md md:max-w-xl">
                        ${!isSent ? `<div class="text-xs font-medium text-gray-600 mb-1 ml-3">${e.pengirim_name}</div>` : ''}
                        
                        <div class="${isSent ? 'message-sent' : 'message-received'} px-5 py-3 shadow-md">
                            <p class="break-words text-sm leading-relaxed">${e.pesan}</p>
                        </div>

                        <div class="flex items-center gap-2 mt-1 ${isSent ? 'justify-end' : 'justify-start'} text-xs text-gray-500 px-3">
                            <span>${e.created_at}</span>
                            ${isSent ? (e.dibaca ? '<span class="text-blue-500">✓✓</span>' : '<span class="text-gray-400">✓</span>') : ''}
                        </div>
                    </div>
                </div>
            `;
            
            // Append ke container
            const chatMessages = document.getElementById('chatMessages');
            chatMessages.insertAdjacentHTML('beforeend', messageHTML);
            
            // Auto scroll ke bawah
            scrollToBottom();
            
            // Play notification sound (optional)
            if (typeof Audio !== 'undefined') {
                const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBSuBzvLZijYIGmi78OScTgwOUKfj8LdjHAU6kdTzz38uBSh4yO/ekkAKFGC06eyrVBULSKDf8sB2IwUqftDy2I42CRlqvu/mnEsMEVO06+ypWRQLTKLh8sF4JAUngM/y14s3BxlqvO3mnksLEVG26+uoWhMMTaPi88N5JAYnf87y14w3CBlpvO3mnUsMEVCz7OuoWxQLS6Xh88N5JAYmfs/y14w2BxpovO7mnUoLEU+06uyoWhQLSqLh88N5JAUnfsvy14w2BxlovO3mnksLEVG06uyoWhQLSaLh88N5JAUmfczy14s2BxtovO3mnEoLEk+z6+ymWhQMSqLh88N5JAYmfsvy14w1CBtovO3lnUoLE0+z6+2mWBULSaLi88N4JAYlfsvy14s1CBxovO3lnUoLE0606uyoWhQLSaLi88N4JAYlfsvy14s1CBxou+zmnUoLEk606uynWhQLSKPj88N4JAYlfsvy14s1CBxouuzmnkoLEk606uynWhQLSKPi9MN4JAUlf8zy14s1CBxnuuzmnkoLEk606uynWhQKSKPi9MN4JAUlf8zy14o1CBxnu+zmnUoLE0606uymWhQKSKPi88N4JAYlf8zy14s1CBxnu+zmnUoLE0606uymWhQKSKPi88R4JAUlf8zy14s1CBxnu+zmm0oLE0606+ymWhQKSKPi88R4JAUkgMzy14s1CBxnu+zmm0oLE0606+ymWhQKSKPi88R4JAUkgMzy14s1CBxnu+zmm0oLE0606+ymWhQKSKPi88R4JAUkgMzy14s1BxxpvOvlm0sLE0246+2lWBUKSKPi88R4JAUkgMzy14s1Bxtouu7km0sLE0255eymWhUKSKPi88N4JAYkgMzy14o1BxtpvOvlm0sLE0246+2lWhUKSKPi88N4JAYkgMzy14o1BxtpvOvlm0sLE0246+2lWhUKSKPi88N4JAYkf8zy14s1Bxppve3lm0sLE0245+2mWhUKR6Pj88N4JAUkf8zy14s1Bxpovezlm0sLE024'
                );
                audio.volume = 0.3;
                audio.play().catch(() => {}); // Ignore autoplay errors
            }
        });
</script>
@endsection
