<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'Classic White T-Shirt',
                'description' => 'A comfortable and stylish white t-shirt made from 100% cotton. Perfect for everyday wear.',
                'tags' => 'clothing,t-shirt,casual,white',
                'price' => 19.99,
                'sold_out' => false,
            ],
            [
                'title' => 'Blue Denim Jeans',
                'description' => 'Premium quality denim jeans with a modern fit. Durable and comfortable for all-day wear.',
                'tags' => 'clothing,jeans,denim,blue',
                'price' => 49.99,
                'sold_out' => false,
            ],
            [
                'title' => 'Black Leather Jacket',
                'description' => 'Stylish genuine leather jacket. Perfect for adding edge to any outfit.',
                'tags' => 'clothing,jacket,leather,black',
                'price' => 149.99,
                'sold_out' => true,
            ],
            [
                'title' => 'Running Sneakers',
                'description' => 'High-performance running sneakers with excellent cushioning and support.',
                'tags' => 'shoes,sneakers,sports,running',
                'price' => 79.99,
                'sold_out' => false,
            ],
            [
                'title' => 'Cotton Hoodie',
                'description' => 'Cozy cotton hoodie with adjustable drawstring. Available in multiple colors.',
                'tags' => 'clothing,hoodie,casual,cotton',
                'price' => 39.99,
                'sold_out' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['title' => $product['title']],
                $product
            );
        }
    }
}

