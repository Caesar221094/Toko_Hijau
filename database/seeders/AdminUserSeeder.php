<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah admin sudah ada
        $admin = User::where('email', 'admin@admin.com')->first();

        if (!$admin) {
            User::create([
                'name' => 'Super Administrator',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'email_verified_at' => now(),
                'role' => 'admin', // Role sebagai admin
                'alamat' => 'Jl. Admin No. 1, Jakarta Pusat',
                'telepon' => '081234567890',
            ]);

            $this->command->info('✅ Super Administrator berhasil dibuat!');
            $this->command->info('📧 Email: admin@admin.com');
            $this->command->info('🔑 Password: admin123');
        } else {
            $this->command->info('⚠️  Super Administrator sudah ada.');
        }
    }
}
