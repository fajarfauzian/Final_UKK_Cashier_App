<?php

namespace App\Models; // Menempatkan kelas ini di namespace Models dalam aplikasi Laravel

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory untuk mendukung pembuatan data dummy via factory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas Model sebagai dasar untuk model Eloquent
use Illuminate\Database\Eloquent\SoftDeletes; // Mengimpor trait SoftDeletes untuk mendukung penghapusan sementara (soft delete)

class Product extends Model // Nama kelas model, mewakili entitas produk dalam aplikasi
{
    // Menggunakan trait HasFactory dan SoftDeletes
    // 'HasFactory' memungkinkan penggunaan factory untuk seeding atau testing
    // 'SoftDeletes' menambahkan fungsi soft delete, menandai data sebagai "dihapus" dengan kolom 'deleted_at' tanpa menghapus fisik
    use HasFactory, SoftDeletes;

    // Properti $fillable menentukan kolom mana yang bisa diisi secara massal (mass assignment)
    // Ini adalah fitur keamanan Eloquent untuk mencegah pengisian kolom yang tidak diinginkan
    protected $fillable = [
        'name', // Nama produk, biasanya string
        'price', // Harga produk, biasanya numerik
        'stock', // Jumlah stok produk, biasanya integer
        'image' // Path atau nama file gambar produk, biasanya string
    ];

    // Properti $casts menentukan tipe data kustom untuk kolom tertentu saat diambil atau disimpan
    // Berguna untuk memastikan format data konsisten saat berinteraksi dengan model
    protected $casts = [
        'price' => 'decimal:2', // Mengkonversi kolom 'price' menjadi tipe decimal dengan 2 angka di belakang koma
        // Contoh: 1234.5 di database akan menjadi 1234.50 saat diambil
    ];

    // Method ini mendefinisikan relasi one-to-many antara Product dan SalesDetail
    // Artinya satu produk bisa terkait dengan banyak detail penjualan
    public function salesDetails()
    {
        // 'hasMany' menunjukkan relasi one-to-many dengan model SalesDetail
        // Parameter pertama adalah nama kelas model terkait (SalesDetail::class)
        // Secara default, Eloquent mengasumsikan foreign key adalah 'product_id' di tabel 'sales_details'
        // dan primary key adalah 'id' di tabel 'products'
        return $this->hasMany(SalesDetail::class);
    }
}