<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->query('search'), fn ($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate((int) $request->query('per_page', 10));

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['status'] = $data['stock'] > 0 ? 'active' : 'out_of_stock';

        return response()->json(Product::create($data), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);
        $data['status'] = $data['stock'] > 0 ? 'active' : 'out_of_stock';
        $product->update($data);

        return response()->json($product->fresh());
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]);
    }
}
