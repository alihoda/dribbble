<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class ProductUserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => Str::limit($this->description, 100, '...'),
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'tags' => $this->when(true, TagResource::collection($this->tags)),
            'image' => $this->when(true, $this->image->url()),
        ];
    }
}
