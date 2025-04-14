@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium mb-6 text-gray-800">Daftar Transaksi Penjualan</h2>

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between mb-6 gap-4">
            <div class="flex gap-4">
                <a href="{{ route('sales.export') }}"
                    class="bg-emerald-600 text-white px-4 py-2 rounded hover:bg-emerald-700">Export Excel</a>
                <select id="entries" class="p-2 rounded border-gray-200"
                    onchange="window.location.href='?per_page='+this.value+'&search={{ request('search') }}'">
                    @foreach ([10, 20, 50, 100] as $num)
                        <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>
                            {{ $num }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('sales.index') }}" method="GET">
                    <input type="text" name="search" class="p-2 rounded border" placeholder="Cari transaksi..."
                        value="{{ request('search') }}">
                </form>
                @if (auth()->user()->role !== 'admin')
                    <a href="{{ route('sales.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Tambah Transaksi</a>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-gray-500 uppercase">No.</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Pelanggan</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Tanggal</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Total</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Kembalian</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Dibuat Oleh</th>
                        <th class="p-3 text-center text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($sales as $sale)
                        <tr>
                            <td class="p-3">{{ $startNumber + $loop->index }}</td>
                            <td class="p-3">
                                {{ $sale->is_member && $sale->customer_name ? $sale->customer_name : 'NON-MEMBER' }}</td>
                            <td class="p-3">{{ $sale->created_at->format('d-m-Y') }}</td>
                            <td class="p-3 text-left">Rp {{ number_format($sale->total_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-left">Rp {{ number_format($sale->change, 0, ',', '.') }}</td>
                            <td class="p-3">{{ $sale->user?->name }}</td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('sales.pdf', $sale->id) }}"
                                        class="p-1 text-red-600 hover:bg-red-100 rounded" title="Unduh PDF">PDF</a>
                                    <button data-url="{{ route('sales.details', $sale->id) }}"
                                        class="show-details p-1 text-blue-600 hover:bg-blue-100 rounded"
                                        title="Detail">Detail</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-gray-500">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 flex flex-col sm:flex-row justify-between items-center border-t p-4">
            <p class="text-sm text-gray-700 mb-2 sm:mb-0">Showing {{ $sales->firstItem() ?? 0 }} to
                {{ $sales->lastItem() ?? 0 }} of {{ $sales->total() }} results</p>
            <div class="flex gap-1">
                @if (!$sales->onFirstPage())
                    <a href="{{ $sales->previousPageUrl() }}"
                        class="p-2 border rounded text-gray-500 hover:bg-gray-50">Prev</a>
                @endif
                @for ($i = max(1, $sales->currentPage() - 1); $i <= min($sales->lastPage(), $sales->currentPage() + 1); $i++)
                    <a href="{{ $sales->url($i) }}"
                        class="p-2 border rounded {{ $i == $sales->currentPage() ? 'bg-blue-500 text-white' : 'text-gray-500 hover:bg-gray-50' }}">{{ $i }}</a>
                @endfor
                @if ($sales->hasMorePages())
                    <a href="{{ $sales->nextPageUrl() }}"
                        class="p-2 border rounded text-gray-500 hover:bg-gray-50">Next</a>
                @endif
            </div>
        </div>

        <!-- Sale Detail Modal -->
        <div class="fixed inset-0 z-50 hidden" id="saleDetailModal">
            <div class="absolute inset-0 bg-gray-500/75"
                onclick="document.getElementById('saleDetailModal').classList.add('hidden')">
                <div class="flex items-center justify-center min-h-screen p-4">
                    <div class="bg-white rounded-lg shadow w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                        <div id="modal-content"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/index-sales.js') }}"></script>
@endsection