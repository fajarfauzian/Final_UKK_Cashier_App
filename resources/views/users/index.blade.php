@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium mb-4">Daftar User</h2>

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:justify-between mb-4 gap-3">
            <select id="entries" class="p-2 rounded border"
                onchange="window.location.href='?per_page='+this.value+'&search={{ request('search') }}'">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            </select>

            <form action="{{ route('users.index') }}" method="GET" class="flex gap-3">
                <input type="text" name="search" class="p-2 rounded border" placeholder="Cari user..."
                    value="{{ request('search') }}">
                <a href="{{ route('users.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Tambah User
                </a>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-3 text-left text-gray-500 uppercase">No.</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Nama</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Email</th>
                        <th class="p-3 text-left text-gray-500 uppercase">Role</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse ($users as $user)
                        <tr>
                            <td class="p-3">{{ $startNumber + $loop->index }}</td>
                            <td class="p-3">{{ $user->name }}</td>
                            <td class="p-3">{{ $user->email }}</td>
                            <td class="p-3">{{ $user->role }}</td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="text-blue-600 hover:underline">Edit</a>
                                    <button onclick="openModal({{ $user->id }})"
                                        class="text-red-600 hover:underline">Hapus</button>
                                </div>

                                <!-- Delete Modal -->
                                <div class="fixed inset-0 z-50 hidden" id="modal{{ $user->id }}">
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
                                        <div class="bg-white rounded-lg shadow w-full max-w-md p-4">
                                            <h3 class="font-semibold">Konfirmasi Hapus</h3>
                                            <p class="mt-2">Yakin ingin menghapus user ini?</p>
                                            <div class="mt-4 flex justify-end gap-2">
                                                <button onclick="closeModal({{ $user->id }})"
                                                    class="px-3 py-1 border rounded">Batal</button>
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">Belum ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script src="{{ asset('js/index-users.js') }}"></script>
@endsection
