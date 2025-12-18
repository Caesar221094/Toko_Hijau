<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Relasi: OrderProduct belongs to Order (Many-to-One)
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relasi: OrderProduct belongs to Product (Many-to-One)
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
