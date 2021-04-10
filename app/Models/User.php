<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

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

    // Local Scopes

    public function scopeCreateUser($query, $request)
    {
        if ($request->hasFile('resume')) {
            $request['resume_path'] = $request->file('resume')->store('resumes');
        }
        $request['password'] = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);
        return $this->create($request->all());
    }
}
