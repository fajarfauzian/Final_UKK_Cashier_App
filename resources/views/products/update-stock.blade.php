<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75" onclick="closeStockModal()"></div>
        <!-- Modal panel -->
        <div class="bg-white rounded-lg shadow w-full max-w-md z-10">
            <div class="p-4 flex items-start">
                <span class="iconify h-6 w-6 text-green-600 mr-2" data-icon="mdi:warehouse"></span>
                <div class="w-full">
                    <h3 class="text-lg font-medium text-gray-900">
                        Update Stok <span id="productName" class="font-semibold"></span>
                    </h3>
                    <form id="updateStockForm" method="POST" class="mt-2">
                        @csrf
                        <label for="stockInput" class="block text-sm text-gray-700">Stok</label>
                        <div class="relative mt-1">
                            <input type="number" name="stock" id="stockInput"
                                   class="w-full p-2 rounded border-gray-200 focus:ring-green-500" required>
                            <span class="iconify h-5 w-5 text-gray-400 absolute right-2 top-2"
                                  data-icon="mdi:warehouse"></span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="bg-gray-50 p-4 flex justify-end gap-2">
                <button onclick="closeStockModal()"
                        class="px-3 py-1 rounded border text-gray-700 hover:bg-gray-50">Batal</button>
                <button id="saveStockButton"
                        class="px-3 py-1 rounded bg-green-600 text-white hover:bg-green-700">Simpan</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="{{ asset('js/update-stock-products.js') }}"></script>
@endsection