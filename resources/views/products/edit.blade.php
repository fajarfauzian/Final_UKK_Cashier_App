@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="w-full">
    <h2 class="text-2xl font-medium text-gray-800 mb-6">Edit Produk</h2>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Produk -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Produk</label>
                <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}"
                       class="w-full p-3 rounded-lg border-gray-200 focus:ring-indigo-500" required>
            </div>

            <!-- Harga -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Harga</label>
                <div class="relative">
                    <span class="absolute left-3 top-3 text-gray-500">Rp</span>
                    <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}"
                           class="w-full pl-10 p-3 rounded-lg border-gray-200 focus:ring-indigo-500" required>
                </div>
            </div>

            <!-- Stok -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Stok</label>
                <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}"
                       class="w-full p-3 rounded-lg bg-gray-100 opacity-75 cursor-not-allowed" disabled>
            </div>

            <!-- Gambar Saat Ini -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Saat Ini</label>
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar {{ $product->name }}"
                         class="rounded-lg w-40 h-40 object-cover border-2 border-gray-100">
                @else
                    <div class="w-40 h-40 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                        <span class="text-gray-500">Tidak ada gambar</span>
                    </div>
                @endif
            </div>

            <!-- Ganti Gambar -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Ganti Gambar</label>
                <input type="file" name="image" id="image" class="w-full p-3 rounded-lg border-gray-200" accept="image/*">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" class="py-3 px-6 rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection