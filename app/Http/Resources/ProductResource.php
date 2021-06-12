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
            'user' =>  [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'avatar' => new ImageResource($this->user->avatar)
            ],
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'image' => $this->when(true, new ImageResource($this->image)),
            'tags' => $this->when(true, TagResource::collection($this->tags)),
        ];
    }
}
