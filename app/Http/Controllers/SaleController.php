<?php

namespace App\Http\Controllers;

use App\Models\Sale; // Mengimpor model Sale untuk mengakses dan memanipulasi data di tabel 'sales' di database
use App\Models\SalesDetail; // Mengimpor model SalesDetail untuk mengelola detail penjualan (produk yang dibeli) di tabel 'sales_details'
use App\Models\Product; // Mengimpor model Product untuk mengakses data produk seperti nama, harga, dan stok di tabel 'products'
use App\Models\User; // Mengimpor model User untuk mengakses data pengguna (misalnya kasir atau admin) di tabel 'users'
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani data yang dikirim melalui HTTP request (GET/POST)
use App\Exports\SalesExport; // Mengimpor kelas SalesExport yang merupakan implementasi ekspor data penjualan ke Excel
use Maatwebsite\Excel\Facades\Excel; // Mengimpor facade Excel dari package Maatwebsite untuk mempermudah ekspor data ke file Excel
use Barryvdh\DomPDF\Facade\Pdf as PDF; // Mengimpor facade PDF dari package DomPDF untuk menghasilkan dokumen PDF seperti struk penjualan
use Illuminate\Support\Facades\Log; // Import the Log facade

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $search = $request->query('search');
        $query = Sale::with(['user', 'salesDetails.product'])->orderBy('id', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhere('total_price', 'like', "%{$search}%")
                    ->orWhere('created_at', 'like', "%{$search}%");
            });
        }

        $sales = $query->paginate($perPage);

        $currentPage = $sales->currentPage();
        $startNumber = ($currentPage - 1) * $perPage + 1;

        return view('sales.index', compact('sales', 'startNumber'));
    }

    public function details($id)
    {
        $sale = Sale::with(['user', 'details.product'])->findOrFail($id);
        $details = $sale->details;

        return view('sales.details', compact('sale', 'details'));
    }

    public function getSoldAttribute()
    {
        return $this->hasMany(SalesDetail::class)->sum('quantity');
    }

    public function create()
    {
        $products = Product::withSum('salesDetails as sold', 'quantity')
            ->where('stock', '>', 0)
            ->get()
            ->map(function ($product) {
                $product->quantity = 1; // Menetapkan quantity default untuk form
                return $product;
            });

        $users = User::all();

        return view('sales.create', compact('products', 'users'));
    }

    public function generatePdf($id)
    {
        $sale = Sale::with(['salesDetails.product', 'user'])->findOrFail($id);

        $selectedProducts = $sale->salesDetails->map(function ($detail) {
            return (object)[
                'name' => $detail->product->name ?? 'Unknown Product',
                'price' => $detail->unit_price,
                'quantity' => $detail->quantity,
                'subtotal' => $detail->unit_price * $detail->quantity
            ];
        });

        $totalPrice = $sale->total_price; // Total harga setelah diskon (jika ada)
        $amountPaid = $sale->amount_paid; // Jumlah yang dibayar oleh pelanggan
        $change = $sale->change; // Kembalian yang diberikan
        $is_member = $sale->is_member; // Status membership pelanggan (true/false)
        $customerName = $sale->customer_name ?? 'Guest'; // Nama pelanggan, default 'Guest' jika null
        $phone = $sale->phone; // Nomor telepon pelanggan, bisa null

        $pdf = PDF::loadView('sales.receipt_pdf', compact(
            'sale', 'selectedProducts', 'totalPrice', 'amountPaid', 'change', 'is_member', 'customerName', 'phone'
        ));

        // Mengatur ukuran kertas PDF ke A4 dengan orientasi portrait (tegak)
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('struk_penjualan_' . $id . '.pdf');
    }

    public function showMemberForm()
    {
        return redirect()->route('sales.create')
            ->with('error', 'Silakan pilih produk terlebih dahulu dari form penjualan.');
    }

    public function checkMembership(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array', // Array ID produk wajib ada
            'products.*' => 'exists:products,id', // Setiap ID harus ada di tabel 'products'
            'quantities' => 'required|array', // Array kuantitas wajib ada
            'quantities.*' => 'integer|min:0', // Setiap kuantitas harus integer dan minimal 0
        ]);

        $selectedProducts = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = 0;
        $hasQuantity = false;

        foreach ($selectedProducts as $index => $product) {
            $quantity = $quantities[$index] ?? 0;

            if ($quantity > 0) {
                $hasQuantity = true;
            }

            if ($quantity > 0 && $product->stock < $quantity) {
                return back()->withErrors([
                    'stock' => "Stok produk {$product->name} tidak mencukupi."
                ])->withInput();
            }

            $subtotal = $product->price * $quantity;

            $totalPrice += $subtotal;
        }

        if (!$hasQuantity) {
            return back()->withErrors([
                'quantity' => 'Harap pilih setidaknya satu produk dengan kuantitas lebih dari 0 sebelum melanjutkan transaksi.'
            ])->withInput();
        }

        return view('sales.member', [
            'selectedProducts' => $selectedProducts,
            'quantities' => $quantities,
            'totalPrice' => $totalPrice,
        ]);
    }

    public function processMember(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
            'phone' => 'required|string|max:15',
            'is_member' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $products = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = 0;

        foreach ($products as $index => $product) {
            $quantity = $quantities[$index];
            $totalPrice += $product->price * $quantity;
        }

        $amountPaid = $validated['amount_paid'];
        $change = $amountPaid - $totalPrice;

        if ($change < 0) {
            return redirect()->route('sales.create')
                ->withErrors(['amount_paid' => 'Jumlah yang dibayar tidak mencukupi total harga.'])
                ->withInput();
        }

        $previousPurchase = false;
        if ($request->has('customer_name') && $request->input('customer_name')) {
            $previousPurchase = Sale::whereRaw('LOWER(customer_name) = ?', [strtolower($request->input('customer_name'))])
                ->where('is_member', true)
                ->exists();
        }

        return view('sales.process', [
            'products' => $products,
            'quantities' => $quantities,
            'phone' => $validated['phone'],
            'amount_paid' => $validated['amount_paid'],
            'totalPrice' => $totalPrice,
            'previousPurchase' => $previousPurchase,
        ]);
    }

    public function processTransaction(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
            'is_member' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'use_points' => 'required|boolean',
            'phone' => 'nullable|string|max:15',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $products = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = 0;

        foreach ($products as $index => $product) {
            $quantity = $quantities[$index];
            $totalPrice += $product->price * $quantity;
        }

        $isMember = (bool) $validated['is_member'];
        $usePoints = (bool) $validated['use_points'];

        $previousPurchase = Sale::whereRaw('LOWER(customer_name) = ?', [strtolower($validated['customer_name'])])
            ->where('is_member', true)
            ->exists();

        $finalPrice = ($isMember && $usePoints && $previousPurchase) ? $totalPrice * 0.9 : $totalPrice;
        $amountPaid = $validated['amount_paid'];
        $change = $amountPaid - $finalPrice;

        if ($change < 0) {
            return redirect()->route('sales.create')
                ->withErrors(['amount_paid' => 'Jumlah yang dibayar tidak mencukupi total harga.'])
                ->withInput();
        }

        $sale = Sale::create([
            'user_id' => $validated['user_id'],
            'total_price' => $finalPrice,
            'amount_paid' => $amountPaid,
            'change' => $change,
            'is_member' => $isMember,
            'phone' => $validated['phone'],
            'customer_name' => $validated['customer_name'],
            'use_points' => $usePoints,
        ]);

        foreach ($products as $index => $product) {
            $quantity = $quantities[$index];
            if ($quantity > 0) {
                SalesDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ]);
                $product->decrement('stock', $quantity);
            }
        }

        $selectedProducts = $products->map(function ($product, $index) use ($quantities) {
            $product->quantity = $quantities[$index];
            return $product;
        });

        return view('sales.transaction', [
            'selectedProducts' => $selectedProducts,
            'totalPrice' => $finalPrice,
            'customer_name' => $validated['customer_name'],
            'is_member' => $isMember,
            'amountPaid' => $amountPaid,
            'change' => $change,
            'sale' => $sale,
        ]);
    }

    public function checkMembershipStatus(Request $request)
    {
        try {
            $customerName = $request->input('customer_name');
            $hasPreviousPurchase = false;

            if ($customerName) {
                $hasPreviousPurchase = Sale::whereRaw('LOWER(customer_name) = ?', [strtolower($customerName)])
                    ->where('is_member', true)
                    ->exists();
            }

            return response()->json([
                'hasPreviousPurchase' => $hasPreviousPurchase,
            ]);
        } catch (\Exception $e) {
            Log::error('Error in checkMembershipStatus: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memeriksa status poin: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id', // ID pengguna wajib dan harus ada
            'is_member' => 'required|boolean', // Status member wajib
            'products' => 'required|array', // Array ID produk wajib
            'products.*' => 'exists:products,id', // Setiap ID harus ada di tabel 'products'
            'quantities' => 'required|array', // Array kuantitas wajib
            'quantities.*' => 'integer|min:1', // Setiap kuantitas harus integer >= 1
            'customer_name' => 'required|string|max:255', // Nama pelanggan wajib, maks 255 karakter
            'use_points' => 'required|boolean', // Penggunaan poin wajib
            'amount_paid' => 'required|numeric|min:0', // Jumlah dibayar wajib, numerik >= 0
        ]);

        $products = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = 0;

        foreach ($products as $index => $product) {
            $quantity = $quantities[$index];
            if ($product->stock < $quantity) {
                return back()->withErrors([
                    'stock' => "Stok produk {$product->name} tidak mencukupi."
                ])->withInput();
            }
            $totalPrice += $product->price * $quantity;
        }

        $finalPrice = $validated['is_member'] ? $totalPrice * 0.9 : $totalPrice;
        $change = $validated['amount_paid'] - $finalPrice;

        if ($change < 0) {
            return back()->withErrors([
                'amount_paid' => 'Jumlah yang dibayar tidak mencukupi total harga.'
            ])->withInput();
        }

        try {
            $sale = Sale::create([
                'user_id' => $validated['user_id'],
                'total_price' => $finalPrice,
                'amount_paid' => $validated['amount_paid'],
                'change' => $change,
                'is_member' => $validated['is_member'],
                'phone' => $request->input('phone'), // Nomor telepon opsional dari input
                'customer_name' => $validated['customer_name'],
                'use_points' => $validated['use_points'],
            ]);

            foreach ($products as $index => $product) {
                $quantity = $quantities[$index];
                SalesDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'unit_price' => $product->price,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ]);
                $product->decrement('stock', $quantity);
            }

            return redirect()->route('sales.index')
                ->with('success', 'Transaksi berhasil disimpan');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Gagal menyimpan transaksi: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function export()
    {
        return Excel::download(new SalesExport, 'sales_' . date('Ymd_His') . '.xlsx');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'salesDetails.product']);
        return view('sales.details', compact('sale'));
    }

    public function destroy(Sale $sale)
    {
        try {
            foreach ($sale->salesDetails as $detail) {
                Product::where('id', 'detail->product_id')
                    ->increment('stock', $detail->quantity); // Tambah stok kembali
            }
            $sale->salesDetails()->delete();
            $sale->delete();

            return redirect()->route('sales.index')
                ->with('success', 'Penjualan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('sales.index')
                ->with('error', 'Gagal menghapus penjualan: ' . $e->getMessage());
        }
    }
}