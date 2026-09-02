<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['store_id', 'name', 'price', 'stock'];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function detail()
    {
        return $this->hasOne(ProductDetail::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    protected static function booted(): void
    {
        static::created(function (Product $product) {
            $product->detail()->create([
                'description' => ' ',
                'weight' => 0,
            ]);
        });

        static::deleting(function (Product $product) {
            $product->detail()->delete();
        });
    }
}