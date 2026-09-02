<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with(['store', 'detail'])
            ->when($request->q, fn ($q) => $q->where('name', 'like', '%' . $request->q . '%'))
            ->when($request->store_id, fn ($q) => $q->where('store_id', $request->store_id))
            ->where('stock', '>', 0)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $stores = Store::orderBy('name')->get();

        return view('frontend.products.index', compact('products', 'stores'));
    }

    public function show(Product $product)
    {
        $product->load(['store', 'detail']);
        return view('frontend.products.show', compact('product'));
    }
}