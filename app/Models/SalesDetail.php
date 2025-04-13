<?php

namespace App\Models; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa model ini berada di folder App/Models

use Illuminate\Database\Eloquent\Factories\HasFactory; // Mengimpor trait HasFactory untuk mendukung pembuatan data dummy melalui factory
use Illuminate\Database\Eloquent\Model; // Mengimpor kelas Model dasar dari Laravel Eloquent ORM
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Mengimpor tipe relasi BelongsTo untuk mendefinisikan relasi "milik" ke model lain

class SalesDetail extends Model // Mendefinisikan kelas SalesDetail yang mewarisi Model (Eloquent ORM)
{
    use HasFactory; // Menggunakan trait HasFactory agar model ini mendukung pembuatan data dummy untuk testing atau seeding

    // Properti $fillable mendefinisikan kolom-kolom yang boleh diisi secara massal (mass assignment)
    // Ini adalah langkah keamanan untuk mencegah pengisian kolom yang tidak diizinkan
    protected $fillable = [
        'sale_id', // ID penjualan yang terkait, berfungsi sebagai foreign key ke tabel sales
        'product_id', // ID produk yang dijual, berfungsi sebagai foreign key ke tabel products
        'unit_price', // Harga satuan produk pada saat penjualan
        'quantity', // Jumlah produk yang dijual dalam transaksi ini
        'subtotal' // Total harga untuk item ini (unit_price * quantity)
    ];

    // Properti $casts digunakan untuk mengatur tipe data otomatis saat data diambil atau disimpan
    // Ini memastikan konsistensi tipe data dalam aplikasi
    protected $casts = [
        'unit_price' => 'decimal:2', // Mengubah unit_price menjadi desimal dengan 2 angka di belakang koma
        'subtotal' => 'decimal:2', // Mengubah subtotal menjadi desimal dengan 2 angka di belakang koma
        'quantity' => 'integer', // Mengubah quantity menjadi bilangan bulat (integer)
    ];

    // Method sale() mendefinisikan relasi "belongsTo" ke model Sale
    // Artinya setiap SalesDetail dimiliki oleh satu Sale (penjualan tertentu)
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class); // Mengembalikan definisi relasi ke model Sale berdasarkan sale_id
    }

    // Method product() mendefinisikan relasi "belongsTo" ke model Product
    // Artinya setiap SalesDetail terkait dengan satu Product (produk yang dijual)
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class); // Mengembalikan definisi relasi ke model Product berdasarkan product_id
    }
}