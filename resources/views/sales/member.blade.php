@extends('layouts.app')

@section('title', 'Konfirmasi Membership')

@section('content')
    <div class="w-full">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-2xl font-medium text-gray-900">Konfirmasi Membership</h2>
            <a href="{{ route('sales.create') }}" 
                class="flex items-center bg-gray-100 text-gray-700 py-2 mt-3 px-4 rounded-lg hover:bg-gray-200 font-medium text-center transition-colors duration-200 border border-gray-200">
                <i class="ri-arrow-left-line h-5 w-5 mr-2 text-gray-600"></i>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6 shadow-sm">
                <ul class="list-disc pl-5 text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-8 mb-10 border border-gray-100">
            <form id="confirmForm" action="" method="POST" class="grid md:grid-cols-2 gap-8">
                @csrf
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                <!-- Product Details -->
                <div>
                    <h5 class="text-xl font-bold text-gray-800 mb-5 flex items-center">
                        <i class="ri-shopping-bag-line h-7 w-5 mr-2 text-blue-600"></i>
                        Detail Produk
                    </h5>
                    <div class="space-y-4">
                        @foreach ($selectedProducts as $index => $product)
                            @if ($quantities[$index] > 0)
                                <div class="bg-gray-50 hover:bg-gray-100 transition-colors duration-200 rounded-xl p-4 flex items-center shadow-sm border border-gray-100">
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                        class="w-16 h-16 object-cover rounded-lg mr-4 shadow-sm" alt="{{ $product->name }}">
                                    <div class="flex-1">
                                        <input type="hidden" name="products[]" value="{{ $product->id }}">
                                        <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                                        <p class="text-xs text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="number"
                                            class="w-16 text-center border-gray-200 rounded-md p-1 bg-white shadow-inner"
                                            name="quantities[]" value="{{ $quantities[$index] }}" readonly>
                                        <p class="text-sm font-bold text-gray-800 ml-4 w-24 text-right">Rp {{ number_format($product->price * $quantities[$index], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Membership & Payment -->
                <div>
                    <div class="mb-6 bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h5 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="ri-shopping-bag-line h-7 w-5 mr-2 text-blue-600"></i>
                            Status Membership
                        </h5>
                        <div class="flex space-x-4">
                            <label class="flex items-center cursor-pointer bg-white p-3 rounded-lg border border-gray-200 flex-1 hover:border-blue-400 transition-colors duration-200">
                                <input type="radio" name="is_member" value="1"
                                    {{ old('is_member', 0) == 1 ? 'checked' : '' }} 
                                    onchange="updateFormAction()"
                                    class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Member</span>
                            </label>
                            <label class="flex items-center cursor-pointer bg-white p-3 rounded-lg border border-gray-200 flex-1 hover:border-blue-400 transition-colors duration-200">
                                <input type="radio" name="is_member" value="0"
                                    {{ old('is_member', 0) == 0 ? 'checked' : '' }} 
                                    onchange="updateFormAction()"
                                    class="text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm font-medium text-gray-700">Non-Member</span>
                            </label>
                        </div>
                        @error('is_member')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6" id="phoneSection"
                        style="display: {{ old('is_member', 0) == 1 ? 'block' : 'none' }};">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Member</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-phone-line h-5 w-5 text-gray-400"></i>
                            </div>
                            <input type="number" 
                                class="w-full pl-10 border fokus:border-gray-300 rounded-lg py-3 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror"
                                name="phone" value="{{ old('phone') }}" placeholder="Masukkan nomor telepon member">
                        </div>
                        @error('phone')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-6 bg-blue-50 p-5 rounded-xl border border-blue-100 shadow-sm">
                        <h4 class="text-xl font-bold text-gray-800 flex justify-between items-center">
                            <span>Total Bayar:</span> 
                            <span id="total-payment" class="text-blue-600">Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </h4>
                    </div>

                    <div class="mb-8">
                        <label for="amount_paid_display" class="block text-sm font-medium text-gray-700 mb-2">Jumlah Dibayar</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="text"
                                class="w-full pl-12 border fokus:border-gray-300 rounded-lg py-3 focus:ring-blue-500 focus:border-blue-500 @error('amount_paid') border-red-500 @enderror"
                                id="amount_paid_display"
                                value="{{ old('amount_paid') ? 'Rp ' . number_format(old('amount_paid'), 0, ',', '.') : '' }}"
                                placeholder="0" oninput="formatCurrency(this)">
                        </div>
                        <input type="hidden" name="amount_paid" id="amount_paid" value="{{ old('amount_paid') }}">
                        @error('amount_paid')
                            <div class="text-red-500 text-xs mt-2">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="customer_name" value="NON-MEMBER">
                    <input type="hidden" name="use_points" value="0">

                    <div class="flex flex-col space-y-3">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white py-3 px-4 rounded-xl hover:bg-blue-700 font-bold flex items-center justify-center transition-colors duration-200 shadow-md">
                            <i class="ri-checkbox-circle-line h-5 w-5 mr-2"></i>
                            Pesan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/member-sales.js') }}"></script>
@endsection