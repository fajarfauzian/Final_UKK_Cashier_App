@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="w-full pb-20">
    <div class="max-w-7xl mx-auto">
        @if (auth()->user()->role !== 'petugas')
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <span class="text-red-700 font-semibold">Akses ditolak untuk menambahkan penjualan.</span>
            </div>
        @else
            <form action="{{ route('sales.check-membership') }}" method="POST" class="space-y-6">
                @csrf
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4">
                        <ul class="list-disc pl-5 text-red-600">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4" id="product-container">
                    @foreach ($products as $index => $product)
                        <div class="product-item bg-white rounded-lg shadow" @if ($index >= 10) style="display: none;" @endif>
                            <img src="{{ asset('storage/' . $product->image) }}" class="w-full h-40 object-contain p-2" alt="{{ $product->name }}">
                            <div class="p-3">
                                <h6 class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</h6>
                                <p class="text-green-600 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                <p class="text-gray-600 text-xs">Stok: {{ $product->stock }}</p>
                                <input type="hidden" name="products[]" value="{{ $product->id }}">
                                <input type="hidden" class="product-price" value="{{ $product->price }}">
                                <div class="flex items-center mt-2 gap-2">
                                    <button type="button" class="w-6 h-6 bg-gray-100 rounded-full" onclick="changeQuantity(this, -1)">-</button>
                                    <input type="number" name="quantities[]" class="quantity-input w-12 text-center border rounded py-1 px-2" 
                                           value="0" min="0" max="{{ $product->stock }}" onchange="updateTotals(this)" required>
                                    <button type="button" class="w-6 h-6 bg-gray-100 rounded-full" onclick="changeQuantity(this, 1)">+</button>
                                </div>
                                <div class="mt-2 border-t pt-2">
                                    <span class="text-sm text-gray-600">Subtotal: </span>
                                    <span class="subtotal-display text-sm font-bold text-blue-600">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if (count($products) > 10)
                    <div class="text-center" id="show-more-container">
                        <button type="button" id="show-more-btn" class="border text-gray-700 px-4 py-1 rounded-full hover:bg-gray-50">
                            Tampilkan Lebih Banyak
                        </button>
                    </div>
                @endif

                <div class="fixed bottom-0 left-0 w-full p-4 bg-white border-t z-40">
                    <div class="max-w-7xl mx-auto flex justify-between items-center">
                        <div>
                            <span class="text-sm text-gray-600">Produk: <span id="selected-products-count">0</span></span>
                            <span class="text-sm text-gray-600 ml-4">Total: <span id="total-price" class="text-blue-600">Rp 0</span></span>
                        </div>
                        <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-full hover:bg-blue-700">
                            Lanjutkan
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