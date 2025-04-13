@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-800 mb-6">Dashboard</h2>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6 border border-gray-100">
            <h3 class="text-xl font-bold text-gray-900 mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
            <p class="text-gray-500 mb-4">Berikut adalah data dashboard Anda.</p>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                @if (auth()->user()->role === 'petugas')
                    <!-- Widget Petugas -->
                    <div class="bg-white rounded-lg shadow-md p-5 flex items-center transition-transform hover:scale-[1.02] border border-gray-100">
                        <div class="bg-green-100 rounded-full p-3 mr-4">
                            <i class="ri-shopping-cart-line text-green-600 text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-700">Total Penjualan Hari Ini</h4>
                            <p class="text-3xl font-bold text-green-600">{{ $dailySales ?? 0 }}</p>
                            <span class="text-xs text-gray-400">{{ now()->format('d M Y H:i') }}</span>
                        </div>
                    </div>
                @elseif (auth()->user()->role === 'admin')
                    <!-- Bar Chart -->
                    <div class="lg:col-span-2 bg-white rounded-lg shadow-md p-5 border border-gray-100">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Penjualan 2 Minggu Terakhir</h4>
                        <div class="h-80"><canvas id="salesChart"></canvas></div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="bg-white rounded-lg shadow-md p-5 border border-gray-100">
                        <h4 class="text-lg font-semibold text-gray-800 mb-4">Persentase Produk</h4>
                        <div class="h-80"><canvas id="productChart"></canvas></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @if (auth()->user()->role === 'admin')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Data
            const sales = @json($salesData ?? []);
            const products = @json($productSales ?? []);

            // Bar Chart
            const salesCtx = document.getElementById('salesChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'bar',
                data: {
                    labels: sales.map(item => item.date),
                    datasets: [{
                        label: 'Penjualan',
                        data: sales.map(item => item.total),
                        backgroundColor: (ctx) => {
                            const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 300);
                            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.8)');
                            gradient.addColorStop(1, 'rgba(59, 130, 246, 0.3)');
                            return gradient;
                        },
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1,
                        borderRadius: 8,
                        barThickness: 20,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { color: '#6B7280', font: { size: 12 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#6B7280', font: { size: 12 } }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.9)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: (context) => `Rp ${context.parsed.y.toLocaleString('id-ID')}`
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });

            // Pie Chart
            const productCtx = document.getElementById('productChart').getContext('2d');
            new Chart(productCtx, {
                type: 'pie',
                data: {
                    labels: products.map(item => item.name),
                    datasets: [{
                        data: products.map(item => item.percentage),
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(139, 92, 246, 0.8)',
                            'rgba(236, 72, 153, 0.8)'
                        ],
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#6B7280',
                                font: { size: 12 },
                                padding: 20,
                                boxWidth: 12
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(31, 41, 55, 0.9)',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: (context) => `${context.label}: ${context.parsed.toFixed(1)}%`
                            }
                        }
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeOutQuart'
                    }
                }
            });
        </script>
    @endif
@endsection