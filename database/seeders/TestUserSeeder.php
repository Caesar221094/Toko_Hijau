<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah test user sudah ada
        $testUser = User::where('email', 'user1@gmail.com')->first();

        if (!$testUser) {
            User::create([
                'name' => 'Test User 1',
                'email' => 'user1@gmail.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'role' => 'customer',
                'alamat' => 'Jl. Test No. 1, Jakarta Selatan',
                'telepon' => '081234567891',
            ]);

            $this->command->info('✅ Test User berhasil dibuat!');
            $this->command->info('📧 Email: user1@gmail.com');
            $this->command->info('🔑 Password: password123');
        } else {
            $this->command->info('⚠️  Test User sudah ada.');
        }
    }
}
