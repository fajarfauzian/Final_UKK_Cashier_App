<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        $search = $request->input('search');

        $products = Product::when($search, fn($query) => $query->where('name', 'like', "%{$search}%"))
            ->paginate($perPage)
            ->appends(['perPage' => $perPage, 'search' => $search]);
        
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);
        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function select(Request $request)
    {
        $product = Product::find($request->product_id);
        return $product
            ? response()->json($product)
            : response()->json(['message' => 'Produk tidak ditemukan'], 404);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function edit($id)
    {
        return view('products.edit', ['product' => Product::findOrFail($id)]);
    }

    public function editStock($id)
    {
        return view('components.update-stock', ['product' => Product::findOrFail($id)]);
    }

    public function updateStock(Request $request, $id)
    {
        $request->validate(['stock' => 'required|integer|min:0']);
        Product::findOrFail($id)->update(['stock' => $request->stock]);
        return redirect()->route('products.index')->with('success', 'Stok berhasil diperbarui!');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, false);

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

    private function validateProduct(Request $request, bool $includeStock = true)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ];

        if ($includeStock) {
            $rules['stock'] = 'required|integer|min:0';
        }

        return $request->validate($rules);
    }
}
