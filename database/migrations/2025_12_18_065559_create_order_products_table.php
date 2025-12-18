<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel orders
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            // Foreign key ke tabel products
            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');
            // Jumlah produk yang dipesan
            $table->unsignedInteger('quantity')->default(1);
            // Harga per unit saat order dibuat (snapshot harga)
            $table->decimal('price', 13, 2);
            // Subtotal (quantity * price)
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
            
            // Index untuk performa query
            $table->index(['order_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
