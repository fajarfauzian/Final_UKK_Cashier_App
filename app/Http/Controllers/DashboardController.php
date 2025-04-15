<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $data = [];

        if ($user->role === 'petugas') {
            $dailySales = Sale::whereDate('created_at', Carbon::today())->count();
            $memberSales = Sale::whereDate('created_at', Carbon::today())
                ->where('is_member', true)
                ->get();
            $nonMemberSales = Sale::whereDate('created_at', Carbon::today())
                ->where('is_member', false)
                ->get();

            $data = [
                'dailySales' => $dailySales,
                'memberSales' => $memberSales,
                'nonMemberSales' => $nonMemberSales,
                'lastUpdated' => Sale::whereDate('created_at', Carbon::today())->latest('updated_at')
                    ->first()?->updated_at?->format('d-m-Y H:i') ?? now()->format('d-m-Y H:i'),
            ];
        } elseif ($user->role === 'admin') {
            $totalSales = Sale::count();
            $data = [
                'salesData' => $this->getSalesData(),
                'productSales' => Product::withCount('salesDetails')
                    ->get()
                    ->map(fn($product) => [
                        'name' => $product->name,
                        'percentage' => $totalSales > 0 ? ($product->sales_details_count / $totalSales) * 100 : 0,
                    ]),
            ];
        } else {
            $data = [
                'totalProducts' => Product::count(),
                'totalSales' => Sale::count(),
                'totalUsers' => User::count(),
            ];
        }

        return view('dashboard', $data);
    }

    private function getSalesData()
    {
        return Sale::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(13))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(fn($item) => [
                'date' => Carbon::parse($item->date)->format('d-M-Y'),
                'total' => $item->total,
            ]);
    }
}
