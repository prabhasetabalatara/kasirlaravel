<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil data yang diperlukan
        $cashier = User::where('email', 'cashier@example.com')->first();
        $product1 = Product::where('nama', 'Nasi Goreng Spesial')->first();
        $product2 = Product::where('nama', 'Es Teh Manis')->first();

        // Pastikan data master ada sebelum melanjutkan
        if (!$cashier || !$product1 || !$product2) {
            $this->command->info('Pastikan Anda sudah menjalankan seeder lain terlebih dahulu.');
            return;
        }

        // Membuat 2 transaksi dummy
        DB::transaction(function () use ($cashier, $product1, $product2) {
            // --- Transaksi 1 ---
            $qty1 = 2;
            $total1 = $product1->harga * $qty1;

            $trans1 = Transaction::create([
                'user_id' => $cashier->id,
                'total_amount' => $total1,
                'discount' => 0,
                'tax' => 0,
            ]);

            OrderItem::create([
                'transaction_id' => $trans1->id,
                'product_id' => $product1->id,
                'quantity' => $qty1,
                'price' => $product1->harga,
            ]);
            // Kurangi stok
            $product1->decrement('stock', $qty1);


            // --- Transaksi 2 ---
            $qty2_p1 = 1;
            $qty2_p2 = 1;
            $total2 = ($product1->harga * $qty2_p1) + ($product2->harga * $qty2_p2);

            $trans2 = Transaction::create([
                'user_id' => $cashier->id,
                'total_amount' => $total2,
                'discount' => 0,
                'tax' => 0,
            ]);

            OrderItem::create([
                'transaction_id' => $trans2->id,
                'product_id' => $product1->id,
                'quantity' => $qty2_p1,
                'price' => $product1->harga,
            ]);
            $product1->decrement('stock', $qty2_p1);

            OrderItem::create([
                'transaction_id' => $trans2->id,
                'product_id' => $product2->id,
                'quantity' => $qty2_p2,
                'price' => $product2->harga,
            ]);
            $product2->decrement('stock', $qty2_p2);
        });
    }
}