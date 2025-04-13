<?php

namespace App\Http\Controllers;

use App\Models\Sale; // Mengimpor model Sale untuk mengakses data penjualan
use App\Models\Product; // Mengimpor model Product untuk mengakses data produk
use App\Models\User; // Mengimpor model User untuk mengakses data pengguna
use Carbon\Carbon; // Mengimpor Carbon untuk manipulasi tanggal dan waktu
use Illuminate\Support\Facades\Auth; // Mengimpor Auth untuk autentikasi pengguna

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'petugas') {
            $dailySales = Sale::whereDate('created_at', Carbon::today())->count();
            $lastUpdated = Sale::whereDate('created_at', Carbon::today())->latest('updated_at')->first()?->updated_at;

            return view('dashboard', [
                'dailySales' => $dailySales, // Jumlah penjualan harian
                'lastUpdated' => $lastUpdated ? $lastUpdated->format('d-m-Y H:i') : now()->format('d-m-Y H:i'), // Waktu terakhir diperbarui atau waktu saat ini jika tidak ada data
            ]);
        } 
        elseif ($user->role === 'admin') {
            $startDate = Carbon::today()->subDays(13);

            $salesData = Sale::selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->where('created_at', '>=', $startDate) // Filter berdasarkan tanggal mulai
                ->groupBy('date') // Kelompokkan berdasarkan tanggal
                ->orderBy('date') // Urutkan berdasarkan tanggal
                ->get()
                ->map(function ($item) { // Format data untuk ditampilkan
                    return [
                        'date' => Carbon::parse($item->date)->format('d-M-Y'), // Format tanggal
                        'total' => $item->total, // Jumlah penjualan per hari
                    ];
                });

            $totalSales = Sale::count(); // Total semua penjualan
            $productSales = Product::withCount('salesDetails') // Menghitung jumlah detail penjualan per produk
                ->get()
                ->map(function ($product) use ($totalSales) { // Format data produk
                    return [
                        'name' => $product->name, // Nama produk
                        'percentage' => $totalSales > 0 ? ($product->sales_details_count / $totalSales) * 100 : 0, // Persentase penjualan produk
                    ];
                });

            return view('dashboard', [
                'salesData' => $salesData, // Data penjualan harian
                'productSales' => $productSales, // Data persentase penjualan produk
            ]);
        }

        $totalProducts = Product::count(); // Total jumlah produk
        $totalSales = Sale::count(); // Total jumlah penjualan
        $totalUsers = User::count(); // Total jumlah pengguna

        return view('dashboard', [
            'totalProducts' => $totalProducts, // Jumlah produk
            'totalSales' => $totalSales, // Jumlah penjualan
            'totalUsers' => $totalUsers, // Jumlah pengguna
        ]);
    }
}