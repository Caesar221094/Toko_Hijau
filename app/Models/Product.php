<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // tambahkan semua field yang digunakan
    protected $fillable = [
        'nama',
        'category_id',
        'harga',
        'deskripsi',
        'stok',
        'foto',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi: Product has many OrderProducts (One-to-Many)
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }

    /**
     * Relasi: Product belongs to many Orders through OrderProducts (Many-to-Many)
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_products')
                    ->withPivot('quantity', 'price', 'subtotal')
                    ->withTimestamps();
    }
}
