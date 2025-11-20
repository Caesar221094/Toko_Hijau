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
}
