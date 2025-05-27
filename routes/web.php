<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/Beranda', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/produk', [ProductController::class, 'index'])->name('product.index');
Route::get('/produk/tambah', [ProductController::class, 'create'])->name('product.create');

Route::post('/produk-tambah', [ProductController::class, 'store'])->name('product.store');
Route::get('/produk/update/{id}', [ProductController::class, 'edit'])->name('product.edit');

Route::post('/produk/update/{id}', [ProductController::class, 'update'])->name('product.update');

Route::get('/produk/hapus/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

