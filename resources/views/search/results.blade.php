@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
    <div class="max-w-7xl mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-4">Search Results for "{{ $query }}"</h1>

        <!-- Products Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Products</h2>
            @if ($products->isEmpty())
                <p class="text-gray-600">No products found.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($products as $product)
                        <div class="bg-white p-3 rounded shadow">
                            <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                            <p class="text-gray-600">Rp {{ number_format($product->price, 2) }}</p>
                            <p class="text-gray-600">Stock: {{ $product->stock }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sales Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Sales</h2>
            @if ($sales->isEmpty())
                <p class="text-gray-600">No sales found.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($sales as $sale)
                        <div class="bg-white p-3 rounded shadow">
                            <h3 class="text-lg font-semibold">Sale {{ $sale->id }}</h3>
                            <p class="text-gray-600">Rp {{ number_format($sale->total_price, 2) }}</p>
                            <p class="text-gray-600">{{ $sale->customer_name ?? 'N/A' }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sales Details Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Sales Details</h2>
            @if ($salesDetails->isEmpty())
                <p class="text-gray-600">No sales details found.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($salesDetails as $detail)
                        <div class="bg-white p-3 rounded shadow">
                            <h3 class="text-lg font-semibold">{{ $detail->product->name }}</h3>
                            <p class="text-gray-600">Qty: {{ $detail->quantity }}</p>
                            <p class="text-gray-600">Rp {{ number_format($detail->subtotal, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Users Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-2">Users</h2>
            @if ($users->isEmpty())
                <p class="text-gray-600">No users found.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($users as $user)
                        <div class="bg-white p-3 rounded shadow">
                            <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
                            <p class="text-gray-600">{{ $user->email }}</p>
                            <p class="text-gray-600">{{ $user->role }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <a href="{{ route('dashboard') }}" class="text-blue-600">Back to Dashboard</a>
    </div>
@endsection
