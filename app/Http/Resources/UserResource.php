<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class UserResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'description' => $this->description,
            'avatar' => $this->when(!is_null($this->avatar), new ImageResource($this->avatar)),
            'products' => $this->when(true, ProductUserResource::collection($this->products))
        ];
    }
}
