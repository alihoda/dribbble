<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductTagCollection extends ResourceCollection
{
    protected $is_collection;

    public function is_collection($flag)
    {
        $this->is_collection = $flag;
        return $this;
    }

    public function toArray($request)
    {
        return $this->collection->map(function (ProductTagResource $resource) use ($request) {
            return $resource->is_collection($this->is_collection)->toArray(($request));
        })->all();
    }
}
