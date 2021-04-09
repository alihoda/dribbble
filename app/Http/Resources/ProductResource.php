<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->user_id,
            'created_at' => Carbon::parse($this->created_at)->diffForHumans(),
            'images' => $this->when(true, ImageResource::collection($this->images)),
            'tags' => $this->when(true, TagResource::collection($this->tags)),
        ];
    }
}
