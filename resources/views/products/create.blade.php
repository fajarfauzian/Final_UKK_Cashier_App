@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-800 mb-6">Tambah Produk</h2>
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           class="w-full pl-4 pr-10 py-3 rounded-lg border-0 bg-gray-50 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 text-gray-500">Rp</span>
                        <input type="text" 
                               name="price_display" 
                               id="price_display"
                               value="{{ old('price') ? number_format(old('price'), 0, ',', '.') : '' }}"
                               class="w-full pl-10 pr-10 py-3 rounded-lg border-0 bg-gray-50 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500"
                               required>
                        <input type="hidden" 
                               name="price" 
                               id="price" 
                               value="{{ old('price') }}">
                    </div>
                </div>

                <div>
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                    <input type="number" 
                           name="stock" 
                           id="stock" 
                           value="{{ old('stock') }}"
                           class="w-full pl-4 pr-10 py-3 rounded-lg border-0 bg-gray-50 focus:bg-white ring-1 ring-gray-200 focus:ring-2 focus:ring-indigo-500"
                           required>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Gambar Produk</label>
                    <label for="image"
                           class="flex flex-col items-center w-full h-24 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div id="upload-text" class="flex flex-col items-center pt-5 pb-6">
                            <span class="iconify w-8 h-8 mb-2 text-gray-500" data-icon="mdi:cloud-upload"></span>
                            <p class="text-sm text-gray-500">
                                <span class="font-semibold">Klik untuk upload</span>
                            </p>
                        </div>
                        <img id="image-preview" 
                             class="hidden max-h-24 object-contain rounded-lg"
                             alt="Preview">
                        <input type="file" 
                               name="image" 
                               id="image" 
                               class="hidden"
                               accept="image/png, image/jpeg, image/gif">
                    </label>
                </div>

                <button type="submit"
                        class="inline-flex items-center py-3 px-6 rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 font-medium shadow-md">
                    Simpan
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/create-products.js') }}"></script>
@endsection