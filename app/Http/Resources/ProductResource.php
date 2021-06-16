<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;


class ProductResource extends JsonResource
{
    protected $is_collection;

    public function is_collection($flag)
    {
        $this->is_collection = $flag;
        return $this;
    }

    public function toArray($request)
    {
        $description =
            $this->is_collection
            ? Str::limit($this->description, 50, '...')
            : $this->description;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $description,
            'user' =>  [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'avatar' => new ImageResource($this->user->avatar)
            ],
            'createdAt' => Carbon::parse($this->created_at)->diffForHumans(),
            'image' => $this->when(!is_null($this->image), new ImageResource($this->image)),
            'tags' => $this->when(!is_null($this->tags), TagResource::collection($this->tags)),
        ];
    }

    public static function collection($resource)
    {
        return new ProductCollection($resource);
    }
}
