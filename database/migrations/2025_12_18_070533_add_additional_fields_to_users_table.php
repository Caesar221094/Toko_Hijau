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
        Schema::table('users', function (Blueprint $table) {
            // Role: admin atau customer
            $table->enum('role', ['admin', 'customer'])->default('customer')->after('password');
            // Alamat lengkap user
            $table->text('alamat')->nullable()->after('role');
            // Nomor telepon
            $table->string('telepon', 20)->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'alamat', 'telepon']);
        });
    }
};
