<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $authController;
    public function index(Request $request)
    {
        if (Auth::user()->role == 'admin') {
            // For admin users, default to 10 if not specified
            $perPage = $request->input('perPage', 5);
        } else {
            // For non-admin users, allow selection but default to 12
            $perPage = $request->input('perPage', 12);
            
            // Ensure perPage is one of the allowed values for non-admin users
            $allowedValues = [12, 20, 30, 50];
            if (!in_array($perPage, $allowedValues)) {
                $perPage = 12;
            }
        }
        
        $search = $request->input('search');
        
        $products = Product::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%");
        })->paginate($perPage);
        
        // Append query parameters for pagination links
        $products->appends(['perPage' => $perPage, 'search' => $search]);
        
        return view('products.index', compact('products'));
    }
    public function __construct(AuthController $authController)
    {
        $this->authController = $authController;
        $this->middleware('auth');
        
        // Restrict access to admin only for these routes
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== 'admin') {
                return redirect()->route('dashboard')->with('error', 'Anda tidak memiliki izin untuk mengakses halaman ini.');
            }
            return $next($request);
        })->except(['index', 'show']);
    }
    

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validated['image'] = $imagePath;
        }

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function select(Request $request)
    {
        $product = Product::find($request->product_id);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }
        return response()->json($product);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.edit', compact('product'));
    }

    public function editStock($id)
    {
        $product = Product::findOrFail($id);
        return view('products.update-stock', compact('product'));
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $product->update(['stock' => $request->stock]);

        return redirect()->route('products.index')->with('success', 'Stok berhasil diperbarui!');
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);
        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(null, 204);
    }
}
