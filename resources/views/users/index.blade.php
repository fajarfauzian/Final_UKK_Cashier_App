@extends('layouts.app')

@section('title', 'Daftar User')

@section('content')
    <div class="w-full">
        <h2 class="text-2xl font-medium mb-4 text-gray-800">Daftar Pengguna</h2>

        <!-- Header Section -->
        <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <select id="entries" class="text-sm p-2 rounded-lg border border-gray-300 focus:outline-none"
                        onchange="window.location.href='{{ route('users.index') }}?per_page=' + this.value + '&search={{ request('search') }}'">
                        <option disabled selected>Showing {{ request('per_page') ?? 10 }}</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <div class="relative">
                        <form action="{{ route('users.index') }}" method="GET">
                            <div class="relative">
                                <input type="text" name="search"
                                    class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    placeholder="Cari user..." value="{{ request('search') }}">
                                <i class="absolute top-1/2 left-3 -translate-y-1/2 ri-search-line text-gray-400"></i>
                                <input type="hidden" name="per_page" value="{{ request('per_page') ?? 10 }}">
                            </div>
                        </form>
                    </div>
                </div>

                <a href="{{ route('users.create') }}"
                    class="px-3 py-2 text-sm font-medium text-gray-600 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200">
                    <i class="ri-add-line mr-1"></i> Tambah User
                </a>
            </div>
        </div>

        <!-- Table -->
        <div class="p-3 bg-white border border-gray-200 rounded-lg shadow-sm">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">No.</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Nama</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Email</th>
                        <th class="py-3 px-6 text-left text-sm font-medium text-gray-500">Role</th>
                        <th class="py-3 px-6 text-center text-sm font-medium text-gray-500">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 border-b">
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $startNumber + $loop->index }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $user->name }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $user->email }}</td>
                            <td class="px-6 py-3 text-sm text-gray-900">{{ $user->role }}</td>
                            <td class="px-6 py-3">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('users.edit', $user->id) }}"
                                        class="p-1.5 text-blue-600 hover:bg-blue-100 rounded-full" title="Edit">
                                        <i class="ri-edit-line text-md"></i>
                                    </a>
                                    <button onclick="openModal({{ $user->id }})"
                                        class="p-1.5 text-red-600 hover:bg-red-100 rounded-full" title="Hapus">
                                        <i class="ri-delete-bin-line text-md"></i>
                                    </button>
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
                            <td colspan="5" class="px-6 py-10 text-center text-sm text-gray-500">Belum ada user</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/index-users.js') }}"></script>
@endsection