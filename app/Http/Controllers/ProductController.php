<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        return ProductResource::collection(Product::with(['user', 'tags', 'images'])->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail | required | min:5',
            'description' => 'bail | required | min:10'
        ]);

        $request['user_id'] = $request->user()->id;
        // get id of tags
        $tags_id = Tag::getTagsId($request->input('tags'));

        $product = Product::create($request->all());
        $product->tags()->sync($tags_id);

        // Check for image in request
        if ($request->hasFile('thumbnail')) {
            foreach ($request->file('thumbnail') as $image) {
                $path = $image->store('thumbnails');
                $product->images()->save(Image::make(['path' => $path]));
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'data' => new ProductResource($product)
        ]);
    }

    public function show($product)
    {
        return Cache::tags(['product'])
            ->remember("product-{$product}", now()->addMinute(), function () use ($product) {
                $prod = Product::with(['user', 'images', 'tags'])->findOrFail($product);
                return new ProductResource($prod);
            });
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'title' => 'min:5',
            'description' => 'min:10'
        ]);

        $tags_id = Tag::getTagsId($request->input('tags'));
        $product->update($request->all());
        $product->tags()->sync($tags_id);

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        foreach ($product->images as $image) {
            Storage::delete($image->path);
        }
        $product->images()->delete();
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
