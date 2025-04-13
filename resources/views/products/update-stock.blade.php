<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50">

    <div class="bg-white rounded-xl shadow-lg w-full max-w-md relative">
        <form id="updateStockForm" method="POST" class="p-6 space-y-4">
            @csrf
            <div class="flex items-center gap-2">
                <span class="iconify text-green-600 text-xl" data-icon="mdi:warehouse"></span>
                <h3 class="text-lg font-semibold text-gray-800">
                    Update Stok <span id="productName" class="font-bold"></span>
                </h3>
            </div>
            <div>
                <label for="stockInput" class="block text-sm font-medium text-gray-700">Stok</label>
                <div class="relative">
                    <input type="number" name="stock" id="stockInput" required
                        class="w-full mt-1 px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:outline-none">
                    <span class="iconify absolute right-3 top-3 text-gray-400" data-icon="mdi:warehouse"></span>
                </div>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeStockModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-md">Batal</button>
                <button type="submit" id="saveStockButton"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
    @parent
    <script src="{{ asset('js/update-stock-products.js') }}"></script>
@endsection
