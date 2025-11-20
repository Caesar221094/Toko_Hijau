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
            // foto disimpan path string, boleh null
            $table->string('foto')->nullable();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            // decimal untuk harga, nullable
            $table->decimal('harga', 13, 2)->nullable();
            $table->integer('stok')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
