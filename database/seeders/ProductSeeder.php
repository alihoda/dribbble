<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();

        $productCount = (int)$this->command->ask('How many products would you like?', 10);

        Product::factory($productCount)->make()->each(function ($product) use ($users) {
            $product->user_id = $users->random()->id;
            $product->save();
        });
    }
}
