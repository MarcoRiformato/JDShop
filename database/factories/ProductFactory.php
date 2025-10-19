<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'tags' => implode(',', fake()->words(3)),
            'price' => fake()->randomFloat(2, 10, 500),
            'sold_out' => fake()->boolean(20), // 20% chance of being sold out
        ];
    }
}

