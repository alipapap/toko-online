<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Http\Resources\StoreResource;

class StoreController extends Controller
{
    public function index()
    {
        return StoreResource::collection(Store::all());
    }

    public function show($id)
    {
        return new StoreResource(Store::find0rFail($id));
    }
}
