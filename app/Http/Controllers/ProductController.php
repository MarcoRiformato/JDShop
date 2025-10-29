<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Image;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                // Get images with cover image first, then at least 2 more if available (up to 3 total)
                $imagesCollection = $product->images->sortByDesc('is_cover');
                $imagesToShow = $imagesCollection->take($product->images->count() >= 3 ? 3 : $product->images->count());
                
                return [
                    'id' => $product->id,
                    'title' => $product->title,
                    'description' => $product->description,
                    'tags' => $product->tags,
                    'price' => $product->price,
                    'sold_out' => $product->sold_out,
                    'cover_image_url' => $product->cover_image_url,
                    'images_count' => $product->images->count(),
                    'images' => $imagesToShow->map(fn($image) => [
                        'id' => $image->id,
                        'url' => $image->url,
                        'thumbnail_url' => $image->thumbnail_url,
                        'is_cover' => $image->is_cover,
                    ]),
                    'discount_percentage' => $product->discount_percentage,
                    'discount_start_date' => $product->discount_start_date,
                    'discount_end_date' => $product->discount_end_date,
                    'original_price' => $product->original_price,
                    'has_active_discount' => $product->has_active_discount,
                    'discounted_price' => $product->discounted_price,
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
            ->with('success', 'Prodotto creato con successo! Ora aggiungi delle immagini.')
            ->with('product', $product);
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
                'discount_percentage' => $product->discount_percentage,
                'discount_start_date' => $product->discount_start_date,
                'discount_end_date' => $product->discount_end_date,
                'original_price' => $product->original_price,
                'has_active_discount' => $product->has_active_discount,
                'discounted_price' => $product->discounted_price,
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
        $validated = $request->validated();
        
        // If price is being manually updated, clear discount fields to avoid confusion
        if (isset($validated['price']) && $validated['price'] != $product->price) {
            $validated['discount_percentage'] = null;
            $validated['discount_start_date'] = null;
            $validated['discount_end_date'] = null;
            $validated['original_price'] = null;
        }
        
        $product->update($validated);

        return redirect()->route('products.edit', $product)
            ->with('success', 'Prodotto aggiornato con successo!');
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
            ->with('success', 'Prodotto eliminato con successo!');
    }

    /**
     * Upload an image for the product.
     */
    public function uploadImage(Request $request, Product $product)
    {
        try {
            $request->validate([
                'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            ], [
                'image.required' => 'Seleziona un file immagine.',
                'image.image' => 'Il file deve essere un\'immagine.',
                'image.mimes' => 'Formato non supportato. Usa: JPEG, PNG, JPG, o WebP.',
                'image.max' => 'Il file è troppo grande. Dimensione massima: 10MB.',
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Image upload failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'product_id' => $product->id,
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il caricamento dell\'immagine: ' . $e->getMessage(),
            ], 500);
        }
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
            'message' => 'Immagine eliminata con successo!',
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
            'message' => 'Immagine di copertina aggiornata con successo!',
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
                'discount_percentage' => $product->discount_percentage,
                'discount_start_date' => $product->discount_start_date,
                'discount_end_date' => $product->discount_end_date,
                'original_price' => $product->original_price,
                'has_active_discount' => $product->has_active_discount,
                'discounted_price' => $product->discounted_price,
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

