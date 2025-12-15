<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;

use App\Http\Controllers\Vendor\VendorDashboardController;

use App\Http\Controllers\Customer\CustomerDashboardController;




Route::get('/login', [AuthController::class,'loginPage'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class,'registerShow'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register');//register
Route::get('/forgot-password', [AuthController::class,'forgotPassword'])->name('forgot.password');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');





Route::get('/', function () {
    return auth()->check()
        ? view('welcome')   // or dashboard
        : redirect()->route('login.page');
})->name('home');








Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    //dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');


    //employee management
    Route::get('manage', [AdminManageController::class, 'index'])->name('manage.index');
    Route::get('manage/add-staff', [AdminManageController::class, 'addStaff'])->name('add.staff');
    Route::post('manage/store', [AdminManageController::class, 'store'])->name('manage.store');

    Route::get('manage/search', [AdminManageController::class, 'search'])->name('manage.search');
    Route::get('manage/{id}/show', [AdminManageController::class, 'show'])->name('manage.show');
    // Edit staff (optional)
    Route::get('manage/{id}/edit', [AdminManageController::class, 'edit'])->name('manage.edit');
    // Update staff (optional)
    Route::put('manage/{id}', [AdminManageController::class, 'update'])->name('manage.update');
    // Delete staff (optional)
    Route::delete('manage/{id}', [AdminManageController::class, 'destroy'])->name('manage.destroy');

   


    //order
    Route::get('orders', [AdminOrderController::class, 'index'])->name('order.index');
    Route::get('orders/{id}/show', [AdminOrderController::class, 'show'])->name('order.show');


    //product
    Route::get('product', [AdminProductController::class, 'index'])->name('product.index');
    Route::get('product/create', [AdminProductController::class, 'create'])->name('product.create');
    Route::post('product', [AdminProductController::class, 'store'])->name('product.store');
    Route::get('product/search', [AdminProductController::class, 'search'])->name('product.search');
    Route::get('product/{id}/edit', [AdminProductController::class, 'edit'])->name('product.edit');
    Route::delete('product/{id}/', [AdminProductController::class, 'destroy'])->name('product.destroy');
    Route::put('product/{id}', [AdminProductController::class, 'update'])->name('product.update');

    Route::get('product/vegetable', [AdminProductController::class, 'selectVegetable'])->name('product.vegetable');
    Route::get('product/grocery', [AdminProductController::class, 'selectGrocery'])->name('product.grocery');




});





Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
    Route::get('products', [VendorDashboardController::class, 'products'])->name('products');
    
    Route::get('orders', [VendorDashboardController::class, 'orders'])->name('orders');
    Route::get('analytics', [VendorDashboardController::class, 'analytics'])->name('analytics');
});

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    Route::get('orders', [CustomerDashboardController::class, 'orders'])->name('orders');
    Route::get('cart', [CustomerDashboardController::class, 'cart'])->name('cart');
    Route::get('wishlist', [CustomerDashboardController::class, 'wishlist'])->name('wishlist');
});


//?
// Both admin and vendor can access these routes 
Route::middleware(['auth', 'role:admin,vendor'])->prefix('management')->name('management.')->group(function () {
    Route::get('reports', [ReportController::class, 'index'])->name('reports');
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
});


























// Route::get('/', function () {
//     return view('welcome');
// });


