<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\SalesController; 
use App\Http\Controllers\Admin\CustomerController;  //
use App\Http\Controllers\Admin\NotificationController;



use App\Http\Controllers\Vendor\VendorDashboardController; 
use App\Http\Controllers\Vendor\VendorOrderController; 
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorSalesController;
use App\Http\Controllers\Vendor\VendorNotificationController;






use App\Http\Controllers\Customer\CustomerDashboardController; 
use App\Http\Controllers\Customer\BillController; 
use App\Http\Controllers\Customer\OrderController; 
use App\Http\Controllers\Customer\ProfileController; 
use App\Http\Controllers\Customer\ProductController;




Route::get('/', [AuthController::class,'loginPage'])->name('login.page');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class,'registerShow'])->name('register.page');
Route::post('/register', [AuthController::class, 'register'])->name('register');//register

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class,'forgotPassword'])->name('forgot.password');






Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {


   //notification
   Route::controller(VendorNotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/{id}/mark-read', 'markAsRead')->name('markRead');
        Route::get('/{id}/mark-read', 'markAsRead')->name('markRead');
        
        Route::post('/mark-all-read', 'markAllAsRead')->name('markAllRead');
        Route::delete('/{id}', 'destroy')->name('destroy');
        Route::get('/unread-count', 'getUnreadCount')->name('unreadCount');
    });

    //dashboard
     Route::get('dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');

    //order
    Route::get('orders', [VendorOrderController::class, 'index'])->name('order.index');
    Route::get('orders/{id}/show', [VendorOrderController::class, 'show'])->name('order.show');
    Route::get('orders/completed', [VendorOrderController::class, 'selectCompleted'])->name('order.completed');
    Route::get('orders/out-for-delevery', [VendorOrderController::class, 'selectOutForDelivery'])->name('order.ofd');
    Route::get('orders/pending', [VendorOrderController::class, 'selectPending'])->name('order.pending');
    Route::get('orders/search', [VendorOrderController::class, 'search'])->name('order.search'); //outForDelivery
    Route::patch('orders/out-for-delivery/{id}', [VendorOrderController::class, 'outForDelivery'])->name('orders.deliveried');

      //Payment
    Route::get('orders/{id}/payment', [VendorOrderController::class, 'recordPayment'])
        ->name('order.payment');
    
    Route::post('payments', [VendorOrderController::class, 'paymentStore'])
        ->name('payment.store');

    //Sales
    Route::get('sales', [VendorSalesController::class, 'index'])->name('sales.index');
    Route::get('sales/{id}/show', [VendorSalesController::class, 'show'])->name('sales.show');


    // product

    Route::get('product', [VendorProductController::class, 'index'])->name('product.index');
    Route::get('product/create', [VendorProductController::class, 'create'])->name('product.create');
    Route::post('product', [VendorProductController::class, 'store'])->name('product.store');
    
    Route::get('product/{id}/edit', [VendorProductController::class, 'edit'])->name('product.edit');
    Route::delete('product/{id}/', [VendorProductController::class, 'destroy'])->name('product.destroy');
    Route::put('product/{id}', [VendorProductController::class, 'update'])->name('product.update');

    Route::get('product/search', [VendorProductController::class, 'search'])->name('product.search');
    Route::get('product/vegetable', [VendorProductController::class, 'selectVegetable'])->name('product.vegetable');
    Route::get('product/grocery', [VendorProductController::class, 'selectGrocery'])->name('product.grocery');
    Route::get('products/filter-stock', [VendorProductController::class, 'filterByStock'])
    ->name('product.filter-stock');




    
});






Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    //dashboard
    Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    //Notification
    Route::controller(NotificationController::class)->prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::patch('/{id}/mark-read', 'markAsRead')->name('markRead');
        Route::get('/{id}/mark-read', 'markAsRead')->name('markRead');
        
        Route::post('/mark-all-read', 'markAllAsRead')->name('markAllRead');
        Route::delete('/{id}', 'destroy')->name('destroy');

        // NEW: Delete all notifications route
        Route::delete('/', [NotificationController::class, 'destroyAll'])->name('destroyAll');
        
        Route::get('/unread-count', 'getUnreadCount')->name('unreadCount');

    });
   

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
    Route::get('orders/completed', [AdminOrderController::class, 'selectCompleted'])->name('order.completed');
    Route::get('orders/out-for-delevery', [AdminOrderController::class, 'selectOutForDelivery'])->name('order.ofd');
    Route::get('orders/pending', [AdminOrderController::class, 'selectPending'])->name('order.pending');
    Route::get('orders/search', [AdminOrderController::class, 'search'])->name('order.search'); //outForDelivery
    Route::patch('orders/out-for-delivery/{id}', [AdminOrderController::class, 'outForDelivery'])->name('orders.deliveried');
    
    
    //Paymnet
    Route::get('orders/{id}/payment', [AdminOrderController::class, 'recordPayment'])
        ->name('order.payment');
    
    Route::post('payments', [AdminOrderController::class, 'paymentStore'])
        ->name('payment.store');

// CustomerController
    Route::get('customers', [CustomerController::class, 'index'])->name('customer.index'); 
    Route::get('customers/{id}/show', [CustomerController::class, 'show'])->name('customer.show');
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customer.search');  


    


    //product
    Route::get('product', [AdminProductController::class, 'index'])->name('product.index');
    Route::get('product/create', [AdminProductController::class, 'create'])->name('product.create');
    Route::post('product', [AdminProductController::class, 'store'])->name('product.store');
    
    Route::get('product/{id}/edit', [AdminProductController::class, 'edit'])->name('product.edit');
    Route::delete('product/{id}/', [AdminProductController::class, 'destroy'])->name('product.destroy');
    Route::put('product/{id}', [AdminProductController::class, 'update'])->name('product.update');

    Route::get('product/search', [AdminProductController::class, 'search'])->name('product.search');
    Route::get('product/vegetable', [AdminProductController::class, 'selectVegetable'])->name('product.vegetable');
    Route::get('product/grocery', [AdminProductController::class, 'selectGrocery'])->name('product.grocery');
    Route::get('products/filter-stock', [AdminProductController::class, 'filterByStock'])
    ->name('product.filter-stock');


    Route::get('sales', [SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/{id}/show', [SalesController::class, 'show'])->name('sales.show');


});







Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {

    //dashboard
    Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
    
    //product section
    Route::get('/products', [ProductController::class, 'index'])->name('product.index');
    Route::get('/products/search', [ProductController::class, 'search'])->name('product.search');
    Route::get('/products/vegetable', [ProductController::class, 'selectVegetable'])->name('product.vegetable');
    Route::get('/products/grocery', [ProductController::class, 'selectGrocery'])->name('product.grocery');
    
    // Cart Routes
    Route::get('/cart', [ProductController::class, 'cart'])->name('product.cart');
    Route::post('/cart/add/{product}', [ProductController::class, 'addToCart'])->name('product.addToCart');
    Route::post('/cart/update/{cart}', [ProductController::class, 'updateCart'])->name('product.updateCart');
    Route::delete('/cart/remove/{cart}', [ProductController::class, 'removeFromCart'])->name('product.removeFromCart');
    Route::post('/cart/checkout', [ProductController::class, 'checkout'])->name('product.checkout');

    // Route::get('orders', [CustomerDashboardController::class, 'orders'])->name('order.index'); ///??OrderController
    // Route::get('orders', [CustomerDashboardController::class, 'show'])->name('order.show'); //??

    
    Route::get('orders', [OrderController::class, 'index'])->name('order.index');
    Route::get('order/{id}', [OrderController::class, 'show'])->name('order.show');
    ;
    Route::patch('order/{id}/completed', [OrderController::class, 'completed'])->name('orders.completed'); 


     //bill
    Route::get('/bills', [BillController::class, 'index'])->name('bill.index');

    //profile
    Route::get('profile/{id}', [ProfileController::class, 'index'])->name('profile.index');



 Route::get('wishlist', [CustomerDashboardController::class, 'wishlist'])->name('wishlist');

    
});






// //?
// // Both admin and vendor can access these routes 
// Route::middleware(['auth', 'role:admin,vendor'])->prefix('management')->name('management.')->group(function () {
//     Route::get('reports', [ReportController::class, 'index'])->name('reports');
//     Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics');
// });


























// Route::get('/', function () {
//     return view('welcome');
// });


