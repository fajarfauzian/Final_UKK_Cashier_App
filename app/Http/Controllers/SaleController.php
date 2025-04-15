<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SalesDetail;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Log;

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
              ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$search}%"))
              ->orWhere('total_price', 'like', "%{$search}%")
              ->orWhere('created_at', 'like', "%{$search}%");
        });
    }

    $dailySales = Sale::whereDate('created_at', today())->count();
    $memberSales = Sale::whereDate('created_at', today())
        ->where('is_member', true)
        ->get();
    $nonMemberSales = Sale::whereDate('created_at', today())
        ->where('is_member', false)
        ->get();

    $sales = $query->paginate($perPage);
    $startNumber = ($sales->currentPage() - 1) * $perPage + 1;

    // Perbaiki view untuk mengarah ke sales.index
    return view('sales.index', compact('sales', 'startNumber', 'dailySales', 'memberSales', 'nonMemberSales'));
}





    public function details($id)
    {
        $sale = Sale::with(['user', 'details.product'])->findOrFail($id);
        return view('sales.details', ['sale' => $sale, 'details' => $sale->details]);
    }

    public function create()
    {
        $products = Product::withSum('salesDetails as sold', 'quantity')
            ->where('stock', '>', 0)
            ->get()
            ->map(fn($product) => tap($product, function($p) { $p->quantity = 1; }));

        return view('sales.create', [
            'products' => $products,
            'users' => User::all()
        ]);
    }

    public function generatePdf($id)
    {
        $sale = Sale::with(['salesDetails.product', 'user'])->findOrFail($id);

        $data = [
            'sale' => $sale,
            'selectedProducts' => $sale->salesDetails->map(fn($detail) => (object)[
                'name' => $detail->product->name ?? 'Unknown Product',
                'price' => $detail->unit_price,
                'quantity' => $detail->quantity,
                'subtotal' => $detail->unit_price * $detail->quantity
            ]),
            'totalPrice' => $sale->total_price,
            'amountPaid' => $sale->amount_paid,
            'change' => $sale->change,
            'is_member' => $sale->is_member,
            'customerName' => $sale->customer_name ?? 'Guest',
            'phone' => $sale->phone
        ];

        $pdf = PDF::loadView('sales.receipt_pdf', $data)->setPaper('a4', 'portrait');
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
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
        ]);

        $selectedProducts = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = 0;
        $hasQuantity = false;

        foreach ($selectedProducts as $index => $product) {
            $quantity = $quantities[$index] ?? 0;
            $hasQuantity = $hasQuantity || $quantity > 0;

            if ($quantity > 0 && $product->stock < $quantity) {
                return back()->withErrors([
                    'stock' => "Stok produk {$product->name} tidak mencukupi."
                ])->withInput();
            }

            $totalPrice += $product->price * $quantity;
        }

        if (!$hasQuantity) {
            return back()->withErrors([
                'quantity' => 'Harap pilih setidaknya satu produk dengan kuantitas lebih dari 0.'
            ])->withInput();
        }

        return view('sales.member', compact('selectedProducts', 'quantities', 'totalPrice'));
    }

    public function processMember(Request $request)
    {
        $validated = $this->validateProductSelection($request);

        $products = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = $this->calculateTotalPrice($products, $quantities);
        $amountPaid = $validated['amount_paid'];

        if ($amountPaid < $totalPrice) {
            return redirect()->route('sales.create')
                ->withErrors(['amount_paid' => 'Jumlah yang dibayar tidak mencukupi total harga.'])
                ->withInput();
        }

        $previousPurchase = false;
        if ($request->has('customer_name') && $request->input('customer_name')) {
            $previousPurchase = $this->checkPreviousPurchase($request->input('customer_name'));
        }

        return view('sales.process', [
            'products' => $products,
            'quantities' => $quantities,
            'phone' => $validated['phone'],
            'amount_paid' => $amountPaid,
            'totalPrice' => $totalPrice,
            'previousPurchase' => $previousPurchase,
        ]);
    }

    public function processTransaction(Request $request)
    {
        $validated = $this->validateTransactionRequest($request);

        $products = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];
        $totalPrice = $this->calculateTotalPrice($products, $quantities);

        $isMember = (bool) $validated['is_member'];
        $usePoints = (bool) $validated['use_points'];
        $previousPurchase = $this->checkPreviousPurchase($validated['customer_name']);

        $finalPrice = ($isMember && $usePoints && $previousPurchase) ? $totalPrice * 0.9 : $totalPrice;
        $amountPaid = $validated['amount_paid'];
        $change = $amountPaid - $finalPrice;

        if ($change < 0) {
            return redirect()->route('sales.create')
                ->withErrors(['amount_paid' => 'Jumlah yang dibayar tidak mencukupi total harga.'])
                ->withInput();
        }

        $sale = $this->createSale($validated, $finalPrice, $amountPaid, $change);
        $this->createSalesDetails($sale, $products, $quantities);

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
            $hasPreviousPurchase = $customerName ? $this->checkPreviousPurchase($customerName) : false;

            return response()->json(['hasPreviousPurchase' => $hasPreviousPurchase]);
        } catch (\Exception $e) {
            Log::error('Error in checkMembershipStatus: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat memeriksa status poin: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransactionRequest($request);

        $products = Product::whereIn('id', $validated['products'])->get();
        $quantities = $validated['quantities'];

        // Periksa stok
        foreach ($products as $index => $product) {
            $quantity = $quantities[$index];
            if ($product->stock < $quantity) {
                return back()->withErrors([
                    'stock' => "Stok produk {$product->name} tidak mencukupi."
                ])->withInput();
            }
        }

        $totalPrice = $this->calculateTotalPrice($products, $quantities);
        $finalPrice = $validated['is_member'] ? $totalPrice * 0.9 : $totalPrice;
        $change = $validated['amount_paid'] - $finalPrice;

        if ($change < 0) {
            return back()->withErrors([
                'amount_paid' => 'Jumlah yang dibayar tidak mencukupi total harga.'
            ])->withInput();
        }

        try {
            $sale = $this->createSale($validated, $finalPrice, $validated['amount_paid'], $change);
            $this->createSalesDetails($sale, $products, $quantities);

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
                Product::where('id', $detail->product_id)
                    ->increment('stock', $detail->quantity);
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



    private function validateProductSelection(Request $request)
    {
        return $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:0',
            'phone' => 'required|string|max:15',
            'is_member' => 'required|boolean',
            'user_id' => 'required|exists:users,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);
    }

    private function validateTransactionRequest(Request $request)
    {
        return $request->validate([
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
    }


    private function calculateTotalPrice($products, $quantities)
    {
        $totalPrice = 0;
        foreach ($products as $index => $product) {
            $quantity = $quantities[$index] ?? 0;
            $totalPrice += $product->price * $quantity;
        }
        return $totalPrice;
    }

    private function checkPreviousPurchase($customerName)
    {
        return Sale::whereRaw('LOWER(customer_name) = ?', [strtolower($customerName)])
            ->where('is_member', true)
            ->exists();
    }

    private function createSale($data, $finalPrice, $amountPaid, $change)
    {
        return Sale::create([
            'user_id' => $data['user_id'],
            'total_price' => $finalPrice,
            'amount_paid' => $amountPaid,
            'change' => $change,
            'is_member' => $data['is_member'],
            'phone' => $data['phone'] ?? null,
            'customer_name' => $data['customer_name'],
            'use_points' => $data['use_points'],
        ]);
    }

    private function createSalesDetails($sale, $products, $quantities)
    {
        foreach ($products as $index => $product) {
            $quantity = $quantities[$index] ?? 0;
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
    }
}
