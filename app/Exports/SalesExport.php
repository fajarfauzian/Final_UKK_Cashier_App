<?php

namespace App\Exports; // Mendefinisikan namespace untuk kelas ini, menunjukkan bahwa kelas ini berada di folder App/Exports

use App\Models\Sale; // Mengimpor model Sale untuk mengakses data penjualan dari database
use Maatwebsite\Excel\Concerns\FromCollection; // Mengimpor concern FromCollection untuk mengekspor data dari koleksi
use Maatwebsite\Excel\Concerns\WithHeadings; // Mengimpor concern WithHeadings untuk menentukan judul kolom di file Excel

// Mendefinisikan kelas SalesExport yang mengimplementasikan dua interface: FromCollection dan WithHeadings
class SalesExport implements FromCollection, WithHeadings
{
    // Method collection() mendefinisikan data yang akan diekspor ke Excel
    // Mengembalikan koleksi data yang akan menjadi isi dari file Excel
    public function collection()
    {
        // Mengambil semua data dari model Sale beserta relasi 'user' dan 'salesDetails' menggunakan eager loading
        // Kemudian memetakan (map) setiap entri Sale ke dalam array dengan format yang diinginkan
        return Sale::with('user', 'salesDetails')->get()->map(function ($sale) {
            return [
                'ID' => $sale->id, // Kolom ID penjualan, diambil langsung dari atribut id
                // Kolom Nama Pelanggan: Jika pelanggan adalah member dan memiliki nama, gunakan nama tersebut; jika tidak, gunakan 'NON-MEMBER'
                'Nama Pelanggan' => $sale->is_member && $sale->customer_name ? $sale->customer_name : 'NON-MEMBER',
                // Kolom Tanggal Penjualan: Format tanggal pembuatan (created_at) menjadi 'dd-mm-yyyy hh:mm'
                'Tanggal Penjualan' => $sale->created_at->format('d-m-Y H:i'),
                // Kolom Total Harga: Format total_price dengan prefix 'Rp', tanpa desimal, menggunakan pemisah ribuan titik
                'Total Harga' => 'Rp ' . number_format($sale->total_price, 0, ',', '.'),
                // Kolom Jumlah Dibayar: Format amount_paid dengan prefix 'Rp', tanpa desimal, menggunakan pemisah ribuan titik
                'Jumlah Dibayar' => 'Rp ' . number_format($sale->amount_paid, 0, ',', '.'),
                // Kolom Kembalian: Format change dengan prefix 'Rp', tanpa desimal, menggunakan pemisah ribuan titik
                'Kembalian' => 'Rp ' . number_format($sale->change, 0, ',', '.'),
                // Kolom Dibuat Oleh: Nama pengguna yang membuat penjualan, diambil dari relasi user
                'Dibuat Oleh' => $sale->user->name,
            ];
        });
    }

    // Method headings() mendefinisikan judul kolom untuk file Excel
    // Mengembalikan array yang berisi nama-nama kolom sesuai urutan data di collection()
    public function headings(): array
    {
        return [
            'ID', // Judul kolom untuk ID penjualan
            'Nama Pelanggan', // Judul kolom untuk nama pelanggan
            'Tanggal Penjualan', // Judul kolom untuk tanggal dan waktu penjualan
            'Total Harga', // Judul kolom untuk total harga penjualan
            'Jumlah Dibayar', // Judul kolom untuk jumlah yang dibayarkan
            'Kembalian', // Judul kolom untuk kembalian
            'Dibuat Oleh', // Judul kolom untuk nama pengguna yang membuat penjualan
        ];
    }
}