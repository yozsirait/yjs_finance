<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Transaksi
    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index'); // Halaman utama
    Route::get('/transaksi/create', [TransactionController::class, 'create'])->name('transaksi.create'); // Form tambah
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store'); // Simpan
    Route::get('/transaksi/{id}/edit', [TransactionController::class, 'edit'])->name('transaksi.edit');
    Route::put('/transaksi/{id}', [TransactionController::class, 'update'])->name('transaksi.update');
    Route::delete('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaksi.destroy');


    // Kategori
    Route::resource('/kategori', CategoryController::class)->except(['show', 'edit', 'update']);
    Route::get('/kategori/by-type/{type}', [CategoryController::class, 'byType'])->name('kategori.byType');
});



require __DIR__ . '/auth.php';
