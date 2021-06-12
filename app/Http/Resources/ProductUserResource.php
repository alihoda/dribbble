<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ProductUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'tags' => $this->when(true, TagResource::collection($this->tags)),
            'image' => $this->when(true, new ImageResource($this->image)),
        ];
    }
}
