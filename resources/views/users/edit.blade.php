@extends('layouts.app')
@section('title', 'Edit User')
@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium text-gray-800 mb-4">Edit User</h2>
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm text-gray-700">Nama</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                        class="w-full p-2 rounded border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-200' }}"
                        required>
                    @error('name')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="w-full p-2 rounded border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-200' }}"
                        required>
                    @error('email')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm text-gray-700">Password Baru (opsional)</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-2 rounded border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-200' }}">
                    @error('password')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm text-gray-700">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full p-2 rounded border border-gray-200">
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded hover:bg-indigo-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
