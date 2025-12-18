<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('shop.index')->with('error', 'Keranjang kosong!');
        }

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('customer.checkout.index', compact('cart', 'total'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'shipping_address' => 'required|string',
            'notes' => 'nullable|string',
            'bukti_pembayaran' => 'required|image|max:2048',
        ]);

        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong!');
        }

        DB::beginTransaction();
        try {
            // Validasi stok sebelum checkout
            $stockErrors = [];
            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);
                
                if (!$product) {
                    $stockErrors[] = "{$item['name']} tidak ditemukan.";
                    continue;
                }
                
                if ($product->stok < $item['quantity']) {
                    $stockErrors[] = "{$product->nama} stok tidak cukup. Stok tersedia: {$product->stok}, diminta: {$item['quantity']}.";
                }
            }
            
            if (!empty($stockErrors)) {
                DB::rollBack();
                return back()->with('error', 'Checkout gagal! ' . implode(' ', $stockErrors));
            }
            
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
            }

            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => Order::generateOrderNumber(),
                'status_pembayaran' => 'pending',
                'total_amount' => $total,
                'bukti_pembayaran' => $buktiPath,
                'shipping_address' => $request->shipping_address,
                'notes' => $request->notes,
            ]);

            foreach ($cart as $productId => $item) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                ]);

                $product = Product::find($productId);
                $product->decrement('stok', $item['quantity']);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('customer.orders.show', $order->id)
                           ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
