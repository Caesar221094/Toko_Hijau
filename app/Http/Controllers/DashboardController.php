<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status_pembayaran', 'pending')->count(),
            'lunas_orders' => Order::where('status_pembayaran', 'lunas')->count(),
            'ditolak_orders' => Order::where('status_pembayaran', 'ditolak')->count(),
            'dibatalkan_orders' => Order::where('status_pembayaran', 'dibatalkan')->count(),
            'total_revenue' => Order::where('status_pembayaran', 'lunas')->sum('total_amount'),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_customers' => User::where('role', 'customer')->count(),
        ];

        // Recent Orders
        $recentOrders = Order::with(['user', 'orderProducts'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Revenue by month (current year)
        $monthlyRevenue = Order::where('status_pembayaran', 'lunas')
            ->whereYear('created_at', date('Y'))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        // Fill missing months with 0
        $revenueData = [];
        for ($i = 1; $i <= 12; $i++) {
            $revenueData[$i] = $monthlyRevenue[$i] ?? 0;
        }

        return view('dashboard', compact('stats', 'recentOrders', 'revenueData'));
    }
}
