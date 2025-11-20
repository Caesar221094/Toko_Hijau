<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'category_id',
        'harga',
        'deskripsi',
        'stok',
        'foto',
    ];

    // relasi ke Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
