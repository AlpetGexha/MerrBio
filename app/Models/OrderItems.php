<?php

namespace App\Models;

use Database\Factories\OrderItemsFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItems extends Model
{
    /** @use HasFactory<OrderItemsFactory> */
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class);
    }
}
