<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SavingTargetController;
use App\Http\Controllers\CategoryBudgetController;
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

    // Anggaran per Kategori
    Route::get('/anggaran', [\App\Http\Controllers\CategoryBudgetController::class, 'index'])->name('anggaran.index');
    Route::post('/anggaran', [\App\Http\Controllers\CategoryBudgetController::class, 'store'])->name('anggaran.store');
    Route::delete('/anggaran/{id}', [\App\Http\Controllers\CategoryBudgetController::class, 'destroy'])->name('anggaran.destroy');


    // Akun Bank & Wallet
    Route::resource('/akun', AccountController::class)->except(['show']);

    // Mutasi
    Route::get('/mutasi', [TransferController::class, 'create'])->name('mutasi.create');
    Route::post('/mutasi', [TransferController::class, 'store'])->name('mutasi.store');

    // Saving Target    
    Route::resource('/target-dana', SavingTargetController::class)->except(['show']);
    Route::get('/target-dana/{id}', [SavingTargetController::class, 'show'])->name('target-dana.show');
    Route::post('/target-dana/{id}/simpan', [SavingTargetController::class, 'simpanDana'])->name('target-dana.simpan');
    Route::get('/target-dana/{target}/log/{log}/edit', [SavingTargetController::class, 'editLog'])->name('target-dana.log.edit');
    Route::patch('/target-dana/{target}/log/{log}', [SavingTargetController::class, 'updateLog'])->name('target-dana.log.update');
    Route::delete('/target-dana/{target}/log/{log}', [SavingTargetController::class, 'destroyLog'])->name('target-dana.log.destroy');
});



require __DIR__ . '/auth.php';
