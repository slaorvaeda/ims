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
use App\Http\Controllers\OperatorController;
use App\Http\Controllers\ReportController;
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
    $portals = \App\Models\PortalVendor::where('type', 'Portal')->orderBy('name')->get();
    
    $salesCounts = InwardItemCode::where('status', 'Sold')
        ->whereNotNull('portal_vendor_id')
        ->selectRaw('portal_vendor_id, COUNT(*) as total_qty')
        ->groupBy('portal_vendor_id')
        ->pluck('total_qty', 'portal_vendor_id')
        ->toArray();

    $chartPortalNames = [];
    $chartPortalSales = [];
    
    foreach ($portals as $portal) {
        $chartPortalNames[] = $portal->name;
        $chartPortalSales[] = (int) ($salesCounts[$portal->id] ?? 0);
    }

    if (empty($chartPortalNames)) {
        $chartPortalNames = ['No Portals'];
        $chartPortalSales = [0];
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

    // Calculate per-product Stock Breakdown (Inward, Outward/Sold, Available) for the dashboard modal
    $stockBreakdown = Product::with(['brand'])->get()
        ->map(function ($product) {
            $inwardCount = InwardItemCode::where('product_id', $product->id)
                ->where(function ($q) {
                    $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
                })
                ->count();
            
            $dispatchCount = DispatchItemCode::where('product_id', $product->id)
                ->where(function ($q) {
                    $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
                })
                ->count();

            $availableCount = max(0, $inwardCount - $dispatchCount);

            return [
                'product_name' => $product->product_name,
                'product_id_code' => $product->product_id,
                'sku' => $product->sku,
                'brand_name' => $product->brand->name ?? 'N/A',
                'inward' => $inwardCount,
                'outward' => $dispatchCount,
                'available' => $availableCount
            ];
        })
        ->filter(function ($item) {
            return $item['inward'] > 0 || $item['outward'] > 0 || $item['available'] > 0;
        })
        ->values();

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
        'chartActivityDispatch',
        'stockBreakdown'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics.index');

    // Operator Routes
    Route::get('/operators', [OperatorController::class, 'index'])->name('operators.index');
    Route::post('/operators/brands', [OperatorController::class, 'storeBrand'])->name('operators.store-brand');
    Route::put('/operators/brands/{brand}', [OperatorController::class, 'updateBrand'])->name('operators.update-brand');
    Route::delete('/operators/brands/{brand}', [OperatorController::class, 'destroyBrand'])->name('operators.destroy-brand');
    Route::post('/operators/portal-vendors', [OperatorController::class, 'storePortalVendor'])->name('operators.store-portal-vendor');
    Route::put('/operators/portal-vendors/{portalVendor}', [OperatorController::class, 'updatePortalVendor'])->name('operators.update-portal-vendor');
    Route::delete('/operators/portal-vendors/{portalVendor}', [OperatorController::class, 'destroyPortalVendor'])->name('operators.destroy-portal-vendor');

    // IMS Resource CRUD routes with permission checks
    Route::middleware('permission:products')->group(function () {
        Route::get('/products/export', [ProductController::class, 'export'])->name('products.export');
        Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
        Route::resource('products', ProductController::class);
    });

    Route::middleware('permission:purchases')->group(function () {
        Route::get('/purchases/export', [PurchaseController::class, 'export'])->name('purchases.export');
        Route::post('/purchases/import', [PurchaseController::class, 'import'])->name('purchases.import');
        Route::get('/purchases/next-uid', [PurchaseController::class, 'getNextUid'])->name('purchases.next-uid');
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

    // Unified Reports Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/stock', [ReportController::class, 'exportStock'])->name('reports.export.stock');
    Route::get('/reports/export/inward', [ReportController::class, 'exportInward'])->name('reports.export.inward');
    Route::get('/reports/export/dispatch', [ReportController::class, 'exportDispatch'])->name('reports.export.dispatch');
});

require __DIR__.'/auth.php';
