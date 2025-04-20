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
        $value = json_decode($value, true);

        return $value[app()->getLocale()] ?? array_values($value)[0] ?? '';
    }

    public function getDescriptionAttribute($value)
    {
        // if (is_string($value)) {
        //     return $value;
        // }
        $value = json_decode($value, true);

        return $value[app()->getLocale()] ?? array_values($value)[0] ?? '';
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
