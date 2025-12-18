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
        // Cek apakah kolom bukti_pembayaran sudah ada
        if (!Schema::hasColumn('orders', 'bukti_pembayaran')) {
            Schema::table('orders', function (Blueprint $table) {
                // Bukti pembayaran (path file/image)
                $table->string('bukti_pembayaran')->nullable()->after('total_amount');
            });
        }
        
        // Step 1: Ubah kolom status menjadi VARCHAR sementara
        DB::statement("ALTER TABLE orders CHANGE COLUMN status status VARCHAR(50) NOT NULL DEFAULT 'pending'");
        
        // Step 2: Update data lama agar sesuai dengan enum baru
        DB::statement("UPDATE orders SET status = 'pending' WHERE status IN ('pending', 'processing')");
        DB::statement("UPDATE orders SET status = 'sukses' WHERE status = 'completed'");
        DB::statement("UPDATE orders SET status = 'gagal' WHERE status = 'cancelled'");
        
        // Step 3: Ubah menjadi status_pembayaran dengan enum
        DB::statement("ALTER TABLE orders CHANGE COLUMN status status_pembayaran ENUM('pending', 'sukses', 'gagal') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('bukti_pembayaran');
        });
        
        // Kembalikan ke status semula
        DB::statement("ALTER TABLE orders CHANGE COLUMN status_pembayaran status ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending'");
    }
};
