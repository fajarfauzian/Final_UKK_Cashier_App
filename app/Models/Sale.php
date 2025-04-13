<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa model ini berada di folder App/Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory untuk membuat factory (data dummy) untuk testing
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas Model dasar dari Laravel Eloquent ORM
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Mengimpor tipe relasi BelongsTo untuk mendefinisikan relasi "milik" ke model lain
use Illuminate\Database\Eloquent\Relations\HasMany; // Mengimpor tipe relasi HasMany untuk mendefinisikan relasi "memiliki banyak" ke model lain

class Sale extends Model // Mendefinisikan kelas Sale yang mewarisi Model (Eloquent ORM)
{
    use HasFactory; // Menggunakan trait HasFactory agar model ini mendukung pembuatan data dummy melalui factory

    // Properti $fillable mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    // Ini mencegah pengisian kolom yang tidak diizinkan untuk alasan keamanan
    protected $fillable = [
        'user_id', // ID pengguna yang melakukan penjualan, biasanya foreign key ke tabel users
        'total_price', // Total harga dari penjualan
        'amount_paid', // Jumlah uang yang dibayarkan oleh pelanggan
        'change', // Kembalian yang diberikan ke pelanggan (amount_paid - total_price)
        'is_member', // Status keanggotaan pelanggan (true/false)
        'phone', // Nomor telepon pelanggan, bisa nullable tergantung kebutuhan
        'customer_name', // Nama pelanggan, untuk mencatat identitas pembeli
        'use_points', // Menunjukkan apakah pelanggan menggunakan poin untuk pembayaran (true/false)
    ];

    // Properti $casts digunakan untuk mengatur tipe data otomatis saat data diambil atau disimpan
    // Ini memastikan data dalam format yang konsisten
    protected $casts = [
        'total_price' => 'decimal:2', // Mengubah total_price menjadi desimal dengan 2 angka di belakang koma
        'amount_paid' => 'decimal:2', // Mengubah amount_paid menjadi desimal dengan 2 angka di belakang koma
        'change' => 'decimal:2', // Mengubah change menjadi desimal dengan 2 angka di belakang koma
        'is_member' => 'boolean', // Mengubah is_member menjadi tipe boolean (true/false)
        'use_points' => 'boolean', // Mengubah use_points menjadi tipe boolean (true/false)
    ];

    // Properti $with mendefinisikan relasi yang akan selalu dimuat (eager loading) saat model ini diambil
    // Ini mengurangi jumlah query database dengan memuat relasi secara otomatis
    protected $with = ['user', 'salesDetails']; // Memuat relasi user dan salesDetails setiap kali objek Sale diambil

    // Method user() mendefinisikan relasi "belongsTo" ke model User
    // Artinya setiap Sale dimiliki oleh satu User (misalnya kasir atau penjual)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class); // Mengembalikan definisi relasi ke model User berdasarkan user_id
    }

    // Method salesDetails() mendefinisikan relasi "hasMany" ke model SalesDetail
    // Artinya satu Sale bisa memiliki banyak SalesDetail (detail item yang dijual)
    public function salesDetails(): HasMany
    {
        return $this->hasMany(SalesDetail::class); // Mengembalikan definisi relasi ke model SalesDetail
    }
}