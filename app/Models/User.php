<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'birth_data',
        'gender',
        'description',
        'is_admin',
        'resume_path',
        'remember_token'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function socialNetwork()
    {
        return $this->hasMany(SocialNetwork::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function avatar()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}
