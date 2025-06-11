<?php

use App\Http\Controllers\AuthController;
use App\Providers\CrudHelper;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Providers\WebHelper;
use Spatie\Activitylog\Models\Activity;

// ADMIN ROLE
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/tambah', [UserController::class, 'create'])->name('user.create');
    Route::post('/user/tambah', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/update/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/user/update/{id}/{source?}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    Route::get('/riwayat',function(){
        $logs = Activity::orderBy('created_at', 'desc')->get();
        $data = WebHelper::logFormatter($logs);
        $data = collect($data)->groupBy('tipe');
        return view('pages.log.index', compact('data'));
    })->name('log.index');
});

// ADMIN & USER
Route::middleware(['auth', 'role:admin,user'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.home');
    Route::get('/Beranda', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('profil', [DashboardController::class, 'show'])->name('dashboard.show');

    Route::get('/produk', [ProductController::class, 'index'])->name('product.index');
    Route::get('/produk/tambah', [ProductController::class, 'create'])->name('product.create');
    Route::post('/produk/tambah', [ProductController::class, 'store'])->name('product.store');
    Route::get('/produk/update/{id}', [ProductController::class, 'edit'])->name('product.edit');
    Route::post('/produk/update/{id}', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('/transaksi/tambah', [TransactionController::class, 'create'])->name('transaction.create');
    route::post('/transaksi/tambah', [TransactionController::class, 'store'])->name('transaction.store');
    Route::get('/transaksi/update/{id}', [TransactionController::class, 'edit'])->name('transaction.edit');
    route::put('/transaksi/update/{id}', [TransactionController::class, 'update'])->name('transaction.update');
    Route::get('/transaksi/{id}', [TransactionController::class, 'destroy'])->name('transaction.destroy');

    Route::get('kalkulator', function () {
        $table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.calculator.index', compact('result'));
    })->name('calculator.index');

    Route::get('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});

// NOT AUTHORIZED
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'loginProcess'])->name('auth.loginProcess');
});

// PUBLIC
Route::get('homepage', function(){
$table = 'Products';
        $data = CrudHelper::table($table);
        $result = CrudHelper::masterShowData($table, $data);
        return view('pages.homepage.index', compact('result'));
});
