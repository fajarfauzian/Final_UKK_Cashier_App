@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="w-full">
    <h2 class="text-3xl font-medium text-gray-900 mb-8">Edit Produk</h2>

    <div class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Left Side: Nama Produk, Harga, Stok -->
                <div class="space-y-8">
                    <!-- Nama Produk -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name', $product->name) }}"
                               class="w-full px-4 py-3 rounded-xl border-0 bg-gray-100 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                               placeholder="Masukkan nama produk"
                               required>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 text-sm">Rp</span>
                            <input type="number" 
                                   name="price" 
                                   id="price" 
                                   value="{{ old('price', $product->price) }}"
                                   class="w-full pl-12 pr-4 py-3 rounded-xl border-0 bg-gray-100 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                   placeholder="0"
                                   required>
                        </div>
                    </div>

                    <!-- Stok -->
                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                        <input type="number" 
                               name="stock" 
                               id="stock" 
                               value="{{ old('stock', $product->stock) }}"
                               class="w-full px-4 py-3 rounded-xl border-0 bg-gray-100 opacity-75 cursor-not-allowed"
                               placeholder="Masukkan jumlah stok"
                               disabled>
                    </div>
                </div>

                <!-- Right Side: Gambar Saat Ini, Ganti Gambar -->
                <div class="space-y-8">
                    <!-- Gambar Saat Ini -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gambar Saat Ini</label>
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 alt="Gambar {{ $product->name }}"
                                 class="rounded-xl w-full max-h-80 object-contain border-2 border-gray-100">
                        @else
                            <div class="w-full h-80 bg-gray-50 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-200">
                                <span class="text-gray-500 text-sm">Tidak ada gambar</span>
                            </div>
                        @endif
                    </div>

                    <!-- Ganti Gambar -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Ganti Gambar</label>
                        <label for="image"
                               class="flex flex-col items-center w-full h-24 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                            <div id="upload-text" class="flex flex-col items-center justify-center h-full">
                                <span class="iconify w-8 h-8 mb-2 text-gray-400" data-icon="mdi:cloud-upload"></span>
                                <p class="text-sm text-gray-500">
                                    <span class="font-semibold text-blue-600 hover:text-blue-700">Klik untuk upload</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPEG, atau GIF</p>
                            </div>
                            <img id="image-preview" 
                                 class="hidden max-h-24 w-full object-contain rounded-xl p-2"
                                 alt="Preview">
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   class="hidden"
                                   accept="image/png, image/jpeg, image/gif">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Buttons: Submit and Cancel -->
            <div class="mt-8 flex justify-end space-x-4">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center py-3 px-8 rounded-xl text-gray-700 bg-gray-200 hover:bg-gray-300 focus:ring-2 focus:ring-gray-400 font-semibold transition-all duration-200">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center py-3 px-8 rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 font-semibold shadow-md transition-all duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/edit-products.js') }}"></script>
@endsection