<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'tags',
        'price',
        'sold_out',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'sold_out' => 'boolean',
        ];
    }

    /**
     * Get the images for the product.
     */
    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Get the cover image for the product.
     */
    public function getCoverImage(): ?Image
    {
        return $this->images()->where('is_cover', true)->first() 
            ?? $this->images()->first();
    }

    /**
     * Get the cover image URL.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        $coverImage = $this->getCoverImage();
        
        if ($coverImage) {
            return $coverImage->url;
        }
        
        // Return placeholder image from Lorem Picsum if no image exists
        return "https://picsum.photos/seed/{$this->id}/800/800";
    }
}

