<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Image;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(protected ImageService $imageService)
    {
    }

    /**
     * Display a listing of the products.
     */
    public function index(): Response
    {
        $products = Product::with('images')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'tags' => $product->tags,
                    'price' => $product->price,
                    'sold_out' => $product->sold_out,
                    'cover_image_url' => $product->cover_image_url,
                    'images_count' => $product->images->count(),
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/ProductCreate');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create($request->validated());

        return redirect()->route('products.edit', $product)
            ->with('success', 'Product created successfully! Now add some images.');
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product): Response
    {
        $product->load('images');

        return Inertia::render('Admin/ProductEdit', [
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'tags' => $product->tags,
                'price' => $product->price,
                'sold_out' => $product->sold_out,
                'images' => $product->images->map(fn($image) => [
                    'id' => $image->id,
                    'url' => $image->url,
                    'thumbnail_url' => $image->thumbnail_url,
                    'is_cover' => $image->is_cover,
                ]),
            ],
        ]);
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.edit', $product)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        // Delete all associated images
        foreach ($product->images as $image) {
            $this->imageService->deleteProductImage($image->filename);
        }

        $product->delete();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Upload an image for the product.
     */
    public function uploadImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $filename = $this->imageService->uploadProductImage($request->file('image'));

        // If this is the first image, make it the cover
        $isCover = $product->images()->count() === 0;

        $image = $product->images()->create([
            'filename' => $filename,
            'is_cover' => $isCover,
        ]);

        return response()->json([
            'success' => true,
            'image' => [
                'id' => $image->id,
                'url' => $image->url,
                'thumbnail_url' => $image->thumbnail_url,
                'is_cover' => $image->is_cover,
            ],
        ]);
    }

    /**
     * Delete an image.
     */
    public function deleteImage(Image $image)
    {
        $this->imageService->deleteProductImage($image->filename);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully!',
        ]);
    }

    /**
     * Set an image as the cover image.
     */
    public function setCoverImage(Image $image)
    {
        // Remove cover status from all images of this product
        $image->product->images()->update(['is_cover' => false]);

        // Set this image as cover
        $image->update(['is_cover' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Cover image updated successfully!',
        ]);
    }

    /**
     * Get product details for preview (JSON API endpoint).
     */
    public function show(Product $product)
    {
        $product->load('images');

        return response()->json([
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'tags' => $product->tags,
                'price' => $product->price,
                'sold_out' => $product->sold_out,
                'images' => $product->images->map(fn($image) => [
                    'id' => $image->id,
                    'url' => $image->url,
                    'thumbnail_url' => $image->thumbnail_url,
                    'is_cover' => $image->is_cover,
                ]),
            ],
        ]);
    }
}

