<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil kategori
        $makanan = Category::where('nama', 'Makanan')->first();
        $minuman = Category::where('nama', 'Minuman')->first();

        // Daftar produk makanan
        $makananList = [
            ['nama' => 'Nasi Goreng Spesial', 'harga' => 25000, 'stock' => 100],
            ['nama' => 'Mie Ayam Bakso', 'harga' => 18000, 'stock' => 80],
            ['nama' => 'Sate Ayam 10 Tusuk', 'harga' => 22000, 'stock' => 60],
            ['nama' => 'Ayam Geprek Level 5', 'harga' => 20000, 'stock' => 90],
            ['nama' => 'Nasi Uduk Komplit', 'harga' => 15000, 'stock' => 70],
        ];

        // Daftar produk minuman
        $minumanList = [
            ['nama' => 'Es Teh Manis', 'harga' => 5000, 'stock' => 200],
            ['nama' => 'Es Jeruk Segar', 'harga' => 6000, 'stock' => 150],
            ['nama' => 'Kopi Hitam Panas', 'harga' => 8000, 'stock' => 100],
            ['nama' => 'Susu Coklat Dingin', 'harga' => 10000, 'stock' => 120],
            ['nama' => 'Air Mineral Botol', 'harga' => 4000, 'stock' => 250],
        ];

        // Insert makanan
        foreach ($makananList as $item) {
            Product::firstOrCreate(
                ['nama' => $item['nama']],
                [
                    'category_id' => $makanan->id,
                    'harga' => $item['harga'],
                    'stock' => $item['stock'],
                ]
            );
        }

        // Insert minuman
        foreach ($minumanList as $item) {
            Product::firstOrCreate(
                ['nama' => $item['nama']],
                [
                    'category_id' => $minuman->id,
                    'harga' => $item['harga'],
                    'stock' => $item['stock'],
                ]
            );
        }
    }
}
