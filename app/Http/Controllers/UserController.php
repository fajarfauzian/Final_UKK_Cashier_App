<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Menampilkan daftar user dengan fitur pencarian dan paginasi.
     */
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($query) => $query->where('name', 'like', "%{$request->search}%")
                                                        ->orWhere('email', 'like', "%{$request->search}%"))
            ->paginate($request->per_page ?? 10);

        return view('users.index', [
            'users' => $users,
            'startNumber' => $users->firstItem()
        ]);
    }

    /**
     * Menampilkan form untuk menambah user baru.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Menyimpan user baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Memperbarui data user di database.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|confirmed';
        }

        $validated = $request->validate($rules);
        
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        $user->forceDelete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}