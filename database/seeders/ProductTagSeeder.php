<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    public function run()
    {
        $tagCount = Tag::all()->count();

        if ($tagCount === 0) {
            $this->command->info('No tags found, skipping assigning tags to products');
            return;
        }

        $countMin = (int)$this->command->ask('Minimum tags on blog products?', 1);
        $countMax = min((int)$this->command->ask('Maximum tags on blog products?', $tagCount), $tagCount);

        Product::all()->each(function (Product $product) use ($countMin, $countMax) {
            $count = random_int($countMin, $countMax);
            $tags = Tag::inRandomOrder()->take($count)->get()->pluck('id');
            $product->tags()->sync($tags);
        });
    }
}
