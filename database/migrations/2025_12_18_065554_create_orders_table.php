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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Foreign key ke tabel users
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            // Nomor order unik
            $table->string('order_number')->unique();
            // Status pesanan
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])
                  ->default('pending');
            // Total harga pesanan
            $table->decimal('total_amount', 15, 2)->default(0);
            // Alamat pengiriman
            $table->text('shipping_address')->nullable();
            // Catatan pesanan
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
