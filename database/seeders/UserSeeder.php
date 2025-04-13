<?php

namespace Database\Seeders; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa kelas ini berada di folder Database/Seeders

use Illuminate\Database\Seeder; // Mengimpor kelas Seeder dari Laravel untuk mendukung proses seeding database
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk mengenkripsi kata sandi
use App\Models\User; // Mengimpor model User untuk membuat entri pengguna di database

// Mendefinisikan kelas UserSeeder yang mewarisi Seeder
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Method ini berisi logika untuk mengisi (seed) database dengan data awal.
     * Dalam hal ini, membuat pengguna awal untuk aplikasi.
     */
    public function run(): void
    {
        // Membuat entri baru di tabel users menggunakan model User
        User::create([
            'name' => 'Administrator', // Nama pengguna yang akan dibuat, dalam hal ini 'Administrator'
            'email' => 'admin@gmail.com', // Alamat email pengguna, digunakan untuk login
            'password' => Hash::make('admin123'), // Kata sandi yang dienkripsi menggunakan Hash::make untuk keamanan
            'role' => 'admin', // Peran pengguna, dalam hal ini 'admin' untuk menunjukkan hak akses penuh
        ]);
    }
}