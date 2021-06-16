<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductTagResource extends JsonResource
{
    public function toArray($request)
    {
        $products = $this->products()->latest()->paginate(3);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'count' => $this->products_count,
            'products' => ProductResource::collection($products)->is_collection(true)
        ];
    }
}
