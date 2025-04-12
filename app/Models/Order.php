<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Database\Factories\OrderFactory;
use Filament\Forms\Components\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItems::class);
    }


    public function newsScope(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::New);
    }

    public function processingScope(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Processing);
    }


    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }
}
