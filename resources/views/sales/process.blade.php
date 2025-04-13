@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-2xl font-medium text-gray-900">Informasi Pelanggan</h2>
            <a href="{{ route('sales.create') }}" 
                class="flex items-center bg-gray-100 text-gray-700 py-2 mt-3 px-4 rounded-lg hover:bg-gray-200 font-medium text-center transition-colors duration-200 border border-gray-200">
                <i class="ri-arrow-left-line h-5 w-5 mr-2 text-gray-600"></i>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-600 p-6 mb-8 rounded-lg shadow-sm">
                <ul class="list-disc pl-6 text-red-700">
                    @foreach ($errors->all() as $error)
                        <li class="text-sm">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg p-8 border border-gray-100">
            <form action="{{ route('sales.process-transaction') }}" method="POST" class="space-y-8">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                <input type="hidden" name="is_member" value="1">
                <input type="hidden" name="phone" value="{{ $phone }}">
                <input type="hidden" name="amount_paid" value="{{ $amount_paid }}">
                @foreach ($products as $index => $product)
                    <input type="hidden" name="products[]" value="{{ $product->id }}">
                    <input type="hidden" name="quantities[]" value="{{ $quantities[$index] }}">
                @endforeach

                <!-- Product Details -->
                <div>
                    <h5 class="text-xl font-semibold text-gray-900 mb-4">Detail Produk</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-gray-700">
                            <thead class="bg-gray-50 text-gray-900">
                                <tr>
                                    <th class="p-4 text-left font-medium">Produk</th>
                                    <th class="p-4 text-left font-medium">Harga</th>
                                    <th class="p-4 text-left font-medium">Jml</th>
                                    <th class="p-4 text-left font-medium">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $index => $product)
                                    @if ($quantities[$index] > 0)
                                        @php
                                            $subtotal = $product->price * $quantities[$index];
                                        @endphp
                                        <tr
                                            class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                            <td class="p-4">{{ $product->name }}</td>
                                            <td class="p-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                            <td class="p-4">{{ $quantities[$index] }}</td>
                                            <td class="p-4">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Details -->
                <div>
                    <h5 class="text-xl font-semibold text-gray-900 mb-4">Rincian Pembayaran</h5>
                    <div class="grid sm:grid-cols-2 gap-4 text-gray-700">
                        <p><strong class="font-medium">Total:</strong> Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                        <p><strong class="font-medium">Dibayar:</strong> Rp {{ number_format($amount_paid, 0, ',', '.') }}
                        </p>
                        @php
                            $pointsEarned = floor($totalPrice / 100); // 1 poin per Rp 100
                            $usePoints = $previousPurchase && old('use_points', 0) == 1;
                            if ($usePoints) {
                                $discount = $totalPrice * 0.1; // Diskon 10%
                                $totalAfterDiscount = $totalPrice - $discount;
                            } else {
                                $totalAfterDiscount = $totalPrice;
                            }
                        @endphp
                        @if ($usePoints)
                            <p><strong class="font-medium">Diskon (10%):</strong> Rp
                                {{ number_format($discount, 0, ',', '.') }}</p>
                            <p><strong class="font-medium">Total Setelah Diskon:</strong> Rp
                                {{ number_format($totalAfterDiscount, 0, ',', '.') }}</p>
                        @endif
                        <p><strong class="font-medium">Poin Didapat:</strong> {{ $pointsEarned }} Poin</p>
                    </div>
                </div>

                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-900 mb-2">Nama Pelanggan</label>
                    <input type="text"
                        class="w-full border fokus:border-gray-200 rounded-lg py-3 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('customer_name') border-red-500 @enderror"
                        name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
                    @error('customer_name')
                        <div class="text-red-600 text-xs mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Use Member Points with Checkbox -->
                <div>
                    <label class="flex items-center text-sm text-gray-900 cursor-pointer">
                        <input type="hidden" name="use_points" value="0">
                        <input type="checkbox" name="use_points" value="1"
                            class="h-5 w-5 text-blue-600 fokus:border-gray-300 rounded focus:ring-blue-500 @error('use_points') border-red-500 @enderror"
                            id="use_points" {{ old('use_points', 0) == 1 ? 'checked' : '' }}
                            {{ !$previousPurchase ? 'disabled' : '' }}>
                        <span class="ml-3 font-medium">Gunakan Poin (Diskon 10%)</span>
                    </label>
                    <p id="points-message"
                        class="text-xs mt-2 {{ $previousPurchase ? 'text-green-600' : 'text-gray-500' }}">
                        {{ $previousPurchase ? 'Poin dapat digunakan untuk pembelian ini.' : 'Anda belum bisa memakai poin di pembelian pertama.' }}
                    </p>
                    @error('use_points')
                        <div class="text-red-600 text-xs mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-6 rounded-lg hover:from-blue-700 hover:to-blue-800 font-medium transition-all duration-300 shadow-md">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk cek nama pelanggan secara real-time -->
    <script src="{{ asset('js/process-sales.js') }}"></script>
@endsection
