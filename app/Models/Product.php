<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'sku',
        'is_in_stock',
        'stock_quantity',
        'category_id',
        'keywords',
    ];

    protected $casts = [
        'name' => 'json',
        'description' => 'json',
        'keywords' => 'json',
        'price' => 'decimal:2',
        'is_in_stock' => 'boolean',
        'stock_quantity' => 'integer',
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
        return $this->is_in_stock && $this->stock_quantity > 0;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
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
