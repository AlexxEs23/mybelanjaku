<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approval Penjual - Admin UMKM Market</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <!-- Include Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <div class="ml-64 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Persetujuan Penjual</h1>
            <p class="text-gray-600">Kelola persetujuan pendaftaran penjual</p>
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
        <div class="mb-6">
            <div class="flex space-x-4 border-b border-gray-200">
                <button onclick="showTab('pending')" id="tab-pending" class="tab-button px-6 py-3 font-semibold text-orange-600 border-b-2 border-orange-600">
                    Menunggu ({{ $pendingSellers->count() }})
                </button>
                <button onclick="showTab('approved')" id="tab-approved" class="tab-button px-6 py-3 font-semibold text-gray-500 hover:text-gray-700">
                    Disetujui ({{ $approvedSellers->count() }})
                </button>
                <button onclick="showTab('rejected')" id="tab-rejected" class="tab-button px-6 py-3 font-semibold text-gray-500 hover:text-gray-700">
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
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($seller->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">{{ $seller->name }}</h3>
                                        <p class="text-sm text-gray-500">Mendaftar: {{ $seller->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-3 gap-4 mt-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">No. HP</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->no_hp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Alamat</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->alamat }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 ml-4">
                                <form method="POST" action="{{ route('admin.seller.approve', $seller->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition font-medium text-sm">
                                        ✓ Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.seller.reject', $seller->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition font-medium text-sm" onclick="return confirm('Yakin ingin menolak penjual ini?')">
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
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-green-400 to-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                        {{ strtoupper(substr($seller->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">{{ $seller->name }}</h3>
                                        <p class="text-sm text-gray-500">Disetujui: {{ $seller->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">No. HP</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->no_hp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Alamat</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->alamat }}</p>
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
                                        {{ strtoupper(substr($seller->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-800">{{ $seller->name }}</h3>
                                        <p class="text-sm text-gray-500">Ditolak: {{ $seller->updated_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="grid md:grid-cols-3 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Email</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->email }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">No. HP</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->no_hp }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Alamat</p>
                                        <p class="text-sm font-medium text-gray-700">{{ $seller->alamat }}</p>
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
