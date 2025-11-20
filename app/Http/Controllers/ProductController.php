<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $query = Product::with('category');

        if ($search) {
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
        }

        $products = $query->orderBy('id','desc')->paginate(10)->withQueryString();

        return view('product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('nama')->get();
        return view('product.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama','category_id','harga','deskripsi','stok']);

        if ($request->hasFile('foto')) {
            // simpan ke disk 'public' di folder 'produk'
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success','Produk dibuat');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('nama')->get();
        return view('product.edit', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'harga' => 'nullable|numeric',
            'deskripsi' => 'nullable|string',
            'stok' => 'nullable|integer',
            'foto' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama','category_id','harga','deskripsi','stok']);

        if ($request->hasFile('foto')) {
            // hapus file lama jika ada
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success','Produk diperbarui');
    }

    public function destroy(Product $product)
    {
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();

        return redirect()->route('products.index')->with('success','Produk dihapus');
    }
}
