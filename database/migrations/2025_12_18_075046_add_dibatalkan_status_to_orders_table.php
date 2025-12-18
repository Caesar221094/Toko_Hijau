<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update data yang tidak sesuai dengan enum
        DB::statement("UPDATE orders SET status_pembayaran = 'pending' WHERE status_pembayaran NOT IN ('pending', 'lunas', 'ditolak')");
        
        // Tambahkan 'dibatalkan' ke enum status_pembayaran
        DB::statement("ALTER TABLE orders MODIFY status_pembayaran ENUM('pending', 'lunas', 'ditolak', 'dibatalkan') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum sebelumnya
        DB::statement("ALTER TABLE orders MODIFY status_pembayaran ENUM('pending', 'lunas', 'ditolak') NOT NULL DEFAULT 'pending'");
    }
};
