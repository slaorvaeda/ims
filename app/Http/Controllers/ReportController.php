<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use App\Models\PortalVendor;
use App\Services\CsvExcelService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard with tabs for Stock, Inward, and Dispatch details.
     */
    public function index(Request $request)
    {
        // 1. Filtering Parameters
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $productId = $request->input('product_id');
        $brandId = $request->input('brand_id');
        $portalId = $request->input('portal_id');
        $activeTab = $request->input('tab', 'stock'); // Default to stock report tab
        $search = $request->input('search');

        // Fetch master tables for filters
        $products = Product::orderBy('product_name')->get();
        $brands = Brand::orderBy('name')->get();
        $portals = PortalVendor::where('type', 'Portal')->orderBy('name')->get();

        // Establish Date Range boundaries
        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        // 2. Compute Common KPIs (Filtered by Date Range and Brand/Product if provided)
        $inwardKpiQuery = InwardItemCode::query();
        
        $dispatchKpiQuery = DispatchItemCode::query();

        if ($start) {
            $inwardKpiQuery->where('created_at', '>=', $start);
            $dispatchKpiQuery->where('created_at', '>=', $start);
        }
        if ($end) {
            $inwardKpiQuery->where('created_at', '<=', $end);
            $dispatchKpiQuery->where('created_at', '<=', $end);
        }
        if ($productId) {
            $inwardKpiQuery->where('product_id', $productId);
            $dispatchKpiQuery->where('product_id', $productId);
        }
        if ($brandId) {
            $inwardKpiQuery->whereHas('product', function ($q) use ($brandId) {
                $q->where('brand_id', $brandId);
            });
            $dispatchKpiQuery->whereHas('product', function ($q) use ($brandId) {
                $q->where('brand_id', $brandId);
            });
        }
        if ($portalId) {
            $inwardKpiQuery->where('portal_vendor_id', $portalId);
            $dispatchKpiQuery->where('portal_vendor_id', $portalId);
        }

        $kpis = [
            'total_inward' => $inwardKpiQuery->count(),
            'total_dispatch' => $dispatchKpiQuery->count(),
            'active_stock' => max(0, $inwardKpiQuery->count() - $dispatchKpiQuery->count()),
            'damaged_stock' => InwardItemCode::where('status', 'Damaged')
                ->when($start, fn($q) => $q->where('created_at', '>=', $start))
                ->when($end, fn($q) => $q->where('created_at', '<=', $end))
                ->when($productId, fn($q) => $q->where('product_id', $productId))
                ->count(),
            'returns' => InwardItemCode::where('mark', 'Returned')
                ->when($start, fn($q) => $q->where('created_at', '>=', $start))
                ->when($end, fn($q) => $q->where('created_at', '<=', $end))
                ->when($productId, fn($q) => $q->where('product_id', $productId))
                ->count(),
        ];

        // 3. Fetch Tab Specific Data
        $stockData = collect();
        $inwardCodes = collect();
        $dispatchCodes = collect();

        if ($activeTab === 'stock') {
            // Product stock summary
            $productsQuery = Product::with(['brand'])
                ->when($brandId, function ($q) use ($brandId) {
                    $q->where('brand_id', $brandId);
                })
                ->when($productId, function ($q) use ($productId) {
                    $q->where('id', $productId);
                })
                ->when($search, function ($q) use ($search) {
                    $q->where(function($sub) use ($search) {
                        $sub->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
                });

            $stockData = $productsQuery->get()->map(function ($product) use ($start, $end, $portalId) {
                // Inward query
                $inQuery = InwardItemCode::where('product_id', $product->id);
                if ($start) $inQuery->where('created_at', '>=', $start);
                if ($end) $inQuery->where('created_at', '<=', $end);
                if ($portalId) $inQuery->where('portal_vendor_id', $portalId);

                // Dispatch query
                $outQuery = DispatchItemCode::where('product_id', $product->id);
                if ($start) $outQuery->where('created_at', '>=', $start);
                if ($end) $outQuery->where('created_at', '<=', $end);
                if ($portalId) $outQuery->where('portal_vendor_id', $portalId);

                $inwardCount = $inQuery->count();
                $dispatchCount = $outQuery->count();
                $balance = max(0, $inwardCount - $dispatchCount);

                // Fetch latest purchase price
                $latestPurchase = $product->purchases()->latest('date')->latest('id')->first();
                $purchaseRate = $latestPurchase ? floatval($latestPurchase->price) : 0.00;
                $stockValue = $balance * $purchaseRate;

                return [
                    'product_id' => $product->product_id,
                    'sku' => $product->sku ?? 'N/A',
                    'name' => $product->product_name,
                    'brand' => $product->brand->name ?? 'N/A',
                    'inward' => $inwardCount,
                    'dispatch' => $dispatchCount,
                    'balance' => $balance,
                    'purchase_rate' => $purchaseRate,
                    'stock_value' => $stockValue,
                ];
            });

        } elseif ($activeTab === 'inward') {
            // Paginated inward logs
            $inwardQuery = InwardItemCode::with(['product.brand', 'portal'])
                ->when($start, fn($q) => $q->where('created_at', '>=', $start))
                ->when($end, fn($q) => $q->where('created_at', '<=', $end))
                ->when($productId, fn($q) => $q->where('product_id', $productId))
                ->when($brandId, fn($q) => $q->whereHas('product', fn($p) => $p->where('brand_id', $brandId)))
                ->when($portalId, fn($q) => $q->where('portal_vendor_id', $portalId))
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('uid', 'like', "%{$search}%")
                            ->orWhereHas('product', function ($p) use ($search) {
                                $p->where('product_name', 'like', "%{$search}%")
                                  ->orWhere('sku', 'like', "%{$search}%");
                            });
                    });
                })
                ->latest();

            $inwardCodes = $inwardQuery->paginate(15)->withQueryString();

        } elseif ($activeTab === 'dispatch') {
            // Paginated dispatch logs
            $dispatchQuery = DispatchItemCode::with(['product.brand', 'portal'])
                ->when($start, fn($q) => $q->where('created_at', '>=', $start))
                ->when($end, fn($q) => $q->where('created_at', '<=', $end))
                ->when($productId, fn($q) => $q->where('product_id', $productId))
                ->when($brandId, fn($q) => $q->whereHas('product', fn($p) => $p->where('brand_id', $brandId)))
                ->when($portalId, fn($q) => $q->where('portal_vendor_id', $portalId))
                ->when($search, function ($q) use ($search) {
                    $q->where(function ($sub) use ($search) {
                        $sub->where('uid', 'like', "%{$search}%")
                            ->orWhereHas('product', function ($p) use ($search) {
                                $p->where('product_name', 'like', "%{$search}%")
                                  ->orWhere('sku', 'like', "%{$search}%");
                            });
                    });
                })
                ->latest();

            $dispatchCodes = $dispatchQuery->paginate(15)->withQueryString();
        }

        return view('reports.index', compact(
            'products',
            'brands',
            'portals',
            'startDate',
            'endDate',
            'productId',
            'brandId',
            'portalId',
            'activeTab',
            'search',
            'kpis',
            'stockData',
            'inwardCodes',
            'dispatchCodes'
        ));
    }

    /**
     * Export Stock Balance report to Excel/CSV.
     */
    public function exportStock(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $productId = $request->input('product_id');
        $brandId = $request->input('brand_id');
        $portalId = $request->input('portal_id');
        $search = $request->input('search');

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        $productsQuery = Product::with(['brand'])
            ->when($brandId, function ($q) use ($brandId) {
                $q->where('brand_id', $brandId);
            })
            ->when($productId, function ($q) use ($productId) {
                $q->where('id', $productId);
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function($sub) use ($search) {
                    $sub->where('product_name', 'like', "%{$search}%")
                        ->orWhere('product_id', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            });

        $headers = ['Product ID', 'SKU', 'Product Name', 'Brand', 'Inward Quantity', 'Dispatch Quantity', 'Available Stock', 'Purchase Rate', 'Stock Value'];
        $data = [];

        foreach ($productsQuery->get() as $product) {
            $inQuery = InwardItemCode::where('product_id', $product->id);
            if ($start) $inQuery->where('created_at', '>=', $start);
            if ($end) $inQuery->where('created_at', '<=', $end);
            if ($portalId) $inQuery->where('portal_vendor_id', $portalId);

            $outQuery = DispatchItemCode::where('product_id', $product->id);
            if ($start) $outQuery->where('created_at', '>=', $start);
            if ($end) $outQuery->where('created_at', '<=', $end);
            if ($portalId) $outQuery->where('portal_vendor_id', $portalId);

            $inCount = $inQuery->count();
            $dispatchCount = $outQuery->count();
            $balance = max(0, $inCount - $dispatchCount);

            // Fetch latest purchase price
            $latestPurchase = $product->purchases()->latest('date')->latest('id')->first();
            $purchaseRate = $latestPurchase ? floatval($latestPurchase->price) : 0.00;
            $stockValue = $balance * $purchaseRate;

            $data[] = [
                $product->product_id,
                $product->sku ?? 'N/A',
                $product->product_name,
                $product->brand->name ?? 'N/A',
                $inCount,
                $dispatchCount,
                $balance,
                $purchaseRate,
                $stockValue
            ];
        }

        return CsvExcelService::export($headers, $data, 'stock_report_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Export Inward Detail log to Excel/CSV.
     */
    public function exportInward(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $productId = $request->input('product_id');
        $brandId = $request->input('brand_id');
        $portalId = $request->input('portal_id');
        $search = $request->input('search');

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        $inwardQuery = InwardItemCode::with(['product.brand', 'portal'])
            ->when($start, fn($q) => $q->where('created_at', '>=', $start))
            ->when($end, fn($q) => $q->where('created_at', '<=', $end))
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->when($brandId, fn($q) => $q->whereHas('product', fn($p) => $p->where('brand_id', $brandId)))
            ->when($portalId, fn($q) => $q->where('portal_vendor_id', $portalId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($p) use ($search) {
                            $p->where('product_name', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $headers = ['Serial UID', 'Product ID', 'SKU', 'Product Name', 'Brand', 'Status', 'Mark', 'Portal Vendor', 'Updated By', 'Inward Date'];
        $data = [];

        foreach ($inwardQuery->get() as $item) {
            $data[] = [
                $item->uid,
                $item->product->product_id ?? '',
                $item->product->sku ?? '',
                $item->product->product_name ?? '',
                $item->product->brand->name ?? '',
                $item->status,
                $item->mark ?? 'N/A',
                $item->portal->name ?? 'N/A',
                $item->updated_by ?? 'System',
                $item->created_at ? $item->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'inward_report_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Export Dispatch Detail log to Excel/CSV.
     */
    public function exportDispatch(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $productId = $request->input('product_id');
        $brandId = $request->input('brand_id');
        $portalId = $request->input('portal_id');
        $search = $request->input('search');

        $start = $startDate ? Carbon::parse($startDate)->startOfDay() : null;
        $end = $endDate ? Carbon::parse($endDate)->endOfDay() : null;

        $dispatchQuery = DispatchItemCode::with(['product.brand', 'portal'])
            ->when($start, fn($q) => $q->where('created_at', '>=', $start))
            ->when($end, fn($q) => $q->where('created_at', '<=', $end))
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->when($brandId, fn($q) => $q->whereHas('product', fn($p) => $p->where('brand_id', $brandId)))
            ->when($portalId, fn($q) => $q->where('portal_vendor_id', $portalId))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($p) use ($search) {
                            $p->where('product_name', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                            });
                    });
                })
            ->latest();

        $headers = ['Serial UID', 'Product ID', 'SKU', 'Product Name', 'Brand', 'Status', 'Portal Vendor', 'Updated By', 'Dispatch Date'];
        $data = [];

        foreach ($dispatchQuery->get() as $item) {
            $data[] = [
                $item->uid,
                $item->product->product_id ?? '',
                $item->product->sku ?? '',
                $item->product->product_name ?? '',
                $item->product->brand->name ?? '',
                $item->status,
                $item->portal->name ?? 'N/A',
                $item->updated_by ?? 'System',
                $item->created_at ? $item->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'dispatch_report_' . now()->format('Ymd_His') . '.csv');
    }
}
