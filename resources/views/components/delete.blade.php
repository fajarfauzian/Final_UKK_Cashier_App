<div class="fixed inset-0 z-50 hidden" id="deleteModal">
    <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow w-full max-w-md p-4">
            <h3 class="font-semibold">Konfirmasi Hapus</h3>
            <p class="mt-2">Yakin ingin menghapus produk: <span id="deleteProductName" class="font-medium"></span>?</p>
            <div class="mt-4 flex justify-end gap-2">
                <button onclick="closeDeleteModal()" class="px-3 py-1 border rounded">Batal</button>
                <button type="button" onclick="deleteProduct()" class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    @parent
    <script src="{{ asset('js/delete-products.js') }}"></script>
@endsection