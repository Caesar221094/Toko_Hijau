<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $products = Product::with('category')
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                  ->orWhere('deskripsi', 'like', '%'.$search.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('product.index', compact('products', 'search'));
    }

    public function create(): View
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
            'stok' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->only(['nama','category_id','harga','deskripsi','stok']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success','Produk berhasil dibuat.');
    }

    public function edit(Product $product): View
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
            'stok' => 'nullable|integer|min:0',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $data = $request->only(['nama','category_id','harga','deskripsi','stok']);

        if ($request->hasFile('foto')) {
            // hapus file lama bila ada
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success','Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // hapus foto jika ada
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();
        return redirect()->route('products.index')->with('success','Produk berhasil dihapus.');
    }
}
