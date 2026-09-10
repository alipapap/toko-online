<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['store'])
            ->when($request->q, function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->q . '%');
            })
            ->when($request->store_id, function ($q) use ($request) {
                $q->where('store_id', $request->store_id);
            });

        $products = $query->paginate(12);
        $stores = Store::select('id', 'name')->get();

        return response()->json([
            'products' => $products,
            'stores' => $stores,
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['store', 'detail']);
        return response()->json($product);
    }
}