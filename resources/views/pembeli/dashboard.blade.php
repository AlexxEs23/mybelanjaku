@extends('layouts.dashboard')

@section('title', 'Dashboard Pembeli - CheckoutAja')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">👋 Halo, {{ Auth::user()->name ?? 'Pembeli' }}!</h1>
                <p class="text-gray-600">Selamat datang di dashboard Anda. Kelola pesanan dan keranjang belanja Anda dengan mudah.</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-sm font-semibold text-purple-600">🕐</span>
                    <span id="realtime-clock" class="text-sm font-semibold text-purple-600"></span>
                </div>
            </div>
            <a href="{{ route('home') }}" class="px-6 py-3 bg-white border-2 border-purple-600 text-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition flex items-center gap-2">
                <span>🏠</span>
                <span>Kembali ke Beranda</span>
            </a>
        </div>
    </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Pesanan -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-700
                rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="text-4xl">📦</div>
            <span class="bg-purple-800 rounded-full px-3 py-1 text-xs font-semibold">
                Total
            </span>
        </div>
        <h3 class="text-black text-3xl font-bold mb-1">{{ $totalOrders }}</h3>
        <p class="text-black font-bold text-sm">Total Pesanan</p>
    </div>

    <!-- Pesanan Diproses -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-700
                rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="text-4xl">🚚</div>
            <span class="bg-purple-800 rounded-full px-3 py-1 text-xs font-semibold">
                Proses
            </span>
        </div>
        <h3 class="text-black text-3xl font-bold mb-1">{{ $activeOrders }}</h3>
        <p class="text-black font-bold text-sm">Sedang Diproses</p>
    </div>

    <!-- Pesanan Selesai -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-700
                rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="text-4xl">✅</div>
            <span class="bg-purple-800 rounded-full px-3 py-1 text-xs font-semibold">
                Selesai
            </span>
        </div>
        <h3 class="text-black text-3xl font-bold mb-1">{{ $completedOrders }}</h3>
        <p class="text-black font-bold text-sm">Pesanan Selesai</p>
    </div>

    <!-- Total Belanja -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-700
                rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between mb-4">
            <div class="text-4xl">💰</div>
            <span class="bg-purple-800 rounded-full px-3 py-1 text-xs font-semibold">
                Total
            </span>
        </div>
        <h3 class="text-black text-2xl font-bold mb-1">
            Rp {{ number_format($totalSpent / 1000, 1) }}jt
        </h3>
        <p class="text-black font-bold text-sm">Total Belanja</p>
    </div>

</div>



        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Charts Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Monthly Spending Chart -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-md p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span>📊</span> Pengeluaran Bulanan
                        </h2>
                        <div style="position: relative; height: 250px;">
                            <canvas id="spendingChart"></canvas>
                        </div>
                    </div>

                    <!-- Order Status Chart -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl shadow-md p-6">
                        <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                            <span>📈</span> Status Pesanan
                        </h2>
                        <div style="position: relative; height: 250px;">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>⚡</span> Aksi Cepat
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
                            <span class="text-2xl mb-2">🛒</span>
                            <span class="text-sm font-semibold text-gray-700">Belanja</span>
                        </a>
                        <a href="{{ route('pembeli.pesanan.index') }}" class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 rounded-lg hover:border-purple-600 hover:bg-purple-50 transition">
                            <span class="text-2xl mb-2">📦</span>
                            <span class="text-sm font-semibold text-gray-700">Pesanan</span>
                        </a>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- Profile Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-purple-700 rounded-full mx-auto mb-3 flex items-center justify-center text-4xl">
                            👤
                        </div>
                        <h3 class="font-bold text-black text-lg mb-1">{{ Auth::user()->name }}</h3>
                        <p class="text-sm text-black mb-4">{{ Auth::user()->email }}</p>
                        <a href="{{ route('profile.show') }}" class="inline-block px-4 py-2 bg-purple-700 text-white rounded-lg text-sm font-semibold hover:bg-purple-800 transition">
                            Edit Profil
                        </a>
                    </div>
                </div>

                <!-- Help Center -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <span>❓</span> Bantuan
                    </h2>
                    
                    <div class="space-y-3">
                        <a href="{{ route('home') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 transition">
                            <span>🛒</span> Belanja Produk
                        </a>
                        <a href="{{ route('pembeli.pesanan.index') }}" class="flex items-center gap-3 text-sm text-gray-700 hover:text-purple-600 transition">
                            <span>📦</span> Lihat Pesanan Saya
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Real-time Clock
        function updateClock() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            
            const dayName = days[now.getDay()];
            const day = now.getDate();
            const month = months[now.getMonth()];
            const year = now.getFullYear();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            
            const timeString = `${dayName}, ${day} ${month} ${year} - ${hours}:${minutes}:${seconds} WIB`;
            
            const clockElement = document.getElementById('realtime-clock');
            if (clockElement) {
                clockElement.textContent = timeString;
            }
        }
        
        // Update clock immediately and then every second
        updateClock();
        setInterval(updateClock, 1000);
        
        // Monthly Spending Line Chart
        const spendingCtx = document.getElementById('spendingChart');
        if (spendingCtx) {
            new Chart(spendingCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: @json($monthLabels),
                    datasets: [{
                        label: 'Pengeluaran (Rp)',
                        data: @json($monthlySpending),
                        borderColor: 'rgb(147, 51, 234)',
                        backgroundColor: 'rgba(147, 51, 234, 0.1)',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(147, 51, 234)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000) + 'k';
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Order Status Doughnut Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Menunggu', 'Diproses', 'Dikirim', 'Selesai', 'Dibatalkan'],
                    datasets: [{
                        data: [
                            @json($statusData['menunggu']),
                            @json($statusData['diproses']),
                            @json($statusData['dikirim']),
                            @json($statusData['selesai']),
                            @json($statusData['dibatalkan'])
                        ],
                        backgroundColor: [
                            'rgb(234, 179, 8)',  // yellow
                            'rgb(59, 130, 246)',  // blue
                            'rgb(147, 51, 234)',  // purple
                            'rgb(34, 197, 94)',   // green
                            'rgb(239, 68, 68)'    // red
                        ],
                        borderWidth: 3,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 10,
                                font: {
                                    size: 11
                                },
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const value = data.datasets[0].data[i];
                                            return {
                                                text: `${label} (${value})`,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
