<?php

namespace App\Http\Controllers;

use App\Mail\ContactInquiry;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        // Get related products based on similar tags
        $relatedProducts = collect();
        if ($product->tags) {
            $productTags = explode(',', $product->tags);
            $productTags = array_map('trim', $productTags);
            
            // Find products that share at least one tag
            $relatedProducts = Product::where('id', '!=', $product->id)
                ->where('sold_out', false)
                ->get()
                ->filter(function($p) use ($productTags) {
                    if (!$p->tags) return false;
                    $pTags = array_map('trim', explode(',', $p->tags));
                    return count(array_intersect($productTags, $pTags)) > 0;
                })
                ->sortByDesc(function($p) use ($productTags) {
                    // Sort by number of matching tags
                    $pTags = array_map('trim', explode(',', $p->tags));
                    return count(array_intersect($productTags, $pTags));
                })
                ->take(4)
                ->map(function($p) {
                    return [
                        'id' => $p->id,
                        'title' => $p->title,
                        'price' => $p->price,
                        'cover_image_url' => $p->cover_image_url,
                    ];
                })
                ->values();
        }

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
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Send contact email for a product inquiry.
     */
    public function sendContactEmail(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
            'product_id' => 'required|exists:products,id',
            'product_title' => 'required|string',
            'product_price' => 'required|numeric',
        ]);

        // Log the contact request
        Log::info('Contact form submission', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'product' => $validated['product_title'],
            'message' => $validated['message'],
        ]);

        try {
            // Send email to marco.riformato@gmail.com
            Mail::to('marco.riformato@gmail.com')->send(new ContactInquiry($validated));
            
            Log::info('Contact email sent successfully to marco.riformato@gmail.com', [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send contact email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't throw the error to the user, just log it
        }

        return back()->with('success', 'Messaggio inviato con successo!');
    }
}

