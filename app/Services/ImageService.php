<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
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
        try {
            // Always save as WebP for better compression
            $filename = time() . '_' . uniqid() . '.webp';

            // Read the uploaded image
            $image = $this->imageManager->read($file);

            // Resize for full-size version (max 1200px width)
            $fullSize = clone $image;
            $fullSize->scale(width: 1200);

            // Save full-size image (WebP format with optimized quality)
            $fullSizeContent = (string) $fullSize->toWebp(quality: 80);
            unset($fullSize); // Free memory
            $fullSizeSaved = Storage::disk('public')->put(
                'products/' . $filename,
                $fullSizeContent
            );
            unset($fullSizeContent); // Free memory

            if (!$fullSizeSaved) {
                throw new \RuntimeException('Failed to save full-size image to storage');
            }

            // Create thumbnail (300px width)
            $thumbnail = clone $image;
            $thumbnail->scale(width: 300);
            unset($image); // Free memory

            // Save thumbnail (WebP format with optimized quality)
            $thumbnailContent = (string) $thumbnail->toWebp(quality: 75);
            unset($thumbnail); // Free memory
            $thumbnailSaved = Storage::disk('public')->put(
                'products/thumbnails/' . $filename,
                $thumbnailContent
            );
            unset($thumbnailContent); // Free memory

            if (!$thumbnailSaved) {
                // Clean up full-size image if thumbnail save fails
                Storage::disk('public')->delete('products/' . $filename);
                throw new \RuntimeException('Failed to save thumbnail image to storage');
            }

            return $filename;
        } catch (\Exception $e) {
            Log::error('ImageService upload failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
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

