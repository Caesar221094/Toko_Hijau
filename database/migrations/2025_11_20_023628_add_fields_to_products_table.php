<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // cek jika kolom belum ada, tambahkan
            if (!Schema::hasColumn('products', 'foto')) {
                $table->string('foto')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('products', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('foto');
            }
            if (!Schema::hasColumn('products', 'harga')) {
                $table->decimal('harga', 13, 2)->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('products', 'stok')) {
                $table->integer('stok')->default(0)->after('harga');
            }
            // jika category_id belum ada (biasanya sudah ada) jangan duplikasi
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'foto')) {
                $table->dropColumn('foto');
            }
            if (Schema::hasColumn('products', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('products', 'harga')) {
                $table->dropColumn('harga');
            }
            if (Schema::hasColumn('products', 'stok')) {
                $table->dropColumn('stok');
            }
        });
    }
};
