<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use TomatoPHP\FilamentEcommerce\Models\Product as ProdcutBase;

class Product extends ProdcutBase
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'category_id',
        'farmer_id',
    ];

    public function getNameAttribute($value)
    {
        if (is_array($value)) {
            return $value['en'] ?? '';
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded['en'] ?? '';
            }
        }

        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (is_array($value)) {
            return $value['en'] ?? '';
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded['en'] ?? '';
            }
        }

        return $value;
    }

    public function getKeywordsAttribute($value)
    {
        if (is_array($value)) {
            return $value['en'] ?? [];
        }

        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded['en'] ?? [];
            }
        }

        return [];
    }

    public function canBeAddedToCart(): bool
    {
        if ($this->is_in_stock) {
            return true;
        }

        return false;
    }

    /**
     * Get the maximum quantity that can be added to cart
     */
    public function getMaxCartQuantity(): int
    {
        if ($this->has_unlimited_stock) {
            return $this->has_max_cart ? $this->max_cart : PHP_INT_MAX;
        }

        if ($this->has_max_cart) {
            return min($this->max_cart, $this->stock_quantity);
        }

        return $this->stock_quantity;
    }

    /**
     * Get the minimum quantity that should be added to cart
     */
    public function getMinCartQuantity(): int
    {
        return $this->has_max_cart ? $this->min_cart : 1;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('products')
            ->singleFile();
    }
}
