<div class="bg-white rounded-lg shadow overflow-hidden">
    <!-- Header -->
    <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
        <h5 class="text-lg font-semibold text-gray-800">
            Detail Penjualan - {{ $sale->is_member && $sale->customer_name ? $sale->customer_name : 'NON-MEMBER' }}
        </h5>
        <button class="text-gray-400 hover:text-gray-600"
            onclick="document.getElementById('saleDetailModal').classList.add('hidden')">
            <span class="iconify w-5 h-5" data-icon="mdi:close"></span>
        </button>
    </div>

    <!-- Body -->
    <div class="p-4">
        <div class="grid md:grid-cols-2 gap-4 mb-4">
            <div>
                <p><strong class="text-gray-700">Pelanggan:</strong>
                    {{ $sale->is_member && $sale->customer_name ? $sale->customer_name : 'NON-MEMBER' }}</p>
                <p><strong class="text-gray-700">Kasir:</strong> {{ $sale->user?->name ?? 'Unknown' }}
                    ({{ $sale->user?->role ?? 'N/A' }})</p>
                <p><strong class="text-gray-700">Tanggal:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="text-right">
                <p><strong class="text-gray-700">Total:</strong> Rp{{ number_format($sale->total_price, 0, ',', '.') }}
                </p>
                <p><strong class="text-gray-700">Diterima:</strong>
                    Rp{{ number_format($sale->amount_paid, 0, ',', '.') }}</p>
                <p><strong class="text-gray-700">Kembalian:</strong> Rp{{ number_format($sale->change, 0, ',', '.') }}
                </p>
            </div>
        </div>

        <h2 class="text-lg font-semibold text-gray-800 mb-2">Daftar Produk</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Produk</th>
                        <th class="p-2 text-right">Harga</th>
                        <th class="p-2 text-center">Jumlah</th>
                        <th class="p-2 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($details as $detail)
                        <tr class="border-b">
                            <td class="p-2">{{ $detail->product?->name ?? 'Product Not Found' }}</td>
                            <td class="p-2 text-right">Rp{{ number_format($detail->unit_price, 0, ',', '.') }}</td>
                            <td class="p-2 text-center">{{ $detail->quantity }}</td>
                            <td class="p-2 text-right">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="p-2 text-center text-gray-500">Tidak ada produk</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="font-bold bg-gray-50">
                        <td colspan="3" class="p-2 text-right">Total</td>
                        <td class="p-2 text-right">Rp{{ number_format($sale->total_price, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <div class="p-4 border-t flex justify-end">
        <button class="bg-gray-200 text-gray-700 px-4 py-1 rounded hover:bg-gray-300"
            onclick="document.getElementById('saleDetailModal').classList.add('hidden')">
            Tutup
        </button>
    </div>
</div>
