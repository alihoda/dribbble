<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductListTagResource;
use App\Http\Resources\ProductTagResource;
use App\Models\Tag;

class TagController extends Controller
{

    public function index()
    {
        return ProductListTagResource::collection(Tag::has('products')->withCount('products')->get());
    }

    public function show($tag)
    {
        return new ProductTagResource(Tag::with(['products', 'products.user', 'products.tags'])->findOrFail($tag));
    }
}
