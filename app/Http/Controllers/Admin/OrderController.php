<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status');
        
        $query = Order::with(['user', 'orderProducts.product'])
                     ->orderBy('created_at', 'desc');
        
        if ($status && $status !== 'all') {
            $query->where('status_pembayaran', $status);
        }
        
        $orders = $query->paginate(15);
        
        // Statistik untuk cards
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status_pembayaran', 'pending')->count(),
            'lunas' => Order::where('status_pembayaran', 'lunas')->count(),
            'ditolak' => Order::where('status_pembayaran', 'ditolak')->count(),
            'dibatalkan' => Order::where('status_pembayaran', 'dibatalkan')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'stats', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderProducts.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function approve(Order $order)
    {
        if ($order->status_pembayaran !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa disetujui. Status saat ini: ' . $order->status_pembayaran);
        }

        $order->update([
            'status_pembayaran' => 'lunas'
        ]);

        return back()->with('success', 'Pesanan #' . $order->order_number . ' berhasil disetujui!');
    }

    public function reject(Request $request, Order $order)
    {
        if ($order->status_pembayaran !== 'pending') {
            return back()->with('error', 'Pesanan tidak bisa ditolak. Status saat ini: ' . $order->status_pembayaran);
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

            // Update status
            $order->update([
                'status_pembayaran' => 'ditolak'
            ]);

            DB::commit();
            return back()->with('success', 'Pesanan #' . $order->order_number . ' ditolak dan stok produk dikembalikan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
