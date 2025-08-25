<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'sku' => strtoupper($this->faker->bothify('SKU-####-??')),
            'name' => $this->faker->words(3, true),
            'price' => $this->faker->randomFloat(3, 1, 9999),
        ];
    }
}
