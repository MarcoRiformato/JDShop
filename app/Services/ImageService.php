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
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // Read the uploaded image
        $image = $this->imageManager->read($file);

        // Resize for full-size version (max 1200px width)
        $fullSize = clone $image;
        $fullSize->scale(width: 1200);

        // Create thumbnail (300px width)
        $thumbnail = clone $image;
        $thumbnail->scale(width: 300);

        // Save full-size image
        Storage::disk('public')->put(
            'products/' . $filename,
            (string) $fullSize->toJpeg(quality: 85)
        );

        // Save thumbnail
        Storage::disk('public')->put(
            'products/thumbnails/' . $filename,
            (string) $thumbnail->toJpeg(quality: 80)
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

