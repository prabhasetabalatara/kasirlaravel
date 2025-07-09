<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menyiapkan data dan menampilkan halaman dashboard utama.
     */
    public function index()
    {
        // --- Data untuk kartu ringkasan (summary cards) ---

        // 1. Total penjualan hari ini
        $todaySales = Transaction::whereDate('created_at', today())->sum('total_amount');

        // 2. Jumlah transaksi hari ini
        $todayTransactions = Transaction::whereDate('created_at', today())->count();

        // 3. Jumlah total produk yang terdaftar
        $totalProducts = Product::count();


        // --- Data untuk tabel dan daftar ---

        // 4. Daftar produk dengan stok menipis (misalnya, di bawah 10)
        $lowStockProducts = Product::where('stock', '<', 10)
                                    ->orderBy('stock', 'asc')
                                    ->limit(5)
                                    ->get();

        // 5. Daftar transaksi terbaru yang masuk
        $recentTransactions = Transaction::with('user') // Eager load relasi user
                                           ->latest()
                                           ->take(5)
                                           ->get();

        // Kirim semua data yang sudah dikumpulkan ke view 'dashboard'
        return view('dashboard', [
            'todaySales' => $todaySales,
            'todayTransactions' => $todayTransactions,
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}