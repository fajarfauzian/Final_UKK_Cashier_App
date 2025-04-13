<?php

namespace App\Http\Controllers;

use App\Models\User; // Mengimpor model User untuk mengakses dan memanipulasi data di tabel 'users'
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani data yang dikirim melalui HTTP request (GET/POST)
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk mengenkripsi password sebelum disimpan ke database

class UserController extends Controller
{
    /**
     * Tampilkan daftar user.
     * 
     * Method ini menampilkan daftar pengguna dengan fitur pencarian dan paginasi.
     * Parameter $request berisi data dari query string (misalnya ?search=keyword&per_page=10)
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query) use ($request) {
                return $query->where('name', 'like', '%' . $request->search . '%') // Mencari berdasarkan nama
                    ->orWhere('email', 'like', '%' . $request->search . '%'); // Atau berdasarkan email
            })
            ->paginate($request->per_page ?? 10);

        $startNumber = $users->firstItem();

        return view('users.index', compact('users', 'startNumber'));
    }

    /**
     * Tampilkan form untuk menambah user baru.
     * 
     * Method ini hanya menampilkan form kosong untuk membuat pengguna baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Simpan user baru ke database.
     * 
     * Method ini memproses data dari form create dan menyimpan pengguna baru.
     * Parameter $request berisi data dari form yang dikirim via POST
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', // Nama wajib, harus string, maksimum 255 karakter
            'email' => 'required|email|unique:users,email', // Email wajib, harus format email, unik di tabel 'users' kolom 'email'
            'password' => 'required|confirmed', // Password wajib, harus dikonfirmasi (ada field password_confirmation)
        ]);

        User::create([
            'name' => $request->name, // Nama dari input form
            'email' => $request->email, // Email dari input form
            'password' => Hash::make($request->password), // Password dienkripsi
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit user.
     * 
     * Method ini menampilkan form untuk mengedit data pengguna yang ada.
     * Parameter $user adalah instance User yang di-inject via route model binding
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update user di database.
     * 
     * Method ini memproses data dari form edit dan memperbarui data pengguna.
     * Parameter $request berisi data dari form, $user adalah instance User yang akan diubah
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255', // Nama wajib, string, maks 255 karakter
            'email' => 'required|email|unique:users,email,' . $user->id,
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|confirmed'; // Password wajib dan harus dikonfirmasi
        }

        $request->validate($rules);
        $updateData = [
            'name' => $request->name, // Nama baru dari input
            'email' => $request->email, // Email baru dari input
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Hapus user dari database.
     * 
     * Method ini menghapus pengguna tertentu dari tabel 'users'.
     * Parameter $user adalah instance User yang di-inject via route model binding
     */
    public function destroy(User $user)
    {
        $user->forceDelete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}