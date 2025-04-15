@extends('layouts.app')

@section('title', 'Konfirmasi Membership')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-800 mb-6">Konfirmasi Membership</h2>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                <ul class="list-disc pl-5 text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-md p-6">
            <form id="confirmForm" action="" method="POST" class="grid md:grid-cols-2 gap-6">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <!-- Product Details -->
                <div>
                    <h5 class="text-lg font-semibold text-gray-800 mb-4">Detail Produk</h5>
                    <div class="space-y-3">
                        @foreach ($selectedProducts as $index => $product)
                            @if ($quantities[$index] > 0)
                                <div class="bg-gray-50 rounded-lg p-3 flex items-center">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="w-12 h-12 object-cover rounded-md mr-3" alt="{{ $product->name }}">
                                    <div class="flex-1">
                                        <input type="hidden" name="products[]" value="{{ $product->id }}">
                                        <p class="text-sm font-medium text-gray-800 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-600">Rp
                                            {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                    <input type="number"
                                        class="w-20 text-center border-gray-300 rounded-md p-1 bg-gray-100"
                                        name="quantities[]" value="{{ $quantities[$index] }}" readonly>
                                    <p class="text-sm font-semibold text-gray-800 ml-3">Rp
                                        {{ number_format($product->price * $quantities[$index], 0, ',', '.') }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Membership & Payment -->
                <div>
                    <div class="mb-6">
                        <h5 class="text-lg font-semibold text-gray-800 mb-3">Status Membership</h5>
                        <label class="flex items-center">
                            <input type="radio" name="is_member" value="1"
                                {{ old('is_member', 0) == 1 ? 'checked' : '' }} onchange="updateFormAction()">
                            <span class="ml-2 text-sm text-gray-700">Member</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="is_member" value="0"
                                {{ old('is_member', 0) == 0 ? 'checked' : '' }} onchange="updateFormAction()">
                            <span class="ml-2 text-sm text-gray-700">Non-Member</span>
                        </label>
                        @error('is_member')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6" id="phoneSection"
                        style="display: {{ old('is_member', 0) == 1 ? 'block' : 'none' }};">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon
                            Member</label>
                        <input type="number" class="w-full border rounded-lg p-2 @error('phone') border-red-500 @enderror"
                            name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon member">
                        @error('phone')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800">Total Bayar: <span id="total-payment">Rp
                                {{ number_format($totalPrice, 0, ',', '.') }}</span></h4>
                    </div>

                    <div class="mb-6">
                        <label for="amount_paid_display" class="block text-sm font-medium text-gray-700 mb-1">Jumlah
                            Dibayar</label>
                        <input type="text"
                            class="w-full border rounded-lg p-2 @error('amount_paid') border-red-500 @enderror"
                            id="amount_paid_display"
                            value="{{ old('amount_paid') ? 'Rp ' . number_format(old('amount_paid'), 0, ',', '.') : '' }}"
                            placeholder="Rp 0" oninput="formatCurrency(this)">
                        <input type="hidden" name="amount_paid" id="amount_paid" value="{{ old('amount_paid') }}">
                        @error('amount_paid')
                            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="customer_name" value="NON-MEMBER">
                    <input type="hidden" name="use_points" value="0">

                    <button type="submit"
                        class="w-full bg-blue-600 text-white py-3 mb-3 rounded-full hover:bg-blue-700 font-semibold">Pesan</button>
                    <a href="{{ route('sales.create') }}"
                        class="block w-full bg-gray-200 text-gray-700 py-3 rounded-full hover:bg-gray-300 font-semibold text-center">Kembali</a>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('js/member-sales.js') }}"></script>
@endsection
