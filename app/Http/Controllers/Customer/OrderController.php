<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->with('orderProducts.product')
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('orderProducts.product');
        return view('customer.orders.show', compact('order'));
    }

    public function cancel(Order $order)
    {
        // Validasi kepemilikan order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Hanya bisa dibatalkan jika status pending
        if ($order->status_pembayaran !== 'pending') {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan. Status: ' . $order->status_pembayaran);
        }

        DB::beginTransaction();
        try {
            // Kembalikan stok produk
            foreach ($order->orderProducts as $orderProduct) {
                $product = Product::find($orderProduct->product_id);
                if ($product) {
                    $product->increment('stok', $orderProduct->quantity);
                }
            }

            // Ubah status menjadi dibatalkan
            $order->update([
                'status_pembayaran' => 'dibatalkan'
            ]);

            DB::commit();
            return redirect()->route('customer.orders.index')
                           ->with('success', 'Pesanan berhasil dibatalkan. Stok produk telah dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
