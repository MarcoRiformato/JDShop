<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'title' => 'LED Par Can Stage Lights (x12)',
                'description' => 'Set of 12 professional LED Par Can lights. RGB color mixing, DMX controllable. Perfect for stage lighting, events, and concerts. Gently used, fully functional. Includes mounting brackets.',
                'tags' => 'lighting,stage,led,equipment',
                'price' => 450.00,
                'sold_out' => false,
                'images' => 2,
            ],
            [
                'title' => 'Vintage Wooden Banquet Tables',
                'description' => 'Six beautiful vintage wooden banquet tables, 8ft x 3ft each. Solid construction with rustic finish. Perfect for weddings, corporate events, or outdoor gatherings. Minor wear adds character.',
                'tags' => 'furniture,tables,vintage,wood',
                'price' => 200.00,
                'sold_out' => false,
                'images' => 3,
            ],
            [
                'title' => 'Professional Fog Machine',
                'description' => 'High-output fog machine with wireless remote control. 1500W, heats up in 5 minutes. Creates amazing atmosphere for events. Includes 2L of fog fluid. Excellent condition.',
                'tags' => 'equipment,fog,effects,atmosphere',
                'price' => 180.00,
                'sold_out' => true,
                'images' => 2,
            ],
            [
                'title' => 'Velvet Drapes - Burgundy (Set)',
                'description' => 'Luxurious burgundy velvet drapes, 20ft x 10ft panels. Set of 6 panels with rod pockets. Heavy-duty fabric perfect for stage backdrops, room dividers, or elegant event decoration.',
                'tags' => 'drapes,props,fabric,backdrop',
                'price' => 320.00,
                'sold_out' => false,
                'images' => 2,
            ],
            [
                'title' => 'Folding Chiavari Chairs (x50)',
                'description' => 'Set of 50 elegant gold folding Chiavari chairs. Lightweight aluminum construction, cushioned seats. Perfect for weddings, galas, and upscale events. Some minor scratches.',
                'tags' => 'furniture,chairs,wedding,seating',
                'price' => 750.00,
                'sold_out' => false,
                'images' => 3,
            ],
            [
                'title' => 'Moving Head Spot Lights (x4)',
                'description' => 'Four professional moving head spot lights. 200W LED, gobo patterns, color wheel, DMX512. Create dynamic lighting effects. Recently serviced. Perfect working condition.',
                'tags' => 'lighting,moving-head,stage,professional',
                'price' => 1200.00,
                'sold_out' => false,
                'images' => 2,
            ],
            [
                'title' => 'Rustic Wooden Bar Counter',
                'description' => 'Beautiful rustic wooden bar counter, 10ft long. Reclaimed wood construction with industrial metal frame. Perfect for cocktail events, trade shows, or festival bars. Easy assembly.',
                'tags' => 'furniture,bar,wood,rustic',
                'price' => 380.00,
                'sold_out' => false,
                'images' => 3,
            ],
            [
                'title' => 'Decorative Mirror Balls (Set)',
                'description' => 'Set of 3 mirror disco balls - 12", 16", and 20" diameter. Includes motors and mounting hardware. Add sparkle and retro vibes to any event. All motors tested and working.',
                'tags' => 'props,disco,mirrors,decorative',
                'price' => 150.00,
                'sold_out' => false,
                'images' => 2,
            ],
        ];

        foreach ($products as $productData) {
            $imageCount = $productData['images'];
            unset($productData['images']);

            $product = Product::firstOrCreate(
                ['title' => $productData['title']],
                $productData
            );

            // Add Lorem Picsum images if product was just created and has no images
            if ($product->wasRecentlyCreated || $product->images()->count() === 0) {
                $this->addPlaceholderImages($product, $imageCount);
            }
        }
    }

    /**
     * Add placeholder images from Lorem Picsum
     */
    private function addPlaceholderImages(Product $product, int $count): void
    {
        // Ensure storage directories exist
        Storage::disk('public')->makeDirectory('products');
        Storage::disk('public')->makeDirectory('products/thumbnails');

        for ($i = 0; $i < $count; $i++) {
            // Generate unique seed for consistent but different images
            $seed = $product->id * 100 + $i;
            
            // Download full-size image (1200x800)
            $fullImageUrl = "https://picsum.photos/seed/{$seed}/1200/800";
            $thumbnailUrl = "https://picsum.photos/seed/{$seed}/300/200";
            
            $filename = "placeholder_{$product->id}_{$i}_" . time() . '.jpg';

            try {
                // Download and save full-size image
                $fullImage = Http::timeout(10)->get($fullImageUrl);
                if ($fullImage->successful()) {
                    Storage::disk('public')->put('products/' . $filename, $fullImage->body());
                }

                // Download and save thumbnail
                $thumbnail = Http::timeout(10)->get($thumbnailUrl);
                if ($thumbnail->successful()) {
                    Storage::disk('public')->put('products/thumbnails/' . $filename, $thumbnail->body());
                }

                // Create image record
                $product->images()->create([
                    'filename' => $filename,
                    'is_cover' => $i === 0, // First image is cover
                ]);
            } catch (\Exception $e) {
                // Continue if image download fails
                continue;
            }
        }
    }
}

