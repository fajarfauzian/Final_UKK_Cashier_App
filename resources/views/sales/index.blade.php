@extends('layouts.app')

@section('title', 'Daftar Transaksi Penjualan')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium mb-4 text-gray-800">Daftar Transaksi Penjualan</h2>

        <!-- Header Section -->
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-wrap items-center gap-3">
                    <select id="entries" class="text-sm p-2 rounded-lg border border-gray-300 focus:outline-none"
                        onchange="window.location.href='{{ route('sales.index') }}?per_page=' + this.value + '&search={{ request('search') }}'">
                        <option disabled selected>Showing {{ request('per_page') ?? 10 }}</option>
                        @foreach ([10, 20, 50, 100] as $num)
                            <option value="{{ $num }}" {{ request('per_page') == $num ? 'selected' : '' }}>
                                {{ $num }}
                            </option>
                        @endforeach
                    </select>
                    <a href="{{ route('sales.export') }}"
                        class="px-3 py-2 text-sm font-medium text-white bg-green-700 border border-gray-300 rounded-md hover:bg-green-800">
                        <i class="ri-file-excel-2-line mr-1"></i> Export Excel
                    </a>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <form action="{{ route('sales.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search"
                                    class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Cari transaksi..." value="{{ request('search') }}">
                                <i class="absolute top-1/2 left-3 -translate-y-1/2 ri-search-line text-gray-400"></i>
                                <input type="hidden" name="per_page" value="{{ request('per_page') ?? 10 }}">
                            </div>
                        </form>
                    </div>
                </div>

                <div class="flex gap-3">
                    @if (auth()->user()->role !== 'admin')
                        <a href="{{ route('sales.create') }}"
                            class="px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                            <i class="ri-add-line mr-1"></i> Tambah Transaksi
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">ID Transaksi</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Pelanggan</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Tanggal</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Total</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Dibuat Oleh</th>
                        <th class="py-3 px-6 text-center text-sm font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="px-6 py-2 text-sm text-gray-900">{{ $startNumber + $loop->index }}</td>
                            <td class="px-6 py-2 text-sm text-gray-900">
                                {{ $sale->is_member && $sale->customer_name ? $sale->customer_name : 'NON-MEMBER' }}
                            </td>
                            <td class="px-6 py-2 text-sm text-gray-900">{{ $sale->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-2 text-sm  text-gray-900">
                                Rp {{ number_format($sale->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-2 text-sm text-gray-900">{{ $sale->user?->name }}</td>
                            <td class="px-6 py-2">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('sales.pdf', $sale->id) }}"
                                        class="p-1.5 text-red-600 hover:bg-red-100 rounded-full" title="Unduh PDF">
                                        <i class="ri-file-pdf-line text-md"></i>
                                    </a>
                                    <button data-url="{{ route('sales.details', $sale->id) }}"
                                        class="show-details p-1.5 text-blue-600 hover:bg-blue-100 rounded-full"
                                        title="Detail">
                                        <i class="ri-eye-line text-md"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
            <p class="text-xs text-gray-700 mb-2 sm:mb-0">
                Menampilkan {{ $sales->firstItem() ?? 0 }} sampai {{ $sales->lastItem() ?? 0 }} dari
                {{ $sales->total() }} Transaksi
            </p>
            <div class="flex gap-1">
                @if (!$sales->onFirstPage())
                    <a href="{{ $sales->previousPageUrl() }}"
                        class="w-8 h-8 flex items-center justify-center border rounded text-gray-500 hover:bg-gray-50">
                        <i class="ri-arrow-left-s-line text-sm"></i>
                    </a>
                @endif

                @for ($i = max(1, $sales->currentPage() - 1); $i <= min($sales->lastPage(), $sales->currentPage() + 1); $i++)
                    <a href="{{ $sales->url($i) }}"
                        class="w-8 h-8 flex items-center justify-center border rounded text-sm {{ $i == $sales->currentPage() ? 'bg-blue-500 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                        {{ $i }}
                    </a>
                @endfor

                @if ($sales->hasMorePages())
                    <a href="{{ $sales->nextPageUrl() }}"
                        class="w-8 h-8 flex items-center justify-center border rounded text-gray-500 hover:bg-gray-50">
                        <i class="ri-arrow-right-s-line text-sm"></i>
                    </a>
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
