<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil user admin
        $admin = User::where('email', 'admin@admin.com')->first();
        
        if (!$admin) {
            $this->command->error('❌ User admin tidak ditemukan! Jalankan AdminUserSeeder terlebih dahulu.');
            return;
        }

        // Ambil produk (pastikan ada produk)
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->warn('⚠️  Tidak ada produk. Membuat order tanpa produk.');
        }

        // Buat beberapa order sample
        $orders = [
            [
                'order_number' => Order::generateOrderNumber(),
                'status' => 'completed',
                'shipping_address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta 10220',
                'notes' => 'Mohon dikirim dengan bubble wrap',
            ],
            [
                'order_number' => Order::generateOrderNumber(),
                'status' => 'processing',
                'shipping_address' => 'Jl. Gatot Subroto No. 456, Bandung, Jawa Barat 40123',
                'notes' => null,
            ],
            [
                'order_number' => Order::generateOrderNumber(),
                'status' => 'pending',
                'shipping_address' => 'Jl. Ahmad Yani No. 789, Surabaya, Jawa Timur 60234',
                'notes' => 'Pengiriman setelah tanggal 20',
            ],
        ];

        foreach ($orders as $orderData) {
            // Buat order
            $order = Order::create([
                'user_id' => $admin->id,
                'order_number' => $orderData['order_number'],
                'status' => $orderData['status'],
                'total_amount' => 0, // akan dihitung nanti
                'shipping_address' => $orderData['shipping_address'],
                'notes' => $orderData['notes'],
            ]);

            // Tambahkan produk ke order (2-3 produk random)
            if (!$products->isEmpty()) {
                $selectedProducts = $products->random(min(3, $products->count()));
                $totalAmount = 0;

                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->harga;
                    $subtotal = $quantity * $price;
                    $totalAmount += $subtotal;

                    OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update total amount order
                $order->update(['total_amount' => $totalAmount]);
            }

            $this->command->info("✅ Order {$order->order_number} created with status: {$order->status}");
        }

        $this->command->info("\n🎉 " . count($orders) . " sample orders berhasil dibuat!");
    }
}
