<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        
        $products = Product::where('name', 'like', "%{$query}%")
            ->orWhere('price', 'like', "%{$query}%")
            ->get();

        $sales = Sale::where('total_price', 'like', "%{$query}%")
            ->orWhere('customer_name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->get();

        $salesDetails = SalesDetail::whereHas('product', fn($q) => $q->where('name', 'like', "%{$query}%"))
            ->orWhere('unit_price', 'like', "%{$query}%")
            ->orWhere('quantity', 'like', "%{$query}%")
            ->get();

        $users = User::where('name', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->get();

        return view('search.results', compact('products', 'sales', 'salesDetails', 'users', 'query'));
    }
}