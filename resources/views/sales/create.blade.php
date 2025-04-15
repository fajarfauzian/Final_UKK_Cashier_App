@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
    <div class="container w-full pb-28">
        <div class="max-w-7xl mx-auto">
            <!-- Peringatan Akses Ditolak -->
            @if (auth()->user()->role !== 'petugas')
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-center mb-6">
                    <span class="iconify h-6 w-6 text-red-500 mr-3" data-icon="ri:error-warning-fill" data-width="24"
                        data-height="24"></span>
                    <div class="text-red-700 font-semibold">
                        Anda tidak memiliki akses untuk menambahkan penjualan.
                    </div>
                </div>
            @else
                <!-- Form Section -->
                <form action="{{ route('sales.check-membership') }}" method="POST" class="space-y-6" novalidate>
                    @csrf

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                            <h3 class="text-red-700 font-semibold mb-2">Peringatan!</h3>
                            <ul class="list-disc pl-5 text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Product Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4"
                        id="product-container">
                        @foreach ($products as $index => $product)
                            <div class="product-item" @if ($index >= 10) style="display: none;" @endif>
                                <div
                                    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden h-full border border-gray-100">
                                    <!-- Product Image -->
                                    <div class="relative">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="w-full h-40 object-contain p-3 bg-gray-50" alt="{{ $product->name }}">
                                        @if ($product->stock <= 5)
                                            <span
                                                class="absolute top-2 right-2 bg-yellow-400 text-yellow-900 text-xs font-medium px-2 py-1 rounded-full">
                                                Stok Rendah
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Card Body -->
                                    <div class="p-3">
                                        <h6 class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</h6>
                                        <p class="text-green-600 font-semibold text-base mt-1">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</p>
                                        <p class="text-gray-400 text-xs line-through">Rp
                                            {{ number_format($product->price + 2000, 0, ',', '.') }}</p>

                                        <div class="flex justify-between items-center mt-2 text-xs text-gray-600">
                                            <p>Stok: {{ $product->stock }}</p>
                                            @php
                                                $sold = $product->sold ?? 0;
                                                $soldDisplay =
                                                    $sold >= 1000 ? number_format($sold / 1000, 1) . 'rb+' : $sold;
                                            @endphp
                                            <p>{{ $soldDisplay }} terjual</p>
                                        </div>

                                        <input type="hidden" name="products[]" value="{{ $product->id }}">
                                        <input type="hidden" class="product-price" value="{{ $product->price }}">

                                        <!-- Quantity Input -->
                                        <div class="flex items-center justify-between mt-3">
                                            <button type="button"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"
                                                onclick="changeQuantity(this, -1)">
                                                <i class="ri-subtract-line text-gray-600 text-base"></i>
                                            </button>
                                            <input type="number" name="quantities[]"
                                                class="quantity-input w-12 text-center border rounded-lg py-1 px-1 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('quantities.' . $index) border-red-500 @enderror"
                                                value="0" min="0" max="{{ $product->stock }}"
                                                onchange="updateTotals(this)" required>
                                            <button type="button"
                                                class="w-8 h-8 flex items-center justify-center bg-gray-100 rounded-full hover:bg-gray-200 transition-colors"
                                                onclick="changeQuantity(this, 1)">
                                                <i class="ri-add-line text-gray-600 text-base"></i>
                                            </button>
                                        </div>

                                        <!-- Subtotal -->
                                        <div class="mt-3 pt-2 border-t border-gray-100">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs text-gray-600">Sub Total:</span>
                                                <span class="subtotal-display text-sm font-bold text-blue-600">Rp 0</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Show More Button -->
                    @if (count($products) > 10)
                        <div class="text-center mt-4" id="show-more-container">
                            <button type="button"
                                class="bg-white border border-gray-300 text-gray-700 px-6 py-2 rounded-full hover:bg-gray-50 transition-colors duration-200 text-sm"
                                id="show-more-btn">
                                Tampilkan Lebih Banyak
                            </button>
                        </div>
                    @endif

                    <!-- Fixed Footer -->
                    <div id="fixed-transaction-footer"
                        class="fixed bottom-0 left-0 p-4 bg-white border-t border-gray-200 shadow-md z-40 w-full lg:w-[calc(100%-16rem)] lg:left-64">
                        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-3">
                            <div class="flex flex-col sm:flex-row sm:gap-6">
                                <span class="text-sm text-gray-600">Total Produk: <span id="selected-products-count"
                                        class="font-semibold">0</span></span>
                                <span class="text-sm text-gray-600">Total Harga: <span id="total-price"
                                        class="font-semibold text-blue-600">Rp 0</span></span>
                            </div>
                            <button type="submit"
                                class="w-full sm:w-auto bg-blue-600 text-white py-2 px-6 rounded-full hover:bg-blue-700 transition-colors duration-200 font-medium text-sm">
                                Lanjutkan Transaksi
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/create-sales.js') }}"></script>
@endsection
