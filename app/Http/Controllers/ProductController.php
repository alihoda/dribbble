<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Image;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        $productCollection = Product::latest()->with(['user', 'tags', 'image'])->get();
        return ProductResource::collection($productCollection)->is_collection(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail | required | min:5',
            'description' => 'bail | required | min:10'
        ]);

        $request['user_id'] = $request->user()->id;
        // get id of tags
        $tags_id = Tag::getTagsId(json_decode($request->tags));

        $product = Product::create($request->all());
        $product->tags()->sync($tags_id);

        // Check for image in request
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnail');
            $product->image()->save(Image::make(['path' => $path]));
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => new ProductResource($product)
        ]);
    }

    public function show($product)
    {
        return new ProductResource(Product::with(['user', 'image', 'tags'])->findOrFail($product));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'min:5',
            'description' => 'min:10'
        ]);

        $tags_id = Tag::getTagsId(json_decode($request->tags));

        $product->update($request->all());
        $product->tags()->sync($tags_id);

        // Update user avatar if file is uploaded
        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('thumbnail');
            if ($product->image()) {
                Storage::delete($product->image->path);
                $product->image->path = $path;
                $product->image->save();
            } else {
                $product->image()->save(Image::make(['path' => $path]));
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => new ProductResource($product)
        ]);
    }

    public function destroy(Product $product)
    {
        if (!is_null($product->image)) {
            Storage::delete($product->image->path);
            $product->image()->delete();
        }
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
