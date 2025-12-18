<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['nama' => 'Elektronik'],
            ['nama' => 'Fashion'],
            ['nama' => 'Makanan & Minuman'],
            ['nama' => 'Peralatan Rumah Tangga'],
            ['nama' => 'Olahraga'],
            ['nama' => 'Buku & Alat Tulis'],
            ['nama' => 'Kesehatan & Kecantikan'],
            ['nama' => 'Mainan & Hobi'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['nama' => $cat['nama']]);
        }

        $this->command->info('✅ ' . count($categories) . ' kategori berhasil dibuat!');
    }
}
