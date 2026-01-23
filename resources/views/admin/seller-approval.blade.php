<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Penjual - Admin CheckoutAja.com</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 p-4 md:p-6 lg:p-8 pt-20 lg:pt-8">
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Persetujuan Penjual</h1>
            <p class="text-sm md:text-base text-gray-600">Kelola persetujuan pendaftaran penjual</p>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-green-500 mr-2 text-xl">✓</span>
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex items-center">
                    <span class="text-red-500 mr-2 text-xl">✕</span>
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Tabs -->
        <div class="mb-6 overflow-x-auto">
            <div class="flex space-x-2 md:space-x-4 border-b border-gray-200 min-w-max">
                <button onclick="showTab('pending')" id="tab-pending" class="tab-button px-4 md:px-6 py-3 font-semibold text-sm md:text-base text-orange-600 border-b-2 border-orange-600 whitespace-nowrap">
                    Menunggu ({{ $pendingSellers->count() }})
                </button>
                <button onclick="showTab('approved')" id="tab-approved" class="tab-button px-4 md:px-6 py-3 font-semibold text-sm md:text-base text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    Disetujui ({{ $approvedSellers->count() }})
                </button>
                <button onclick="showTab('rejected')" id="tab-rejected" class="tab-button px-4 md:px-6 py-3 font-semibold text-sm md:text-base text-gray-500 hover:text-gray-700 whitespace-nowrap">
                    Ditolak ({{ $rejectedSellers->count() }})
                </button>
            </div>
        </div>

        <!-- Pending Sellers -->
        <div id="content-pending" class="tab-content">
            @if($pendingSellers->isEmpty())
                <div class="bg-white rounded-xl p-12 text-center">
                    <div class="text-6xl mb-4">📭</div>
                    <p class="text-gray-500 text-lg">Tidak ada penjual yang menunggu persetujuan</p>
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($pendingSellers as $seller)
                    <div class="bg-white rounded-xl shadow-md p-4 md:p-6">
                        <div class="flex flex-col md:flex-row items-start justify-between gap-4">
                            <div class="flex-1 w-full">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-lg md:text-xl flex-shrink-0">
                                        {{ strtoupper(substr($seller->nama_umkm, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base md:text-lg font-bold text-gray-800 truncate">{{ $seller->nama_umkm }}</h3>
                                        <p class="text-xs md:text-sm text-gray-500">Mendaftar: {{ $seller->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4 mt-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Pemilik</p>
                                        <p class="text-sm font-medium text-gray-700 break-words">{{ $seller->nama_pemilik }} ({{ $seller->user->name }})</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <p class="text-sm font-medium text-gray-700 break-all">{{ $seller->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Kategori</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">No. HP</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->no_hp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Wilayah</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->wilayah }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tahun Berdiri</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->tahun_berdiri }}</p>
                                    </div>
                                </div>
                                @if($seller->deskripsi_umkm)
                                <div class="mt-4">
                                    <p class="text-xs text-gray-500 mb-1">Deskripsi</p>
                                    <p class="text-sm text-gray-700">{{ $seller->deskripsi_umkm }}</p>
                                </div>
                                @endif
                            </div>
                            <div class="flex flex-row md:flex-col gap-2 w-full md:w-auto">
                                <form method="POST" action="{{ route('admin.seller.approve', $seller->id) }}" class="flex-1 md:flex-none">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium text-sm whitespace-nowrap">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.seller.reject', $seller->id) }}" class="flex-1 md:flex-none">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium text-sm whitespace-nowrap" onclick="return confirm('Yakin ingin menolak penjual ini?')">
                                        ✕ Tolak
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Approved Sellers -->
        <div id="content-approved" class="tab-content hidden">
            @if($approvedSellers->isEmpty())
                <div class="bg-white rounded-xl p-12 text-center">
                    <div class="text-6xl mb-4">✅</div>
                    <p class="text-gray-500 text-lg">Belum ada penjual yang disetujui</p>
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($approvedSellers as $seller)
                    <div class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-green-500">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-lg md:text-xl flex-shrink-0">
                                        {{ strtoupper(substr($seller->nama_umkm, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-base md:text-lg font-bold text-gray-800 truncate">{{ $seller->nama_umkm }}</h3>
                                        <p class="text-xs md:text-sm text-gray-500">Disetujui: {{ $seller->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Pemilik</p>
                                        <p class="text-sm font-medium text-gray-700 break-words">{{ $seller->nama_pemilik }} ({{ $seller->user->name }})</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <p class="text-sm font-medium text-gray-700 break-all">{{ $seller->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Kategori</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">No. HP</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->no_hp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Wilayah</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->wilayah }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tahun Berdiri</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->tahun_berdiri }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Rejected Sellers -->
        <div id="content-rejected" class="tab-content hidden">
            @if($rejectedSellers->isEmpty())
                <div class="bg-white rounded-xl p-12 text-center">
                    <div class="text-6xl mb-4">❌</div>
                    <p class="text-gray-500 text-lg">Tidak ada penjual yang ditolak</p>
                </div>
            @else
                <div class="grid gap-4">
                    @foreach($rejectedSellers as $seller)
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-red-400 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($seller->nama_umkm, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">{{ $seller->nama_umkm }}</h3>
                                        <p class="text-sm text-gray-500">Ditolak: {{ $seller->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Pemilik</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->nama_pemilik }} ({{ $seller->user->name }})</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->user->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Kategori</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">No. HP</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->no_hp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Wilayah</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->wilayah }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Tahun Berdiri</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->tahun_berdiri }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <script>
        function showTab(tab) {
            // Hide all content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            
            // Remove active state from all tabs
            document.querySelectorAll('.tab-button').forEach(button => {
                button.classList.remove('text-orange-600', 'border-b-2', 'border-orange-600');
                button.classList.add('text-gray-500');
            });
            
            // Show selected content
            document.getElementById('content-' + tab).classList.remove('hidden');
            
            // Add active state to selected tab
            const activeTab = document.getElementById('tab-' + tab);
            activeTab.classList.remove('text-gray-500');
            activeTab.classList.add('text-orange-600', 'border-b-2', 'border-orange-600');
        }
    </script>
</body>
</html>
