@extends('layouts.app')

@section('title', 'Transaksi Berhasil')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-900 mb-5">Transaksi Berhasil</h2>

        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-6">
                <h5 class="text-xl font-semibold">Ringkasan Transaksi</h5>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Pelanggan</p>
                        <h5 class="text-lg font-semibold text-gray-900">{{ $customer_name }}</h5>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Status</p>
                        <span class="{{ $is_member ? 'bg-green-500' : 'bg-gray-500' }} text-white text-sm px-3 py-1 rounded-full font-medium">
                            {{ $is_member ? 'Member' : 'Non-Member' }}
                        </span>
                    </div>
                </div>

                <h4 class="text-xl font-semibold text-gray-900 mb-4">Daftar Produk</h4>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-700">
                        <thead class="bg-gray-900 text-white">
                            <tr>
                                <th class="p-4 text-left font-medium">Produk</th>
                                <th class="p-4 text-left font-medium">Harga</th>
                                <th class="p-4 text-left font-medium">Jml</th>
                                <th class="p-4 text-left font-medium">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($selectedProducts as $product)
                                @if ($product->quantity > 0)
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors duration-200">
                                        <td class="p-4">{{ $product->name }}</td>
                                        <td class="p-4">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="p-4">{{ $product->quantity }}</td>
                                        <td class="p-4 font-semibold">Rp {{ number_format($product->price * $product->quantity, 0, ',', '.') }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="grid md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Rincian Pembayaran</h4>
                        @if ($is_member && $sale->use_points)
                            <div class="flex justify-between text-sm text-gray-700 mb-2">
                                <span>Sebelum Diskon</span>
                                <span>Rp {{ number_format($totalPrice / 0.9, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-green-600 mb-2">
                                <span>Diskon (10%)</span>
                                <span>Rp {{ number_format($totalPrice / 0.9 - $totalPrice, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-base font-bold text-gray-900">
                            <span>Total</span>
                            <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 shadow-sm">
                        <h4 class="text-lg font-semibold text-gray-900 mb-3">Detail Pembayaran</h4>
                        <div class="flex justify-between text-sm text-gray-700 mb-2">
                            <span>Dibayar</span>
                            <span>Rp {{ number_format($amountPaid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold text-gray-900">
                            <span>Kembalian</span>
                            <span>Rp {{ number_format($change, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex flex-col sm:flex-row justify-end gap-4">
                    <a href="{{ route('sales.pdf', $sale->id) }}"
                        class="bg-gradient-to-r from-red-600 to-red-700 text-white py-3 px-6 rounded-lg hover:from-red-700 hover:to-red-800 font-medium transition-all duration-300 shadow-md text-center"
                        target="_blank">
                        Cetak PDF
                    </a>
                    <a href="{{ route('sales.create') }}"
                        class="flex items-center bg-gray-100 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-200 font-medium text-center transition-colors duration-200 border border-gray-200">
                        <i class="ri-arrow-left-line h-5 w-5 mr-2 text-gray-600"></i>
                        Transaksi Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection