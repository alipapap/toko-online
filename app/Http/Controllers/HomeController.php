<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
            $query = Product::query()->with('store');
    
            // Filter pencarian nama produk
            if ($request->filled('q')) {
                $query->where('name', 'like', '%' . $request->input('q') . '%');
            }
    
            // Filter berdasarkan toko
            if ($request->filled('store_id')) {
                $query->where('store_id', $request->input('store_id'));
            }
    
            $products = $query->latest()->get();
            $stores   = Store::orderBy('name')->get();
    
            return view('home.index', compact('products', 'stores'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
