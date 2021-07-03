<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductTagResource extends JsonResource
{
    protected $is_collection;

    public function is_collection($flag)
    {
        $this->is_collection = $flag;
        return $this;
    }

    public function toArray($request)
    {
        $products = $this->is_collection ? $this->products()->latest()->paginate(3) : $this->products;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->products_count,
            'products' => ProductResource::collection($products)->is_collection(true)
        ];
    }


    public static function collection($resource)
    {
        return new ProductTagCollection($resource);
    }
}
