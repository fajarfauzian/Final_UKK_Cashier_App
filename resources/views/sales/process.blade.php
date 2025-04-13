@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-800 mb-6">Informasi Pelanggan</h2>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <ul class="list-disc pl-5 text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-4">
            <form action="{{ route('sales.process-transaction') }}" method="POST" class="space-y-4">
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
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Detail Produk</h5>
                    <table class="w-full text-sm text-gray-700">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="p-2 text-left">Produk</th>
                                <th class="p-2 text-left">Harga</th>
                                <th class="p-2 text-left">Jml</th>
                                <th class="p-2 text-left">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $index => $product)
                                @if ($quantities[$index] > 0)
                                    @php
                                        $subtotal = $product->price * $quantities[$index];
                                    @endphp
                                    <tr class="border-b">
                                        <td class="p-2">{{ $product->name }}</td>
                                        <td class="p-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="p-2">{{ $quantities[$index] }}</td>
                                        <td class="p-2">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Payment Details -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-2">Rincian Pembayaran</h5>
                    <div class="grid md:grid-cols-2 gap-2">
                        <p><strong>Total:</strong> Rp {{ number_format($totalPrice, 0, ',', '.') }}</p>
                        <p><strong>Dibayar:</strong> Rp {{ number_format($amount_paid, 0, ',', '.') }}</p>
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
                            <p><strong>Diskon (10%):</strong> Rp {{ number_format($discount, 0, ',', '.') }}</p>
                            <p><strong>Total Setelah Diskon:</strong> Rp
                                {{ number_format($totalAfterDiscount, 0, ',', '.') }}</p>
                        @endif
                        <p><strong>Poin Didapat:</strong> {{ $pointsEarned }} Poin</p>
                    </div>
                </div>

                <!-- Customer Name -->
                <div>
                    <label for="customer_name" class="block text-sm text-gray-700">Nama Pelanggan</label>
                    <input type="text" class="w-full border rounded p-2 @error('customer_name') border-red-500 @enderror"
                        name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required>
                    @error('customer_name')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Use Member Points with Checkbox -->
                <div>
                    <label class="flex items-center text-sm text-gray-700">
                        <input type="hidden" name="use_points" value="0">
                        <input type="checkbox" name="use_points" value="1"
                            class="mr-2 @error('use_points') border-red-500 @enderror" id="use_points"
                            {{ old('use_points', 0) == 1 ? 'checked' : '' }} {{ !$previousPurchase ? 'disabled' : '' }}>
                        Gunakan Poin (Diskon 10%)
                    </label>
                    <p id="points-message"
                        class="text-xs mt-1 {{ $previousPurchase ? 'text-green-500' : 'text-gray-500' }}">
                        {{ $previousPurchase ? 'Poin dapat digunakan untuk pembelian ini.' : 'Anda belum bisa memakai poin di pembelian pertama.' }}
                    </p>
                    @error('use_points')
                        <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Simpan</button>
                    <a href="{{ route('sales.create') }}"
                        class="flex-1 bg-gray-200 text-gray-700 p-2 rounded hover:bg-gray-300 text-center">Kembali</a>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk cek nama pelanggan secara real-time -->
    <script src="{{ asset('js/process-sales.js') }}"></script>
@endsection
