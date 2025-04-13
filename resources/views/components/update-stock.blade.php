<!-- Update Stock Modal -->
<div id="updateStockModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeStockModal()"></div>
        <!-- Modal panel -->
        <div class="bg-white rounded-xl w-full max-w-sm z-10">
            <div class="p-5">
                <div class="flex items-center">
                    <span class="iconify h-6 w-6 text-teal-600 mr-2" data-icon="mdi:warehouse"></span>
                    <h3 class="text-lg font-semibold text-gray-800">
                        Update Stok <span id="productName" class="font-medium"></span>
                    </h3>
                </div>
                <form id="updateStockForm" method="POST" class="mt-4">
                    @csrf
                    <label for="stockInput" class="block text-sm font-medium text-gray-600">Stok</label>
                    <div class="relative mt-1">
                        <input type="number" 
                               name="stock" 
                               id="stockInput"
                               class="w-full px-3 py-2 rounded-lg border border-gray-200 focus:border-teal-500 focus:ring-1 focus:ring-teal-500"
                               placeholder="Jumlah stok"
                               required>
                        <span class="iconify h-5 w-5 text-gray-400 absolute right-2 top-2.5"
                              data-icon="mdi:warehouse"></span>
                    </div>
                </form>
            </div>
            <div class="p-5 flex justify-end gap-3">
                <button onclick="closeStockModal()"
                        class="px-4 py-2 rounded-lg text-gray-600 bg-gray-100 hover:bg-gray-200 font-medium">
                    Batal
                </button>
                <button id="saveStockButton"
                        class="px-4 py-2 rounded-lg text-white bg-teal-600 hover:bg-teal-700 font-medium">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="{{ asset('js/update-stock-products.js') }}"></script>
@endsection