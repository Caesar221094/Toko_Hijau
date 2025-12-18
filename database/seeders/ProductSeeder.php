<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->count() === 0) {
            echo "Jalankan CategorySeeder dulu!\n";
            return;
        }

        $products = [
            // Elektronik
            ['nama' => 'Laptop ASUS ROG', 'category' => 'Elektronik', 'harga' => 15000000, 'deskripsi' => 'Laptop gaming dengan processor Intel Core i7, RAM 16GB, VGA RTX 3060', 'stok' => 10],
            ['nama' => 'Smartphone Samsung Galaxy S23', 'category' => 'Elektronik', 'harga' => 12000000, 'deskripsi' => 'Smartphone flagship dengan kamera 200MP, chipset Snapdragon 8 Gen 2', 'stok' => 15],

            // Fashion
            ['nama' => 'Kemeja Batik Premium', 'category' => 'Fashion', 'harga' => 250000, 'deskripsi' => 'Kemeja batik lengan panjang bahan katun premium motif modern', 'stok' => 30],
            ['nama' => 'Sepatu Sneakers Nike', 'category' => 'Fashion', 'harga' => 1200000, 'deskripsi' => 'Sepatu sneakers original Nike Air Max dengan teknologi cushioning', 'stok' => 20],

            // Makanan
            ['nama' => 'Kopi Arabica 500gr', 'category' => 'Makanan', 'harga' => 150000, 'deskripsi' => 'Kopi arabica premium dari Aceh Gayo, roasted medium', 'stok' => 50],
            ['nama' => 'Cokelat Premium Box', 'category' => 'Makanan', 'harga' => 200000, 'deskripsi' => 'Cokelat praline premium isi 20 pcs dalam box eksklusif', 'stok' => 25],

            // Olahraga
            ['nama' => 'Raket Badminton Yonex', 'category' => 'Olahraga', 'harga' => 850000, 'deskripsi' => 'Raket badminton Yonex Astrox 99 Pro untuk pemain profesional', 'stok' => 12],
            ['nama' => 'Sepeda Gunung Polygon', 'category' => 'Olahraga', 'harga' => 5500000, 'deskripsi' => 'Sepeda gunung 27.5 inch dengan frame alloy dan suspensi hidrolik', 'stok' => 8],

            // Buku
            ['nama' => 'Novel Laskar Pelangi', 'category' => 'Buku', 'harga' => 95000, 'deskripsi' => 'Novel best seller karya Andrea Hirata tentang perjuangan anak-anak Belitung', 'stok' => 40],
            ['nama' => 'Buku Pemrograman PHP Laravel', 'category' => 'Buku', 'harga' => 150000, 'deskripsi' => 'Panduan lengkap belajar Laravel dari dasar hingga mahir', 'stok' => 35],

            // Mainan
            ['nama' => 'Lego Creator Set', 'category' => 'Mainan', 'harga' => 450000, 'deskripsi' => 'Lego Creator 3-in-1 dengan 500+ pieces untuk kreativitas anak', 'stok' => 18],
            ['nama' => 'Boneka Teddy Bear Jumbo', 'category' => 'Mainan', 'harga' => 350000, 'deskripsi' => 'Boneka beruang ukuran jumbo 80cm bahan lembut dan aman', 'stok' => 22],

            // Peralatan Rumah
            ['nama' => 'Rice Cooker Miyako 2L', 'category' => 'Peralatan Rumah', 'harga' => 450000, 'deskripsi' => 'Magic com 2 liter dengan teknologi 3D heating', 'stok' => 25],
            ['nama' => 'Blender Philips', 'category' => 'Peralatan Rumah', 'harga' => 650000, 'deskripsi' => 'Blender 2L dengan 6 pisau stainless steel dan 3 kecepatan', 'stok' => 20],

            // Kesehatan
            ['nama' => 'Masker KN95 Box 50pcs', 'category' => 'Kesehatan', 'harga' => 125000, 'deskripsi' => 'Masker medis KN95 5 lapis perlindungan maksimal isi 50 pcs', 'stok' => 100],
            ['nama' => 'Vitamin C 1000mg', 'category' => 'Kesehatan', 'harga' => 85000, 'deskripsi' => 'Suplemen vitamin C 1000mg untuk daya tahan tubuh isi 30 tablet', 'stok' => 80],
        ];

        foreach ($products as $productData) {
            $category = $categories->firstWhere('nama', $productData['category']);
            
            if ($category) {
                Product::create([
                    'nama' => $productData['nama'],
                    'category_id' => $category->id,
                    'harga' => $productData['harga'],
                    'deskripsi' => $productData['deskripsi'],
                    'stok' => $productData['stok'],
                    'foto' => null, // Admin bisa upload foto nanti
                ]);
            }
        }

        echo "Berhasil membuat " . count($products) . " produk!\n";
    }
}
