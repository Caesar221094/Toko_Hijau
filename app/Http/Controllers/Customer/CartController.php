<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        // Fix cart lama yang pakai 'foto' bukan 'photo' dan sync stok
        $needsUpdate = false;
        $hasStockIssue = false;
        
        foreach ($cart as $key => $item) {
            if (!isset($item['photo']) && isset($item['foto'])) {
                $cart[$key]['photo'] = $item['foto'];
                unset($cart[$key]['foto']);
                $needsUpdate = true;
            }
            
            // Sync stok terbaru dari database
            $product = Product::find($key);
            if ($product) {
                $cart[$key]['stok'] = $product->stok;
                
                // Jika quantity melebihi stok, auto-adjust
                if ($item['quantity'] > $product->stok) {
                    if ($product->stok > 0) {
                        $cart[$key]['quantity'] = $product->stok;
                        $hasStockIssue = true;
                    } else {
                        unset($cart[$key]); // Hapus jika stok habis
                        $hasStockIssue = true;
                    }
                    $needsUpdate = true;
                }
            } else {
                // Hapus produk yang sudah tidak ada
                unset($cart[$key]);
                $needsUpdate = true;
            }
        }
        
        if ($needsUpdate) {
            session()->put('cart', $cart);
        }
        
        if ($hasStockIssue) {
            session()->flash('warning', 'Beberapa produk di keranjang telah disesuaikan karena stok tidak mencukupi.');
        }
        
        // Cek apakah ada produk dengan quantity melebihi stok
        $canCheckout = true;
        foreach ($cart as $key => $item) {
            if ($item['quantity'] > $item['stok']) {
                $canCheckout = false;
                break;
            }
        }
        
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('customer.cart.index', compact('cart', 'total', 'canCheckout'));
    }

    public function add(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);

        if ($product->stok < 1) {
            return back()->with('error', 'Stok produk habis!');
        }

        $cart = session()->get('cart', []);
        $quantity = $request->input('quantity', 1);

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            
            // Validasi stok
            if ($newQuantity > $product->stok) {
                return back()->with('error', "Jumlah melebihi stok yang tersedia! Stok tersedia: {$product->stok} unit.");
            }
            
            $cart[$productId]['quantity'] = $newQuantity;
            $cart[$productId]['stok'] = $product->stok; // Update stok terbaru
        } else {
            // Validasi stok untuk produk baru
            if ($quantity > $product->stok) {
                return back()->with('error', "Jumlah melebihi stok yang tersedia! Stok tersedia: {$product->stok} unit.");
            }
            
            $cart[$productId] = [
                'name' => $product->nama,
                'price' => $product->harga,
                'quantity' => $quantity,
                'photo' => $product->foto,
                'stok' => $product->stok,
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function update(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            $quantity = $request->quantity;
            
            if ($quantity > 0) {
                // Validasi stok
                if ($quantity > $product->stok) {
                    return back()->with('error', "Jumlah melebihi stok yang tersedia! Stok tersedia: {$product->stok} unit.");
                }
                
                $cart[$productId]['quantity'] = $quantity;
                $cart[$productId]['stok'] = $product->stok; // Update stok terbaru
            } else {
                unset($cart[$productId]);
            }
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Keranjang diupdate!');
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Produk dihapus!');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Keranjang dikosongkan!');
    }
}
