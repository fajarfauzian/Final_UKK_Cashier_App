<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa model ini berada di folder App/Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory untuk mendukung pembuatan data dummy melalui factory
use Illuminate\Database\Eloquent\Relations\HasMany; // Mengimpor tipe relasi HasMany untuk mendefinisikan relasi "memiliki banyak" ke model lain
use Illuminate\Foundation\Auth\User as Authenticatable; // Mengimpor kelas Authenticatable sebagai dasar untuk autentikasi pengguna
use Illuminate\Notifications\Notifiable; // Mengimpor trait Notifiable untuk mendukung pengiriman notifikasi (email, SMS, dll.)
use Illuminate\Database\Eloquent\SoftDeletes; // Mengimpor trait SoftDeletes untuk mendukung penghapusan lembut (soft delete)

class User extends Authenticatable // Mendefinisikan kelas User yang mewarisi Authenticatable (untuk autentikasi)
{
    use HasFactory, Notifiable, SoftDeletes; // Menggunakan trait HasFactory (factory), Notifiable (notifikasi), dan SoftDeletes (penghapusan lembut)

    // Properti $fillable mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    // Ini mencegah pengisian kolom sensitif yang tidak diizinkan
    protected $fillable = [
        'name', // Nama pengguna, biasanya ditampilkan di aplikasi
        'email', // Alamat email pengguna, digunakan untuk login dan notifikasi
        'password', // Kata sandi pengguna, akan dienkripsi otomatis oleh Laravel
        'role', // Peran pengguna (misalnya admin, kasir), untuk kontrol akses
        'is_active', // Status aktif pengguna (true/false), menunjukkan apakah akun bisa digunakan
    ];

    // Properti $hidden mendefinisikan kolom-kolom yang tidak akan ditampilkan saat model diubah ke array/JSON
    // Ini melindungi data sensitif dari eksposur
    protected $hidden = [
        'password', // Kata sandi disembunyikan agar tidak terlihat di output
        'remember_token', // Token "ingat saya" untuk autentikasi, juga disembunyikan
    ];

    // Properti $casts digunakan untuk mengatur tipe data otomatis saat data diambil atau disimpan
    // Memastikan data dalam format yang konsisten
    protected $casts = [
        'email_verified_at' => 'datetime', // Mengubah email_verified_at menjadi objek DateTime untuk manipulasi tanggal
        'is_active' => 'boolean', // Mengubah is_active menjadi tipe boolean (true/false)
    ];

    // Method sales() mendefinisikan relasi "hasMany" ke model Sale
    // Artinya satu User bisa memiliki banyak Sale (penjualan yang dilakukan oleh pengguna ini)
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class); // Mengembalikan definisi relasi ke model Sale berdasarkan user_id
    }
}