<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'product_id'];

    public function product()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    // Scopes

    public function scopeGetTagsId($query, $tags)
    {
        foreach ($tags as $tag) {
            $this->firstOrCreate(['name' => $tag]);
        }
        return $query->whereIn('name', $tags)->get()->pluck('id');
    }
}
