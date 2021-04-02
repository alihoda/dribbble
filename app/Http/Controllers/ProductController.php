<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    public function index()
    {
        return ProductResource::collection(Product::paginate(10));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'bail | required | min:5',
            'description' => 'bail | required | min:10'
        ]);

        $request['user_id'] = $request->user()->id;
        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product
        ]);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'title' => 'min:5',
            'description' => 'min:10'
        ]);

        $product->update($request->all());

        return response()->json([
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
