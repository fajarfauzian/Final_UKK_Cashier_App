<?php

namespace App\Http\Controllers;

use App\Models\Sale; // Mengimpor model Sale untuk mengakses data penjualan di tabel 'sales'
use App\Models\SalesDetail; // Mengimpor model SalesDetail untuk mengelola detail penjualan di tabel 'sales_details'
use App\Models\Product; // Mengimpor model Product untuk mengakses dan memanipulasi data produk di tabel 'products'
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani data yang dikirim melalui HTTP request (GET/POST)

class SalesDetailController extends Controller
{
    public function showDetails(Sale $sale)
    {
        $details = $sale->salesDetails()->with('product')->get();

        return view('sales.details', compact('sale', 'details'));
    }

    public function update(Request $request, SalesDetail $salesDetail)
    {
        $validated = $request->validate([
            'unit_price' => 'sometimes|required|numeric|min:0',
            'quantity' => 'sometimes|required|integer|min:1',
        ]);

        $oldQuantity = $salesDetail->quantity;

        if (isset($validated['quantity']) || isset($validated['unit_price'])) {
            $validated['subtotal'] = ($validated['unit_price'] ?? $salesDetail->unit_price)
                * ($validated['quantity'] ?? $salesDetail->quantity);
        }

        $salesDetail->update($validated);

        if (isset($validated['quantity'])) {
            $difference = $validated['quantity'] - $oldQuantity;
            Product::where('id', $salesDetail->product_id)->decrement('stock', $difference);
        }

        return response()->json($salesDetail);
    }

    public function destroy(SalesDetail $salesDetail)
    {
        Product::where('id', $salesDetail->product_id)->increment('stock', $salesDetail->quantity);
        $salesDetail->delete();

        return response()->json(null, 204);
    }
}