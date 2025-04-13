<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani data yang dikirim melalui HTTP request (GET/POST)
use App\Models\Product; // Mengimpor model Product untuk mengakses dan mencari data di tabel 'products'
use App\Models\Sale; // Mengimpor model Sale untuk mengakses dan mencari data di tabel 'sales'
use App\Models\SalesDetail; // Mengimpor model SalesDetail untuk mengakses dan mencari data di tabel 'sales_details'
use App\Models\User; // Mengimpor model User untuk mengakses dan mencari data di tabel 'users'

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'like', "%{$query}%") // Mencari produk yang nama-nya mengandung kata kunci
            ->orWhere('price', 'like', "%{$query}%") // Atau harga-nya mengandung kata kunci
            ->get(); // Menjalankan query dan mengembalikan koleksi semua produk yang cocok

        $sales = Sale::where('total_price', 'like', "%{$query}%") // Mencari penjualan dengan total harga yang mengandung kata kunci
            ->orWhere('customer_name', 'like', "%{$query}%") // Atau nama pelanggan yang mengandung kata kunci
            ->orWhere('phone', 'like', "%{$query}%") // Atau nomor telepon yang mengandung kata kunci
            ->get(); // Menjalankan query dan mengembalikan koleksi semua penjualan yang cocok

        $salesDetails = SalesDetail::whereHas('product', function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%"); // Mencari produk dengan nama yang mengandung kata kunci
        })->orWhere('unit_price', 'like', "%{$query}%") // Atau harga satuan yang mengandung kata kunci
          ->orWhere('quantity', 'like', "%{$query}%") // Atau kuantitas yang mengandung kata kunci
          ->get(); // Menjalankan query dan mengembalikan koleksi semua detail penjualan yang cocok

        $users = User::where('name', 'like', "%{$query}%") // Mencari pengguna dengan nama yang mengandung kata kunci
            ->orWhere('email', 'like', "%{$query}%") // Atau email yang mengandung kata kunci
            ->get(); // Menjalankan query dan mengembalikan koleksi semua pengguna yang cocok

        return view('search.results', compact('products', 'sales', 'salesDetails', 'users', 'query'));
    }
}