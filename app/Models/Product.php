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
        'discount_percentage',
        'discount_start_date',
        'discount_end_date',
        'original_price',
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
            'discount_percentage' => 'decimal:2',
            'discount_start_date' => 'datetime',
            'discount_end_date' => 'datetime',
            'original_price' => 'decimal:2',
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

    /**
     * Check if the product has an active discount.
     */
    public function getHasActiveDiscountAttribute(): bool
    {
        if (!$this->discount_percentage || !$this->discount_start_date) {
            return false;
        }

        $now = now();
        
        // Check if discount has started
        if ($now->lt($this->discount_start_date)) {
            return false;
        }

        // Check if discount has ended (if end date is set)
        if ($this->discount_end_date && $now->gt($this->discount_end_date)) {
            return false;
        }

        return true;
    }

    /**
     * Check if the product has a future discount (scheduled but not yet active).
     */
    public function getHasFutureDiscountAttribute(): bool
    {
        if (!$this->discount_percentage || !$this->discount_start_date) {
            return false;
        }

        $now = now();
        
        // Check if discount is scheduled for the future
        return $now->lt($this->discount_start_date);
    }

    /**
     * Calculate the discounted price (floored).
     */
    public function getDiscountedPriceAttribute(): ?float
    {
        if (!$this->has_active_discount || !$this->original_price) {
            return null;
        }

        return floor($this->original_price * (1 - ($this->discount_percentage / 100)));
    }

    /**
     * Get the effective price (discounted if active, otherwise original).
     */
    public function getEffectivePriceAttribute(): float
    {
        if ($this->has_active_discount && $this->discounted_price) {
            return (float) $this->discounted_price;
        }

        return (float) ($this->original_price ?? $this->price);
    }
}

