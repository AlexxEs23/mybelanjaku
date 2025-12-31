@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Pesanan Saya</h1>
            <p class="mt-2 text-sm text-gray-600">Daftar pesanan yang telah Anda buat</p>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Orders List -->
        @if($pesanan->count() > 0)
            <div class="space-y-4">
                @foreach($pesanan as $item)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-xs text-gray-500">Order #{{ $item->id }}</span>
                                    <p class="text-sm text-gray-600 mt-1">{{ $item->created_at->format('d F Y, H:i') }}</p>
                                </div>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($item->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($item->status == 'di proses') bg-blue-100 text-blue-800
                                    @elseif($item->status == 'di kirim') bg-purple-100 text-purple-800
                                    @elseif($item->status == 'di terima') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>

                            <div class="border-t border-gray-100 pt-4">
                                <div class="flex items-start space-x-4">
                                    <!-- Product Image Placeholder -->
                                    <div class="w-20 h-20 bg-gray-200 rounded-lg flex-shrink-0 flex items-center justify-center">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->produk->nama_produk }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">Penjual: {{ $item->produk->user->name }}</p>
                                        <div class="mt-2 flex items-center space-x-4 text-sm">
                                            <span class="text-gray-600">Jumlah: <span class="font-medium text-gray-900">{{ $item->jumlah }}</span></span>
                                            <span class="text-gray-600">Harga: <span class="font-medium text-gray-900">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span></span>
                                        </div>
                                        <div class="mt-2">
                                            <span class="text-base font-bold text-gray-900">Total: Rp {{ number_format($item->total, 0, ',', '.') }}</span>
                                        </div>
                                        @if($item->catatan)
                                            <p class="mt-2 text-sm text-gray-600 italic">Catatan: {{ $item->catatan }}</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Action Button -->
                                @if($item->status == 'di kirim')
                                    <div class="mt-4 pt-4 border-t border-gray-100">
                                        <form action="{{ route('pembeli.pesanan.terima', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin sudah menerima pesanan ini?')">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-200">
                                                Pesanan Diterima
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pesanan->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Pesanan</h3>
                <p class="mt-2 text-sm text-gray-600">Anda belum memiliki pesanan. Mulai berbelanja sekarang!</p>
                <div class="mt-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Mulai Belanja
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
