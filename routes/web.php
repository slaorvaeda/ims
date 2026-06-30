<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InwardItemCodeController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DispatchItemCodeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\AnalyticsController;
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

    // 1. Monthly Trends (Sales vs Purchases)
    $cutoffDate = now()->subMonths(5)->startOfMonth();
    
    $purchasesByMonth = Purchase::where('date', '>=', $cutoffDate)
        ->get()
        ->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->date)->format('Y-m');
        })
        ->map(function($group) {
            return $group->sum('quantity');
        });

    $salesByMonth = Sale::where('order_date', '>=', $cutoffDate)
        ->get()
        ->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->order_date)->format('Y-m');
        })
        ->map(function($group) {
            return $group->sum('quantity');
        });

    $chartMonthlyMonths = [];
    $chartMonthlyPurchases = [];
    $chartMonthlySales = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $monthDate = now()->subMonths($i);
        $monthKey = $monthDate->format('Y-m');
        $monthLabel = $monthDate->format('M Y');
        
        $chartMonthlyMonths[] = $monthLabel;
        
        $pQty = $purchasesByMonth->get($monthKey, 0);
        $sQty = $salesByMonth->get($monthKey, 0);
        
        $chartMonthlyPurchases[] = $pQty;
        $chartMonthlySales[] = $sQty;
    }

    // 2. Stock Levels by Product
    $products = Product::withCount(['inwardItemCodes', 'dispatchItemCodes'])->get();
    $chartProductNames = [];
    $chartProductStocks = [];
    
    foreach ($products as $prod) {
        $chartProductNames[] = $prod->product_name;
        $inward = $prod->inward_item_codes_count;
        $dispatch = $prod->dispatch_item_codes_count;
        $chartProductStocks[] = max(0, $inward - $dispatch);
    }

    // 3. Sales Portals Distribution
    $portalSales = Sale::selectRaw('portal_id, SUM(quantity) as total_qty')
        ->groupBy('portal_id')
        ->get();
        
    $chartPortalNames = [];
    $chartPortalSales = [];
    
    foreach ($portalSales as $sale) {
        $chartPortalNames[] = $sale->portal_id;
        $chartPortalSales[] = (int) $sale->total_qty;
    }

    // 4. Daily Activity Log (Last 7 Days)
    $chartActivityDays = [];
    $chartActivityInward = [];
    $chartActivityDispatch = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $dateStr = $date->format('Y-m-d');
        $labelStr = $date->format('D, M d');
        
        $chartActivityDays[] = $labelStr;
        
        $inCount = InwardItemCode::whereDate('created_at', $dateStr)->count();
        $outCount = DispatchItemCode::whereDate('created_at', $dateStr)->count();
        
        $chartActivityInward[] = $inCount;
        $chartActivityDispatch[] = $outCount;
    }

    return view('dashboard', compact(
        'stats',
        'chartMonthlyMonths',
        'chartMonthlyPurchases',
        'chartMonthlySales',
        'chartProductNames',
        'chartProductStocks',
        'chartPortalNames',
        'chartPortalSales',
        'chartActivityDays',
        'chartActivityInward',
        'chartActivityDispatch'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // IMS Resource CRUD routes with permission checks
    Route::middleware('permission:products')->group(function () {
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
        Route::resource('products', ProductController::class);
    });

    Route::middleware('permission:purchases')->group(function () {
        Route::get('/purchases/export', [PurchaseController::class, 'export'])->name('purchases.export');
        Route::post('/purchases/import', [PurchaseController::class, 'import'])->name('purchases.import');
        Route::resource('purchases', PurchaseController::class);
    });

    Route::middleware('permission:inward_item_codes')->group(function () {
        Route::get('/inward-item-codes/export', [InwardItemCodeController::class, 'export'])->name('inward-item-codes.export');
        Route::post('/inward-item-codes/import', [InwardItemCodeController::class, 'import'])->name('inward-item-codes.import');
        Route::post('/inward-item-codes/scan-dispatch', [InwardItemCodeController::class, 'scanDispatch'])->name('inward-item-codes.scan-dispatch');
        Route::resource('inward-item-codes', InwardItemCodeController::class);
    });

    Route::middleware('permission:sales')->group(function () {
        Route::get('/sales/export', [SaleController::class, 'export'])->name('sales.export');
        Route::post('/sales/import', [SaleController::class, 'import'])->name('sales.import');
        Route::resource('sales', SaleController::class);
    });

    Route::middleware('permission:dispatch_item_codes')->group(function () {
        Route::get('/dispatch-item-codes/export', [DispatchItemCodeController::class, 'export'])->name('dispatch-item-codes.export');
        Route::post('/dispatch-item-codes/import', [DispatchItemCodeController::class, 'import'])->name('dispatch-item-codes.import');
        Route::post('/dispatch-item-codes/scan-cancel', [DispatchItemCodeController::class, 'scanCancel'])->name('dispatch-item-codes.scan-cancel');
        Route::resource('dispatch-item-codes', DispatchItemCodeController::class);
    });
    
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
    });
    
    // Barcode Generator route with permission check
    Route::middleware('permission:barcodes')->group(function () {
        Route::get('/barcodes', [BarcodeController::class, 'index'])->name('barcodes.index');
    });
});

require __DIR__.'/auth.php';
