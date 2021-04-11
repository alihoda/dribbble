<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->text(10),
            'description' => $this->faker->realText(),
        ];
    }

    public function userAdmin()
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'test',
                'email' => 'test@test.com',
                'is_admin' => true
            ];
        });
    }
}
