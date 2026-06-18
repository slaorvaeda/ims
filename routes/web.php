<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InwardItemCodeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DispatchItemCodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarcodeController;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $stats = [
        'total_products' => Product::count(),
        'total_purchase_qty' => Purchase::sum('quantity'),
        'total_purchase_cost' => Purchase::sum('amount'),
        'total_inward' => InwardItemCode::count(),
        'total_dispatch' => DispatchItemCode::count(),
        'active_stock' => InwardItemCode::count() - DispatchItemCode::count(),
        'total_sales' => Sale::count(),
        'total_users' => User::count(),
    ];

    return view('dashboard', compact('stats'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // IMS Resource CRUD routes
    Route::resource('products', ProductController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::post('/inward-item-codes/scan-dispatch', [InwardItemCodeController::class, 'scanDispatch'])->name('inward-item-codes.scan-dispatch');
    Route::resource('inward-item-codes', InwardItemCodeController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('dispatch-item-codes', DispatchItemCodeController::class);
    Route::resource('users', UserController::class);
    
    // Barcode Generator route
    Route::get('/barcodes', [BarcodeController::class, 'index'])->name('barcodes.index');
});

require __DIR__.'/auth.php';
