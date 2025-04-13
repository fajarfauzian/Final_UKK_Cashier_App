@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="w-full">
        <h2 class="text-3xl font-medium text-gray-900 mb-6">Tambah Produk</h2>
        <div class="bg-white rounded-2xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Side: Nama Produk, Harga, Stok -->
                    <div class="space-y-8">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-3 rounded-xl border-0 bg-gray-100 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                   placeholder="Masukkan nama produk"
                                   required>
                        </div>

                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 text-sm">Rp</span>
                                <input type="text" 
                                       name="price_display" 
                                       id="price_display"
                                       value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                                       class="w-full pl-12 pr-4 py-3 rounded-xl border-0 bg-gray-100 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                       placeholder="0"
                                       required>
                                <input type="hidden" 
                                       name="price" 
                                       id="price" 
                                       value="{{ old('price') }}">
                            </div>
                        </div>

                        <div>
                            <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok</label>
                            <input type="number" 
                                   name="stock" 
                                   id="stock" 
                                   value="{{ old('stock') }}"
                                   class="w-full px-4 py-3 rounded-xl border-0 bg-gray-100 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition-all duration-200"
                                   placeholder="Masukkan jumlah stok"
                                   required>
                        </div>
                    </div>

                    <!-- Right Side: Gambar Produk -->
                    <div>
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">Gambar Produk</label>
                        <label for="image"
                               class="flex flex-col items-center w-full h-80 border-2 border-dashed border-gray-200 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all duration-200">
                            <div id="upload-text" class="flex flex-col items-center justify-center h-full">
                                <span class="iconify w-12 h-12 mb-3 text-gray-400" data-icon="mdi:cloud-upload"></span>
                                <p class="text-sm text-gray-500">
                                    <span class="font-semibold text-blue-600 hover:text-blue-700">Klik untuk upload</span>
                                </p>
                                <p class="text-xs text-gray-400 mt-1">PNG, JPEG, atau GIF</p>
                            </div>
                            <img id="image-preview" 
                                 class="hidden max-h-80 w-full object-contain rounded-xl p-2"
                                 alt="Preview">
                            <input type="file" 
                                   name="image" 
                                   id="image" 
                                   class="hidden"
                                   accept="image/png, image/jpeg, image/gif">
                        </label>
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
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/create-products.js') }}"></script>
@endsection