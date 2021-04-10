<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductObserver
{

    public function updating(Product $product)
    {
        Cache::tags(['product'])->forget('products');
        Cache::tags(['product'])->forget("product-{$product->id}");
    }

    public function deleting(Product $product)
    {
        Cache::tags(['product'])->forget('products');
        Cache::tags(['product'])->forget("product-{$product->id}");
    }
}
