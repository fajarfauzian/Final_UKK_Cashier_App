@extends('layouts.app')

@section('title', 'Transaksi Berhasil')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-800 mb-6">Transaksi Berhasil</h2>

        <div class="bg-white rounded-lg shadow">
            <div class="bg-green-600 text-white p-4">
                <h5 class="text-lg font-semibold">Ringkasan Transaksi</h5>
            </div>

            <div class="p-4">
                <div class="grid md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-500">Pelanggan</p>
                        <h5 class="text-lg font-semibold">{{ $customer_name }}</h5>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span
                            class="{{ $is_member ? 'bg-green-500' : 'bg-gray-500' }} text-white text-sm px-2 py-1 rounded">
                            {{ $is_member ? 'Member' : 'Non-Member' }}
                        </span>
                    </div>
                </div>

                <h4 class="text-xl font-bold text-gray-800 mb-2">Daftar Produk</h4>
                <table class="w-full text-sm text-gray-700">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="p-2 text-left">Produk</th>
                            <th class="p-2 text-left">Harga</th>
                            <th class="p-2 text-left">Jumlah</th>
                            <th class="p-2 text-left">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($selectedProducts as $product)
                            @if ($product->quantity > 0)
                                <tr class="border-b">
                                    <td class="p-2">{{ $product->name }}</td>
                                    <td class="p-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="p-2">{{ $product->quantity }}</td>
                                    <td class="p-2 font-semibold">Rp
                                        {{ number_format($product->price * $product->quantity, 0, ',', '.') }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

                <div class="grid md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-gray-50 p-3">
                        <h4 class="text-lg font-bold text-gray-800 mb-2">Rincian Pembayaran</h4>
                        @if ($is_member && $sale->use_points)
                            <div class="flex justify-between text-sm">
                                <span>Sebelum Diskon</span>
                                <span>Rp {{ number_format($totalPrice / 0.9, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-green-600">
                                <span>Diskon (10%)</span>
                                <span>Rp {{ number_format($totalPrice / 0.9 - $totalPrice, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-base font-bold">
                            <span>Total</span>
                            <span>Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-3">
                        <h4 class="text-lg font-bold text-gray-800 mb-2">Detail Pembayaran</h4>
                        <div class="flex justify-between text-sm">
                            <span>Dibayar</span>
                            <span>Rp {{ number_format($amountPaid, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-base font-bold">
                            <span>Kembalian</span>
                            <span>Rp {{ number_format($change, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <a href="{{ route('sales.pdf', $sale->id) }}"
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" target="_blank">Cetak PDF</a>
                    <a href="{{ route('sales.create') }}"
                        class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300">Transaksi Baru</a>
                </div>
            </div>
        </div>
    </div>
@endsection
