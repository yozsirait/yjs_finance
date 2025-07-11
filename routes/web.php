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
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/transaksi', [TransactionController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransactionController::class, 'store'])->name('transaksi.store');
    Route::resource('/kategori', CategoryController::class)->except(['show', 'edit', 'update']);
});



require __DIR__ . '/auth.php';
