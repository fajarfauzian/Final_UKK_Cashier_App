@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full">
    <h2 class="text-2xl font-medium mb-4">Dashboard</h2>
    
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="text-lg font-bold mb-2">Selamat datang, {{ Auth::user()->name }}!</h3>
        <p class="text-gray-600 mb-4">Berikut adalah data dashboard kami!</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if (auth()->user()->role === 'petugas')
                <!-- Widget Petugas -->
                <div class="bg-white rounded-lg shadow p-4 flex items-center">
                    <div class="bg-green-100 rounded-full p-2 mr-3">
                        <i class="ri-shopping-cart-line text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-700">Total Penjualan Hari Ini</h4>
                        <p class="text-2xl font-bold">{{ $dailySales ?? 0 }}</p>
                        <span class="text-xs text-gray-500">{{ now()->format('d M Y H:i') }}</span>
                    </div>
                </div>
            @elseif (auth()->user()->role === 'admin')
                <!-- Charts Admin -->
                <div class="md:col-span-2 rounded-lg shadow p-4">
                    <h4 class="text-sm font-bold mb-2">Penjualan 2 Minggu Terakhir</h4>
                    <div class="h-60"><canvas id="salesChart"></canvas></div>
                </div>

                <div class="rounded-lg shadow p-4">
                    <h4 class="text-sm font-bold mb-2">Persentase Produk</h4>
                    <div class="h-60"><canvas id="productChart"></canvas></div>
                </div>
            @else
                <!-- Widget Default -->
                <div class="bg-white rounded-lg shadow p-4 flex items-center">
                    <div class="bg-blue-100 rounded-full p-2 mr-3">
                        <i class="ri-archive-line text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold">Produk</h4>
                        <p class="text-2xl font-bold">{{ $totalProducts ?? 150 }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-4 flex items-center">
                    <div class="bg-green-100 rounded-full p-2 mr-3">
                        <i class="ri-shopping-cart-line text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold">Penjualan</h4>
                        <p class="text-2xl font-bold">{{ $totalSales ?? 320 }}</p>
                    </div>
                </div>

                @if (auth()->user()->role === 'admin')
                <div class="bg-white rounded-lg shadow p-4 flex items-center">
                    <div class="bg-purple-100 rounded-full p-2 mr-3">
                        <i class="ri-team-line text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold">Pengguna</h4>
                        <p class="text-2xl font-bold">{{ $totalUsers ?? 25 }}</p>
                    </div>
                </div>
                @endif
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
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: sales.map(item => item.date),
                datasets: [{
                    label: 'Penjualan',
                    data: sales.map(item => item.total),
                    backgroundColor: 'rgba(34, 197, 94, 0.5)',
                    borderColor: 'rgba(34, 197, 94, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // Pie Chart
        new Chart(document.getElementById('productChart'), {
            type: 'pie',
            data: {
                labels: products.map(item => item.name),
                datasets: [{
                    data: products.map(item => item.percentage),
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(250, 204, 21, 0.7)',
                        'rgba(20, 184, 166, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
    @endif
@endsection