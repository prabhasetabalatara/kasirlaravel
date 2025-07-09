<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Transaksi
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transaksi/baru', [TransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transactions.store');
    Route::get('/transaksi/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::get('/transaksi/{transaction}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
    Route::put('/transaksi/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transaksi/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');


    // Route::middleware(['admin'])->group(function () {
        // --- PERUBAHAN: Rute Produk Dibuat Eksplisit ---
        Route::get('/produk', [ProductController::class, 'index'])->name('produk.index');
        Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
        Route::post('/produk', [ProductController::class, 'store'])->name('produk.store');
        Route::get('/produk/{produk}', [ProductController::class, 'show'])->name('produk.show');
        Route::get('/produk/{produk}/edit', [ProductController::class, 'edit'])->name('produk.edit');
        Route::put('/produk/{produk}', [ProductController::class, 'update'])->name('produk.update');
        Route::delete('/produk/{produk}', [ProductController::class, 'destroy'])->name('produk.destroy');

        // --- PERUBAHAN: Rute Kategori Dibuat Eksplisit ---
        Route::get('/kategori', [\App\Http\Controllers\CategoryController::class, 'index'])->name('kategori.index');
        Route::get('/kategori/create', [\App\Http\Controllers\CategoryController::class, 'create'])->name('kategori.create');
        Route::post('/kategori', [\App\Http\Controllers\CategoryController::class, 'store'])->name('kategori.store');
        Route::get('/kategori/{kategori}/edit', [\App\Http\Controllers\CategoryController::class, 'edit'])->name('kategori.edit');
        Route::put('/kategori/{kategori}', [\App\Http\Controllers\CategoryController::class, 'update'])->name('kategori.update');
        Route::delete('/kategori/{kategori}', [\App\Http\Controllers\CategoryController::class, 'destroy'])->name('kategori.destroy');
        
        // Rute Pengguna
        Route::get('/pengguna', [UserController::class, 'index'])->name('users.index');
        Route::get('/pengguna/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/pengguna/{user}', [UserController::class, 'update'])->name('users.update');
    // });

});

require __DIR__.'/auth.php';
