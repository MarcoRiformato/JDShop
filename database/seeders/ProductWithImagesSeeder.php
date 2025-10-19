<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class ProductWithImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure storage directories exist
        Storage::disk('public')->makeDirectory('products');
        Storage::disk('public')->makeDirectory('products/thumbnails');

        $productsData = [
            [
                'product' => [
                    'title' => 'Classic White T-Shirt',
                    'description' => 'A comfortable and stylish white t-shirt made from 100% cotton. Perfect for everyday wear.',
                    'tags' => 'clothing,t-shirt,casual,white',
                    'price' => 19.99,
                    'sold_out' => false,
                ],
                'image_url' => 'https://picsum.photos/seed/tshirt1/800/800',
            ],
            [
                'product' => [
                    'title' => 'Blue Denim Jeans',
                    'description' => 'Premium quality denim jeans with a modern fit. Durable and comfortable for all-day wear.',
                    'tags' => 'clothing,jeans,denim,blue',
                    'price' => 49.99,
                    'sold_out' => false,
                ],
                'image_url' => 'https://picsum.photos/seed/jeans1/800/800',
            ],
            [
                'product' => [
                    'title' => 'Black Leather Jacket',
                    'description' => 'Stylish genuine leather jacket. Perfect for adding edge to any outfit.',
                    'tags' => 'clothing,jacket,leather,black',
                    'price' => 149.99,
                    'sold_out' => true,
                ],
                'image_url' => 'https://picsum.photos/seed/jacket1/800/800',
            ],
            [
                'product' => [
                    'title' => 'Running Sneakers',
                    'description' => 'High-performance running sneakers with excellent cushioning and support.',
                    'tags' => 'shoes,sneakers,sports,running',
                    'price' => 79.99,
                    'sold_out' => false,
                ],
                'image_url' => 'https://picsum.photos/seed/shoes1/800/800',
            ],
            [
                'product' => [
                    'title' => 'Cotton Hoodie',
                    'description' => 'Cozy cotton hoodie with adjustable drawstring. Available in multiple colors.',
                    'tags' => 'clothing,hoodie,casual,cotton',
                    'price' => 39.99,
                    'sold_out' => false,
                ],
                'image_url' => 'https://picsum.photos/seed/hoodie1/800/800',
            ],
        ];

        foreach ($productsData as $data) {
            // Create or find product
            $product = Product::firstOrCreate(
                ['title' => $data['product']['title']],
                $data['product']
            );

            // Skip if product already has images
            if ($product->images()->count() > 0) {
                $this->command->info("Product '{$product->title}' already has images, skipping...");
                continue;
            }

            try {
                // Download image from Lorem Picsum
                $this->command->info("Downloading image for '{$product->title}'...");
                $imageContent = Http::timeout(10)->get($data['image_url'])->body();

                if ($imageContent) {
                    $filename = time() . '_' . uniqid() . '.jpg';

                    // Save full-size image
                    Storage::disk('public')->put('products/' . $filename, $imageContent);

                    // Create a simple thumbnail (for now, same image - in production use Intervention)
                    Storage::disk('public')->put('products/thumbnails/' . $filename, $imageContent);

                    // Create image record
                    $product->images()->create([
                        'filename' => $filename,
                        'is_cover' => true,
                    ]);

                    $this->command->info("✓ Image added for '{$product->title}'");
                } else {
                    $this->command->warn("Failed to download image for '{$product->title}'");
                }
            } catch (\Exception $e) {
                $this->command->error("Error downloading image for '{$product->title}': " . $e->getMessage());
            }

            // Small delay to avoid rate limiting
            usleep(200000); // 0.2 seconds
        }

        $this->command->info('Products with images seeded successfully!');
    }
}

