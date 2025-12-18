<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category_id = $request->get('category');

        $query = Product::with('category')->where('stok', '>', 0);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        if ($category_id) {
            $query->where('category_id', $category_id);
        }

        $products = $query->orderBy('nama')->paginate(12);
        $categories = Category::orderBy('nama')->get();

        return view('customer.shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        return view('customer.shop.show', compact('product'));
    }
}
