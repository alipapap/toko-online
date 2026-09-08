<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::with('user')->withCount('products')->latest()->get();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        // Hanya tampilkan user yang belum punya toko (karena relasi 1-1)
        $users = User::doesntHave('store')->get();
        return view('stores.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:stores,user_id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        Store::create($validated);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    public function show(Store $store)
    {
        $store->load('user', 'products', 'employees');
        return view('stores.show', compact('store'));
    }

    public function edit(Store $store)
    {
        $users = User::where('id', $store->user_id)->orWhereDoesntHave('store')->get();
        return view('stores.edit', compact('store', 'users'));
    }

    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:stores,user_id,' . $store->id,
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $store->update($validated);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil diupdate.');
    }

    public function destroy(Store $store)
    {
        $store->delete();
        return redirect()->route('stores.index')->with('success', 'Toko berhasil dihapus.');
    }
}