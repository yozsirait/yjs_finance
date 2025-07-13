<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');

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
    Route::get('/transaksi/{id}/duplicate', [TransactionController::class, 'duplicate'])->name('transaksi.duplicate');


    // Kategori
    Route::resource('/kategori', CategoryController::class)->except(['show', 'edit', 'update']);
    Route::get('/kategori/by-type/{type}', [CategoryController::class, 'byType'])->name('kategori.byType');

    // Akun Bank & Wallet
    Route::resource('/akun', AccountController::class)->except(['show']);

    // Mutasi
    Route::get('/mutasi', [TransferController::class, 'create'])->name('mutasi.create');
    Route::post('/mutasi', [TransferController::class, 'store'])->name('mutasi.store');


});



require __DIR__ . '/auth.php';
