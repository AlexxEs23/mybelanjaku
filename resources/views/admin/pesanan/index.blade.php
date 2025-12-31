<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pesanan - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    @include('components.sidebar')

    <div class="ml-64 p-8">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">📦 Data Pesanan</h2>
                    <p class="text-gray-600 mt-1">Semua pesanan yang masuk via WhatsApp</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            @if($pesanan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembeli</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Penjual</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pesanan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $item->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->user ? $item->user->name : 'Guest' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $item->produk->nama_produk }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $item->produk->user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->jumlah }} unit
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        Rp {{ number_format($item->total, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($item->status === 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    Pending
                                                </span>
                                            @elseif($item->status === 'di proses')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    Di Proses
                                                </span>
                                            @elseif($item->status === 'di kirim')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Di Kirim
                                                </span>
                                            @elseif($item->status === 'di terima')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    Di Terima
                                                </span>
                                            @elseif($item->status === 'dibatalkan')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($item->status === 'pending')
                                            <div class="flex gap-2">
                                                <form action="{{ route('admin.pesanan.konfirmasi', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-green-600 text-white text-xs font-medium rounded hover:bg-green-700 transition" onclick="return confirm('Konfirmasi pesanan ini?')">
                                                        ✓ Konfirmasi
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.pesanan.batalkan', $item->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-red-600 text-white text-xs font-medium rounded hover:bg-red-700 transition" onclick="return confirm('Batalkan pesanan ini?')">
                                                        ✗ Batalkan
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($item->status === 'diproses')
                                            <span class="text-xs text-blue-600">⏳ Menunggu Penjual</span>
                                        @elseif($item->status === 'dikirim')
                                            <span class="text-xs text-purple-600">📦 Dalam Pengiriman</span>
                                        @elseif($item->status === 'selesai')
                                            <span class="text-xs text-green-600">✓ Selesai</span>
                                        @elseif($item->status === 'dibatalkan')
                                            <span class="text-xs text-red-600">✗ Dibatalkan</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $pesanan->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="text-6xl mb-4">📦</div>
                    <p class="text-gray-500 text-lg">Belum ada pesanan</p>
                </div>
            @endif
        </div>
    </div>
                
                <div class="flex items-center gap-4">
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 text-purple-600 hover:bg-purple-50 rounded-lg transition">
                        ← Kembali ke Dashboard
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">📦 Kelola Pesanan</h1>
            <p class="text-gray-600">Kelola semua pesanan pelanggan</p>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-xl">✅</span>
                    <p class="font-semibold">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg">
                <div class="flex items-center gap-2">
                    <span class="text-xl">⚠️</span>
                    <p class="font-semibold">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
            <div class="bg-yellow-100 rounded-lg p-4">
                <div class="text-2xl mb-2">⏳</div>
                <div class="text-2xl font-bold text-yellow-700">{{ $pesanan->where('status', 'menunggu')->count() }}</div>
                <div class="text-sm text-yellow-600">Menunggu</div>
            </div>
            <div class="bg-blue-100 rounded-lg p-4">
                <div class="text-2xl mb-2">🔄</div>
                <div class="text-2xl font-bold text-blue-700">{{ $pesanan->where('status', 'diproses')->count() }}</div>
                <div class="text-sm text-blue-600">Diproses</div>
            </div>
            <div class="bg-purple-100 rounded-lg p-4">
                <div class="text-2xl mb-2">🚚</div>
                <div class="text-2xl font-bold text-purple-700">{{ $pesanan->where('status', 'dikirim')->count() }}</div>
                <div class="text-sm text-purple-600">Dikirim</div>
            </div>
            <div class="bg-green-100 rounded-lg p-4">
                <div class="text-2xl mb-2">✅</div>
                <div class="text-2xl font-bold text-green-700">{{ $pesanan->where('status', 'selesai')->count() }}</div>
                <div class="text-sm text-green-600">Selesai</div>
            </div>
            <div class="bg-red-100 rounded-lg p-4">
                <div class="text-2xl mb-2">❌</div>
                <div class="text-2xl font-bold text-red-700">{{ $pesanan->where('status', 'dibatalkan')->count() }}</div>
                <div class="text-sm text-red-600">Dibatalkan</div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembeli</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penjual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pesanan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#{{ $item->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $item->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->produk->nama_produk }}</div>
                                    <div class="text-sm text-gray-500">Jumlah: {{ $item->jumlah }} unit</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->produk->user->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($item->total, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status == 'menunggu')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            ⏳ Menunggu
                                        </span>
                                    @elseif($item->status == 'diproses')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            🔄 Diproses
                                        </span>
                                    @elseif($item->status == 'dikirim')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                            🚚 Dikirim
                                        </span>
                                    @elseif($item->status == 'selesai')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            ✅ Selesai
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            ❌ Dibatalkan
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex gap-2">
                                        @if($item->status == 'menunggu')
                                            <form action="{{ route('admin.pesanan.proses', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition text-xs">
                                                    Proses
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($item->status == 'dikirim')
                                            <form action="{{ route('admin.pesanan.selesai', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600 transition text-xs">
                                                    Selesai
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if(!in_array($item->status, ['selesai', 'dibatalkan']))
                                            <form action="{{ route('admin.pesanan.batal', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition text-xs">
                                                    Batal
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <button onclick="showDetail({{ $item->id }})" class="px-3 py-1 bg-purple-500 text-white rounded hover:bg-purple-600 transition text-xs">
                                            Detail
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            
                            <!-- Hidden detail row -->
                            <tr id="detail-{{ $item->id }}" class="hidden bg-gray-50">
                                <td colspan="8" class="px-6 py-4">
                                    <div class="bg-white rounded-lg p-4 shadow">
                                        <h3 class="font-bold text-lg mb-4">Detail Pesanan #{{ $item->id }}</h3>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-sm text-gray-600">Nama Penerima:</p>
                                                <p class="font-semibold">{{ $item->nama_penerima }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">No. HP:</p>
                                                <p class="font-semibold">{{ $item->no_hp }}</p>
                                            </div>
                                            <div class="col-span-2">
                                                <p class="text-sm text-gray-600">Alamat Pengiriman:</p>
                                                <p class="font-semibold">{{ $item->alamat }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Ekspedisi:</p>
                                                <p class="font-semibold uppercase">{{ $item->ekspedisi }}</p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600">Metode Pembayaran:</p>
                                                <p class="font-semibold">{{ $item->metode_pembayaran == 'transfer_bank' ? 'Transfer Bank' : 'COD' }}</p>
                                            </div>
                                            @if($item->resi)
                                                <div class="col-span-2">
                                                    <p class="text-sm text-gray-600">No. Resi:</p>
                                                    <p class="font-semibold text-purple-600">{{ $item->resi }}</p>
                                                </div>
                                            @endif
                                            @if($item->catatan_pembeli)
                                                <div class="col-span-2">
                                                    <p class="text-sm text-gray-600">Catatan Pembeli:</p>
                                                    <p class="font-semibold">{{ $item->catatan_pembeli }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                                    Belum ada pesanan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function showDetail(id) {
            const detailRow = document.getElementById('detail-' + id);
            detailRow.classList.toggle('hidden');
        }
    </script>
>>>>>>> 5853d8b8a2aaca5bd65654069268449123eb8540
</body>
</html>
