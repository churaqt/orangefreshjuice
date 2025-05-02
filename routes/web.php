<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;


// Authentication Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stock Management
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::get('/stock/{stock}/edit', [StockController::class, 'edit'])->name('stock.edit');
    Route::put('/stock/{stock}', [StockController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{stock}', [StockController::class, 'destroy'])->name('stock.destroy');
    Route::get('/stock/search', [StockController::class, 'search'])->name('stock.search');

    // Order Management
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status');
    Route::get('/orders/search', [OrderController::class, 'search'])->name('orders.search');
    Route::get('/orders/revenue', [OrderController::class, 'calculateRevenue'])->name('orders.revenue');
    Route::resource('orders', OrderController::class);
    Route::resource('orders', OrderController::class);

});

// Redirect root to dashboard or login
Route::get('/', function () {
    return redirect()->route('dashboard');
});