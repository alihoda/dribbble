<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function updating(User $user)
    {
        Cache::tags(['product'])->forget('products');
        Cache::tags(['product'])->forget("product-{$user->id}");
    }

    public function deleting(User $user)
    {
        Cache::tags(['product'])->forget('products');
        Cache::tags(['product'])->forget("product-{$user->id}");
    }
}
