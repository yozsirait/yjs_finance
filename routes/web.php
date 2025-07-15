<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransferController;
use App\Http\Controllers\SavingTargetController;
use App\Http\Controllers\CategoryBudgetController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\AnnualReportController;
use App\Http\Controllers\MutationTransactionController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PinController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

    // Pengeluaran Rutin
    Route::resource('/pengeluaran-rutin', RecurringExpenseController::class)->except(['show']);
    Route::delete('/pengeluaran-rutin/{id}', [RecurringExpenseController::class, 'destroy'])->name('pengeluaran-rutin.destroy');
    Route::get('/pengeluaran-rutin/{id}/edit', [RecurringExpenseController::class, 'edit'])->name('pengeluaran-rutin.edit');
    Route::put('/pengeluaran-rutin/{id}', [RecurringExpenseController::class, 'update'])->name('pengeluaran-rutin.update');

    // Kategori
    Route::resource('/kategori', CategoryController::class)->except(['show', 'edit', 'update']);
    Route::get('/kategori/by-type/{type}', [CategoryController::class, 'byType'])->name('kategori.byType');

    // Anggaran per Kategori
    Route::get('/anggaran', [\App\Http\Controllers\CategoryBudgetController::class, 'index'])->name('anggaran.index');
    Route::post('/anggaran', [\App\Http\Controllers\CategoryBudgetController::class, 'store'])->name('anggaran.store');
    Route::delete('/anggaran/{id}', [\App\Http\Controllers\CategoryBudgetController::class, 'destroy'])->name('anggaran.destroy');
    Route::get('/kategori/by-type/{type}', function ($type) {
        $categories = auth()->user()
            ->categories()
            ->where('type', $type)
            ->get(['id', 'name']);

        return response()->json($categories);
    });
    Route::get('/anggaran/{id}/edit', [CategoryBudgetController::class, 'edit'])->name('anggaran.edit');
    Route::put('/anggaran/{id}', [CategoryBudgetController::class, 'update'])->name('anggaran.update');

    // Akun Bank & Wallet
    Route::resource('/akun', AccountController::class)->except(['show']);

    // Mutasi
    Route::get('/mutasi', [TransferController::class, 'create'])->name('mutasi.create');
    Route::post('/mutasi', [TransferController::class, 'store'])->name('mutasi.store');
    Route::get('/transaksi-mutasi', [MutationTransactionController::class, 'index'])->name('mutasi.transaksi.index');

    // Saving Target    
    Route::resource('/target-dana', SavingTargetController::class)->except(['show']);
    Route::get('/target-dana/{id}', [SavingTargetController::class, 'show'])->name('target-dana.show');
    Route::delete('/target-dana/{id}', [SavingTargetController::class, 'destroy'])->name('target-dana.destroy');
    Route::post('/target-dana/{id}/simpan', [SavingTargetController::class, 'simpanDana'])->name('target-dana.simpan');
    Route::get('/target-dana/{target}/log/{log}/edit', [SavingTargetController::class, 'editLog'])->name('target-dana.log.edit');
    Route::patch('/target-dana/{target}/log/{log}', [SavingTargetController::class, 'updateLog'])->name('target-dana.log.update');
    Route::delete('/target-dana/{target}/log/{log}', [SavingTargetController::class, 'destroyLog'])->name('target-dana.log.destroy');

    // Laporan Perbandingan
    Route::get('/laporan/perbandingan-bulanan', [ComparisonController::class, 'bulan'])->name('laporan.bulanan');
    Route::get('/laporan/perbandingan-member', [ComparisonController::class, 'member'])->name('laporan.member');
    Route::get('/laporan/tahunan', [AnnualReportController::class, 'index'])->name('laporan.tahunan');

    //Pin
    Route::get('/masukkan-pin', [PinController::class, 'form'])->name('pin.form');
    Route::post('/masukkan-pin', [PinController::class, 'verify'])->name('pin.verify');
    // routes/web.php


    Route::get('/pin', function () {
        return view('pin.prompt');
    })->name('pin.prompt');

    Route::post('/pin', function (Illuminate\Http\Request $request) {
        if ($request->pin === env('MEMBER_ACCESS_PIN')) {
            session(['verified_pin' => true]);
            return redirect()->intended(route('anggota.index'));
        }

        return back()->withErrors(['pin' => 'PIN salah']);
    })->name('pin.submit');



    // Anggota
    Route::middleware('verify.pin')->group(function () {
        Route::resource('anggota', MemberController::class)->parameters([
            'anggota' => 'anggota', // ✅ beri tahu Laravel nama parameter di URL = $anggota
        ]);
    });



    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});





require __DIR__ . '/auth.php';
