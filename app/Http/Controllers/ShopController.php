<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShopController extends Controller
{
    /**
     * Display a listing of products in the shop.
     */
    public function index(Request $request): Response
    {
        $query = Product::with('images')
            ->where('sold_out', false)
            ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        // Filter by tags
        if ($request->has('tag') && $request->tag) {
            $query->where('tags', 'like', "%{$request->tag}%");
        }

        $products = $query->get()->map(function ($product) {
            return [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'tags' => $product->tags ? explode(',', $product->tags) : [],
                'price' => $product->price,
                'cover_image_url' => $product->cover_image_url,
            ];
        });

        // Get all unique tags
        $allTags = Product::whereNotNull('tags')
            ->pluck('tags')
            ->flatMap(fn($tags) => explode(',', $tags))
            ->map(fn($tag) => trim($tag))
            ->unique()
            ->sort()
            ->values();

        return Inertia::render('Shop/Index', [
            'products' => $products,
            'tags' => $allTags,
            'filters' => [
                'search' => $request->search,
                'tag' => $request->tag,
            ],
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product): Response
    {
        $product->load('images');

        return Inertia::render('Shop/Show', [
            'product' => [
                'id' => $product->id,
                'title' => $product->title,
                'description' => $product->description,
                'tags' => $product->tags ? explode(',', $product->tags) : [],
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

