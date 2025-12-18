<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            // foto disimpan path string, boleh null
            $table->string('foto')->nullable();
            // decimal untuk harga, 13 digit total dengan 2 desimal
            $table->decimal('harga', 13, 2)->default(0);
            // stok tidak boleh negatif, gunakan unsigned
            $table->unsignedInteger('stok')->default(0);
            // foreign key ke categories
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
