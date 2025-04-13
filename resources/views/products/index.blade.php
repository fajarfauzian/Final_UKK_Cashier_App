@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium mb-4 text-gray-800">Daftar Produk</h2>

        <!-- Header Section -->
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-center gap-4">
                @if (Auth::user()->role == 'admin')
                    <div class="flex flex-wrap items-center gap-2">
                        <select id="entries" class="text-sm p-2 rounded-lg border border-gray-300 focus:outline-none"
                            onchange="window.location.href='{{ route('products.index') }}?perPage=' + this.value + '&search={{ request('search') ?? '' }}'">
                            <option disabled>Showing {{ request('perPage') ?? 5 }}</option>
                            <option value="5"
                                {{ request('perPage') == 5 || request('perPage') == null ? 'selected' : '' }}>5</option>
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="20" {{ request('perPage') == 50 ? 'selected' : '' }}>20</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                @else
                    <div class="flex flex-wrap items-center gap-2">
                        <select id="entries" class="text-sm p-2 rounded-lg border border-gray-300 focus:outline-none"
                            onchange="window.location.href='{{ route('products.index') }}?perPage=' + this.value + '&search={{ request('search') ?? '' }}'">
                            <option disabled>Showing 12</option>
                            <option value="12" selected>12</option>
                            <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                            <option value="30" {{ request('perPage') == 30 ? 'selected' : '' }}>30</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                @endif
                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <form action="{{ route('products.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search"
                                    class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-{{ Auth::user()->role == 'admin' ? 'blue' : 'green' }}-500 focus:border-{{ Auth::user()->role == 'admin' ? 'blue' : 'green' }}-500"
                                    placeholder="Cari produk..." value="{{ request('search') }}">
                                <i class="absolute top-1/2 left-3 -translate-y-1/2 ri-search-line text-gray-400"></i>
                                <input type="hidden" name="perPage" value="{{ request('perPage') ?? 5 }}">
                            </div>
                        </form>
                    </div>
                </div>

                @if (Auth::user()->role == 'admin')
                    <a href="{{ route('products.create') }}"
                        class="px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition duration-200">
                        <i class="ri-add-line mr-1"></i>
                        Tambah Produk
                    </a>
                @endif
            </div>
        </div>

        @if (Auth::user()->role == 'admin')
            <!-- Admin: Table Layout -->
            <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">No.</th>
                            <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Nama Produk</th>
                            <th class="py-3 px-6 text-right text-sm font-medium text-gray-500">Harga</th>
                            <th class="py-3 px-6 text-right text-sm font-medium text-gray-500">Stok</th>
                            <th class="py-3 px-6 text-center text-sm font-medium text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr class="hover:bg-blue-50 border-b transition duration-200">
                                <td class="px-6 py-3 text-sm text-gray-900">{{ $product->id }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-3">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}" class="h-16 w-16 rounded-lg object-cover">
                                        @else
                                            <div class="h-10 w-10 rounded-lg bg-gray-100 border border-gray-200"></div>
                                        @endif
                                        <span class="text-sm text-gray-900">{{ $product->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3 text-sm text-right text-gray-900">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-3 text-sm text-right">{{ $product->stock }}</td>
                                <td class="px-6 py-3">
                                    <div class="flex justify-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-full" title="Edit">
                                            <i class="ri-edit-line text-md"></i>
                                        </a>
                                        <button
                                            onclick="openStockModal({{ $product->id }}, {{ $product->stock }}, '{{ $product->name }}')"
                                            class="p-1.5 text-green-600 hover:bg-green-100 rounded-full"
                                            title="Update Stock">
                                            <i class="ri-stack-line text-md"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-100 rounded-full"
                                                title="Delete" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="ri-delete-bin-line text-md"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">Tidak ada produk
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @else
            <!-- Petugas: Grid Card Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                @forelse ($products as $product)
                    <div
                        class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 hover:bg-green-50 transition duration-200 hover:scale-[1.02]">
                        <div class="mb-3">
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    class="h-32 w-full rounded-lg object-cover">
                            @else
                                <div
                                    class="h-32 w-full rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center">
                                    <i class="ri-image-line text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-sm font-medium text-gray-900 mb-2">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-700 mb-1">Harga: Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-gray-700">Stok: {{ $product->stock }}</p>
                    </div>
                @empty
                    <div class="col-span-full p-10 text-center text-sm text-gray-500">Tidak ada produk</div>
                @endforelse
            </div>
        @endif

        <!-- Pagination -->
        <div class="mt-4 flex flex-col sm:flex-row justify-between items-center">
            <p class="text-xs text-gray-700 mb-2 sm:mb-0">
                Menampilkan {{ $products->firstItem() ?? 0 }} sampai {{ $products->lastItem() ?? 0 }} dari
                {{ $products->total() }} Produk
            </p>
            <div class="flex gap-1">
                @if (!$products->onFirstPage())
                    <a href="{{ $products->previousPageUrl() }}"
                        class="w-8 h-8 flex items-center justify-center border rounded text-gray-500 hover:bg-{{ Auth::user()->role == 'admin' ? 'blue' : 'green' }}-50">
                        <i class="ri-arrow-left-s-line text-sm"></i>
                    </a>
                @endif

                @for ($i = max(1, $products->currentPage() - 1); $i <= min($products->lastPage(), $products->currentPage() + 1); $i++)
                    <a href="{{ $products->url($i) }}"
                        class="w-8 h-8 flex items-center justify-center border rounded text-sm {{ $i == $products->currentPage() ? 'bg-' . (Auth::user()->role == 'admin' ? 'blue' : 'green') . '-500 text-white' : 'text-gray-500 hover:bg-' . (Auth::user()->role == 'admin' ? 'blue' : 'green') . '-50' }}">
                        {{ $i }}
                    </a>
                @endfor

                @if ($products->hasMorePages())
                    <a href="{{ $products->nextPageUrl() }}"
                        class="w-8 h-8 flex items-center justify-center border rounded text-gray-500 hover:bg-{{ Auth::user()->role == 'admin' ? 'blue' : 'green' }}-50">
                        <i class="ri-arrow-right-s-line text-sm"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if (Auth::user()->role == 'admin')
        @include('products.update-stock')
    @endif
@endsection
