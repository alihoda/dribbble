<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductTagResource;
use App\Models\Tag;

class TagController extends Controller
{

    public function index()
    {
        $tags = Tag::has('products')->withCount('products')->get();
        return ProductTagResource::collection($tags)->is_collection(true);
    }

    public function show($tag)
    {
        return new ProductTagResource(Tag::with(['products', 'products.user', 'products.tags'])
            ->withCount('products')
            ->findOrFail($tag));
    }
}
