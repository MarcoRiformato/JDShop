<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageService
{
    protected ImageManager $imageManager;

    public function __construct()
    {
        $this->imageManager = new ImageManager(new Driver());
    }

    /**
     * Upload and process an image.
     *
     * @param  UploadedFile  $file
     * @return string The filename of the uploaded image
     */
    public function uploadProductImage(UploadedFile $file): string
    {
        // Always save as WebP for better compression
        $filename = time() . '_' . uniqid() . '.webp';

        // Read the uploaded image
        $image = $this->imageManager->read($file);

        // Resize for full-size version (max 1200px width)
        $fullSize = clone $image;
        $fullSize->scale(width: 1200);

        // Create thumbnail (300px width)
        $thumbnail = clone $image;
        $thumbnail->scale(width: 300);

        // Save full-size image (WebP format with optimized quality)
        Storage::disk('public')->put(
            'products/' . $filename,
            (string) $fullSize->toWebp(quality: 80)
        );

        // Save thumbnail (WebP format with optimized quality)
        Storage::disk('public')->put(
            'products/thumbnails/' . $filename,
            (string) $thumbnail->toWebp(quality: 75)
        );

        return $filename;
    }

    /**
     * Delete an image and its thumbnail.
     *
     * @param  string  $filename
     * @return bool
     */
    public function deleteProductImage(string $filename): bool
    {
        $deleted = true;

        if (Storage::disk('public')->exists('products/' . $filename)) {
            $deleted = Storage::disk('public')->delete('products/' . $filename);
        }

        if (Storage::disk('public')->exists('products/thumbnails/' . $filename)) {
            Storage::disk('public')->delete('products/thumbnails/' . $filename);
        }

        return $deleted;
    }
}

