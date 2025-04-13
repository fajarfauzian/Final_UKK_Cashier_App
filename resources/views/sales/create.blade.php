@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
    <div class="w-full pb-20">
        @if (auth()->user()->role !== 'petugas')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <span class="text-red-700 font-semibold">Akses ditolak untuk menambahkan penjualan.</span>
            </div>
        @else
            <h1 class="text-2xl font-semibold text-gray-800 mb-6">Tambah Penjualan</h1>

            <form action="{{ route('sales.check-membership') }}" method="POST">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <ul class="list-disc pl-5 text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4" id="product-container">
                    @foreach ($products as $index => $product)
                        <div class="product-item bg-white rounded-lg border border-gray-200 overflow-hidden transition-shadow hover:shadow-md"
                            @if ($index >= 10) style="display: none;" @endif>
                            <div class="relative">
                                <img src="{{ asset('storage/' . $product->image) }}"
                                    class="w-full h-40 object-contain bg-gray-50 p-2" alt="{{ $product->name }}">
                                <div class="absolute top-2 right-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                    Stok: {{ $product->stock }}
                                </div>
                            </div>
                            <div class="p-3">
                                <h6 class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</h6>
                                <p class="text-green-600 font-bold mt-1">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>

                                <div class="mt-1">
                                    <span class="text-xs text-gray-500">Terjual: {{ $product->sold ?? 0 }}</span>
                                </div>

                                <input type="hidden" name="products[]" value="{{ $product->id }}">
                                <input type="hidden" class="product-price" value="{{ $product->price }}">

                                <div class="flex items-center justify-between mt-3">
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200"
                                        onclick="changeQuantity(this, -1)">-</button>
                                    <input type="number" name="quantities[]"
                                        class="quantity-input w-12 text-center border border-gray-300 rounded-md py-1 appearance-none [&::-webkit-outer-spin-button]:m-0 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:m-0 [&::-webkit-inner-spin-button]:appearance-none"
                                        value="0" min="0" max="{{ $product->stock }}"
                                        onchange="updateTotals(this)" required>
                                    <button type="button"
                                        class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-md text-gray-700 hover:bg-gray-200"
                                        onclick="changeQuantity(this, 1)">+</button>
                                </div>

                                <div class="mt-3 pt-2 border-t border-gray-100">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs text-gray-600">Subtotal:</span>
                                        <span class="subtotal-display text-sm font-medium text-blue-600">Rp 0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($products) > 10)
                    <div class="text-center mt-6 mb-20" id="show-more-container">
                        <button type="button" id="show-more-btn"
                            class="border border-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-50">
                            Tampilkan Lebih Banyak
                        </button>
                    </div>
                @endif

                <div class="fixed bottom-0 left-0 w-full bg-white border-t border-gray-200 p-3 shadow-md z-50">
                    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex gap-8">
                            <div>
                                <span class="text-sm text-gray-600">Produk:</span>
                                <span id="selected-products-count" class="ml-1 font-medium">0</span>
                            </div>
                            <div>
                                <span class="text-sm text-gray-600">Total:</span>
                                <span id="total-price" class="ml-1 font-medium text-blue-600">Rp 0</span>
                            </div>
                        </div>
                        <button type="submit"
                            class="bg-blue-600 text-white w-full sm:w-auto py-2 px-5 rounded-md hover:bg-blue-700">
                            Lanjutkan Pembayaran
                        </button>
                    </div>
                </div>
            </form>
        @endif
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/create-sales.js') }}"></script>
@endsection
