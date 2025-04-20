<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'orders_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'account_id',
        'item',
        'price',
        'discount',
        'vat',
        'total',
        'returned',
        'qty',
        'returned_qty',
        'is_free',
        'is_returned',
        'options',
        'refund_id',
        'warehouse_move_id',
    ];

    protected $casts = [
        'qty' => 'double',
        'price' => 'double',
        'discount' => 'double',
        'vat' => 'double',
        'total' => 'double',
        'returned' => 'double',
        'returned_qty' => 'double',
        'is_free' => 'boolean',
        'is_returned' => 'boolean',
        'options' => 'json',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}
