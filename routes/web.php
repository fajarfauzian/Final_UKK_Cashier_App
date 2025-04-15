<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SalesDetailController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SearchController;

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login')->middleware('guest');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');
});

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', function () {
        return redirect()->route('dashboard');
    });

    // Product
    Route::resource('products', ProductController::class);
    Route::controller(ProductController::class)->prefix('products')->group(function () {
        Route::get('/{id}/update-stock', 'editStock')->name('products.updateStock');
        Route::post('/{id}/save-stock', 'updateStock')->name('products.saveStock');
    });

    // Sales
    Route::resource('sales', SaleController::class)->except(['edit', 'update']);
    Route::controller(SaleController::class)->prefix('sales')->group(function () {
        Route::post('/check-membership', 'checkMembership')->name('sales.check-membership');
        Route::post('/process-member', 'processMember')->name('sales.process-member');
        Route::post('/process-transaction', 'processTransaction')->name('sales.process-transaction');
        Route::get('/sales/export', 'export')->name('sales.export')->middleware('auth');
        Route::get('/sales/{id}/pdf', 'generatePdf')->name('sales.pdf');
    });

    // route check-membership-status ke luar grup prefix 'sales'
    Route::post('/sales/check-membership-status', [SaleController::class, 'checkMembershipStatus'])->name('sales.check-membership-status');

    // Sales Details
    Route::controller(SalesDetailController::class)->prefix('sales/{sale}')->group(function () {
        Route::get('/details', 'showDetails')->name('sales.details');
    });

    // Users
    Route::resource('users', UserController::class);

    // Search Route
    Route::get('/search', [SearchController::class, 'index'])->name('search');
});

// Route::get('/', function () {
//     return view('dashboard', ['title' => 'Dashboard']);
// });

// Route::get('/products', function () {
//     return view('products.index', ['title' => 'Products']);
// });

// Route::get('/products/create', function () {
//     return view('products.create', ['title' => 'Create Product']);
// });

// Route::get('/products/edit', function () {
//     return view('products.edit', ['title' => 'edit Product']);
// });

// Route::get('/sales', function () {
//     return view('sales.index', ['title' => 'Sales']);
// });

// Route::get('/sales/create', function () {
//     return view('sales.create', ['title' => 'Sales Create']);
// });

// Route::get('/users', function () {
//     return view('users.index', ['title' => 'Users']);
// });
