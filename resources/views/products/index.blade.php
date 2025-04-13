@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium mb-6 text-gray-800">Daftar Produk</h2>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:justify-between mb-6 gap-4">
            <select id="entries" class="p-2 rounded-lg border-gray-200"
                onchange="window.location.href='{{ route('products.index') }}?perPage=' + this.value + '&search={{ request('search') ?? '' }}'">
                <option value="5" {{ request('perPage') == 5 || !request('perPage') ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            </select>

            <div class="flex gap-3">
                <form action="{{ route('products.index') }}" method="GET" class="flex-grow">
                    <input type="text" name="search" class="p-2 rounded border" placeholder="Cari produk..."
                        value="{{ request('search') }}">
                    <input type="hidden" name="perPage" value="{{ request('perPage') ?? 10 }}">
                </form>
                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('products.create') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Tambah Produk
                    </a>
                @endif
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow-md overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                        <th class="py-3 px-4 text-left text-xs font-medium text-gray-500 uppercase">Nama Produk</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="py-3 px-4 text-right text-xs font-medium text-gray-500 uppercase">Stok</th>
                        <th class="py-3 px-4 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($products as $index => $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-900">
                                {{ $products->firstItem() + $loop->index }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="h-12 w-12 rounded-lg object-cover">
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gray-100 border border-gray-200"></div>
                                    @endif
                                    <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-900">Rp
                                {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ $product->stock }}</td>
                            <td class="px-4 py-3 text-center">
                                @if (Auth::user()->role == 'admin')
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="p-1 text-blue-600 hover:bg-blue-100 rounded">
                                            Edit
                                        </a>
                                        <button
                                            onclick="openStockModal({{ $product->id }}, {{ $product->stock }}, '{{ $product->name }}')"
                                            class="p-1.5 text-green-600 hover:bg-green-100 rounded-full"
                                            title="Update Stock">
                                            Update
                                        </button>
                                        <button onclick="openDeleteModal({{ $product->id }}, '{{ $product->name }}')"
                                            class="p-1.5 text-red-600 hover:bg-red-100 rounded-full" title="Delete Product">
                                            Delete
                                        </button>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">Tidak ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-3 flex flex-col sm:flex-row justify-between items-center">
            <p class="text-sm text-gray-700 mb-2 sm:mb-0">
                Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }}
                results
            </p>
            <div class="flex gap-1">
                @if (!$products->onFirstPage())
                    <a href="{{ $products->previousPageUrl() }}"
                        class="p-2 border rounded text-gray-500 hover:bg-gray-50">Prev</a>
                @endif
                @for ($i = max(1, $products->currentPage() - 1); $i <= min($products->lastPage(), $products->currentPage() + 1); $i++)
                    <a href="{{ $products->url($i) }}"
                        class="p-2 border rounded {{ $i == $products->currentPage() ? 'bg-blue-500 text-white' : 'text-gray-500 hover:bg-gray-50' }}">
                        {{ $i }}
                    </a>
                @endfor
                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                        class="p-2 border rounded text-gray-500 hover:bg-gray-50">Next</a>
                @endif
            </div>
        </div>
    </div>
    @if (Auth::user()->role == 'admin')
        @include('components.update-stock')
        @include('components.delete')
    @endif
@endsection
