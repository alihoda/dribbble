<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialNetwork extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'username', 'url', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
