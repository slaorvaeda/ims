<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inputs & Filters
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $productId = $request->input('product_id');
        $brandId = $request->input('brand_id');
        $portalId = $request->input('portal_id');
        $vendorId = $request->input('vendor_id');
        $search = $request->input('search');
        $type = $request->input('type'); // Filter by activity type

        // Sorting params
        $sortBy = $request->input('sort_by', 'date');
        $sortDir = $request->input('sort_dir', 'desc');

        // Fetch helper models for filters dropdown
        $products = Product::orderBy('product_name')->get();
        $brands = \App\Models\Brand::orderBy('name')->get();
        
        // Fetch unique portals and vendors for dropdowns
        $portalsDb = \App\Models\PortalVendor::where('type', 'Portal')->orderBy('name')->pluck('name')->toArray();
        $portalsSales = Sale::select('portal_id')->distinct()->whereNotNull('portal_id')->pluck('portal_id')->toArray();
        $portals = array_values(array_unique(array_merge($portalsDb, $portalsSales)));
        $vendors = Purchase::select('vendor_id')->distinct()->whereNotNull('vendor_id')->pluck('vendor_id');

        // Format dates for query limits
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // 2. Fetch Filtered Data
        
        // A. Purchases
        $purchasesQuery = Purchase::with('product')
            ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->when($vendorId, function ($q) use ($vendorId) {
                $q->where('vendor_id', $vendorId);
            })
            ->when($brandId, function ($q) use ($brandId) {
                $q->whereHas('product', function ($p) use ($brandId) {
                    $p->where('brand_id', $brandId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('vendor_id', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($pq) use ($search) {
                            $pq->where('product_name', 'like', "%{$search}%")
                              ->orWhere('product_id', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            });

        $filteredPurchases = $purchasesQuery->get();

        // B. Sales
        $salesQuery = Sale::with('product')
            ->whereBetween('order_date', [$start->toDateString(), $end->toDateString()])
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->when($portalId, function ($q) use ($portalId) {
                $q->where('portal_id', $portalId);
            })
            ->when($brandId, function ($q) use ($brandId) {
                $q->whereHas('product', function ($p) use ($brandId) {
                    $p->where('brand_id', $brandId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('portal_id', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($sq) use ($search) {
                            $sq->where('product_name', 'like', "%{$search}%")
                              ->orWhere('product_id', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            });

        $filteredSales = $salesQuery->get();

        // C. Inwards
        $inwardsQuery = InwardItemCode::with(['product', 'portal'])
            ->whereBetween('created_at', [$start, $end])
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->when($portalId, function ($q) use ($portalId) {
                $q->whereHas('portal', function ($sub) use ($portalId) {
                    $sub->where('name', $portalId);
                });
            })
            ->when($brandId, function ($q) use ($brandId) {
                $q->whereHas('product', function ($p) use ($brandId) {
                    $p->where('brand_id', $brandId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($iq) use ($search) {
                            $iq->where('product_name', 'like', "%{$search}%")
                              ->orWhere('product_id', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            });

        $filteredInwards = $inwardsQuery->get();

        // D. Dispatches
        $dispatchesQuery = DispatchItemCode::with(['product', 'portal'])
            ->where(function ($q) {
                $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
            })
            ->whereBetween('created_at', [$start, $end])
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->when($portalId, function ($q) use ($portalId) {
                $q->whereHas('portal', function ($sub) use ($portalId) {
                    $sub->where('name', $portalId);
                });
            })
            ->when($brandId, function ($q) use ($brandId) {
                $q->whereHas('product', function ($p) use ($brandId) {
                    $p->where('brand_id', $brandId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($dq) use ($search) {
                            $dq->where('product_name', 'like', "%{$search}%")
                              ->orWhere('product_id', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            });

        $filteredDispatches = $dispatchesQuery->get();

        // 3. Consolidated KPIs
        $totalPurchasesCost = $filteredPurchases->sum('amount');
        $totalUnitsPurchased = $filteredPurchases->sum('quantity');
        $totalSalesCount = $filteredSales->count();
        $totalUnitsSold = $filteredSales->sum('quantity');
        $totalUnitsInwarded = $filteredInwards->sum('quantity');
        $totalUnitsDispatched = abs($filteredDispatches->sum('quantity'));

        // Calculate Total Sales Revenue based on product selling_price
        $totalSalesValue = $filteredSales->sum(function ($sale) {
            return $sale->quantity * ($sale->product->selling_price ?? 0);
        });

        // Calculate real-time Available Stock matching active filters (Inwarded codes not dispatched)
        $dispatchedUidsQuery = DispatchItemCode::where(function ($q) {
            $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
        });
        $inwardsStockQuery = InwardItemCode::where(function ($q) {
            $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
        });

        if ($productId) {
            $dispatchedUidsQuery->where('product_id', $productId);
            $inwardsStockQuery->where('product_id', $productId);
        }
        if ($portalId) {
            $dispatchedUidsQuery->whereHas('portal', function ($sub) use ($portalId) {
                $sub->where('name', $portalId);
            });
            $inwardsStockQuery->whereHas('portal', function ($sub) use ($portalId) {
                $sub->where('name', $portalId);
            });
        }
        if ($brandId) {
            $dispatchedUidsQuery->whereHas('product', function ($p) use ($brandId) {
                $p->where('brand_id', $brandId);
            });
            $inwardsStockQuery->whereHas('product', function ($p) use ($brandId) {
                $p->where('brand_id', $brandId);
            });
        }
        if ($search) {
            $dispatchedUidsQuery->where('uid', 'like', "%{$search}%");
            $inwardsStockQuery->where('uid', 'like', "%{$search}%");
        }

        $dispatchedUids = $dispatchedUidsQuery->pluck('uid')->toArray();
        $totalUnitsAvailable = $inwardsStockQuery->whereNotIn('uid', $dispatchedUids)->count();
        
        $totalUnitsDamaged = $filteredPurchases->where('status', 'Damaged')->sum('quantity');
        $damagedPurchases = $filteredPurchases->where('status', 'Damaged')->values();
        
        $totalRTGSold = DispatchItemCode::where(function ($q) {
                $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
            })
            ->whereBetween('created_at', [$start, $end])
            ->when($productId, function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->when($brandId, function ($q) use ($brandId) {
                $q->whereHas('product', function ($p) use ($brandId) {
                    $p->where('brand_id', $brandId);
                });
            })
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($dq) use ($search) {
                            $dq->where('product_name', 'like', "%{$search}%")
                              ->orWhere('product_id', 'like', "%{$search}%")
                              ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->whereIn('uid', function ($q) {
                $q->select('uid')->from('inward_item_codes')->where('mark', 'Returned');
            })
            ->count();

        $totalInwardRTG = $filteredInwards->where('status', 'RTG')->sum('quantity');

        // Calculate per-product Stock Breakdown (Inward, Outward/Sold, Available) for the dashboard modal
        $productsQuery = Product::with(['brand']);
        if ($brandId) {
            $productsQuery->where('brand_id', $brandId);
        }
        if ($productId) {
            $productsQuery->where('id', $productId);
        }
        if ($search) {
            $productsQuery->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('product_id', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $stockBreakdown = $productsQuery->get()
            ->map(function ($product) use ($portalId) {
                $inwardQuery = InwardItemCode::where('product_id', $product->id)
                    ->where(function ($q) {
                        $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
                    });
                
                $dispatchQuery = DispatchItemCode::where('product_id', $product->id)
                    ->where(function ($q) {
                        $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
                    });

                if ($portalId) {
                    $inwardQuery->whereHas('portal', function ($sub) use ($portalId) {
                        $sub->where('name', $portalId);
                    });
                    $dispatchQuery->whereHas('portal', function ($sub) use ($portalId) {
                        $sub->where('name', $portalId);
                    });
                }
                
                $inwardCount = $inwardQuery->count();
                $dispatchCount = $dispatchQuery->count();
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

        // 4. Combined Activity Log Collection
        $activities = new Collection();

        if (empty($type) || $type === 'Purchase') {
            foreach ($filteredPurchases as $p) {
                $activities->push([
                    'id' => 'P-' . $p->id,
                    'date' => $p->date,
                    'type' => 'Purchase',
                    'product_name' => $p->product->product_name ?? 'N/A',
                    'product_id_code' => $p->product->product_id ?? '',
                    'sku' => $p->product->sku ?? '',
                    'quantity' => $p->quantity,
                    'amount' => $p->amount,
                    'entity_name' => $p->vendor_id,
                    'portal' => null,
                    'updated_by' => $p->updated_by ?? 'System'
                ]);
            }
        }

        if (empty($type) || $type === 'Sale') {
            foreach ($filteredSales as $s) {
                $activities->push([
                    'id' => 'S-' . $s->id,
                    'date' => $s->order_date,
                    'type' => 'Sale',
                    'product_name' => $s->product->product_name ?? 'N/A',
                    'product_id_code' => $s->product->product_id ?? '',
                    'sku' => $s->product->sku ?? '',
                    'quantity' => $s->quantity,
                    'amount' => null,
                    'entity_name' => null,
                    'portal' => $s->portal_id,
                    'updated_by' => $s->updated_by ?? 'System'
                ]);
            }
        }

        if (empty($type) || $type === 'Inward') {
            foreach ($filteredInwards as $i) {
                $activities->push([
                    'id' => 'I-' . $i->id,
                    'date' => $i->created_at->toDateString(),
                    'type' => 'Inward',
                    'product_name' => $i->product->product_name ?? 'N/A',
                    'product_id_code' => $i->product->product_id ?? '',
                    'sku' => $i->product->sku ?? '',
                    'quantity' => $i->quantity,
                    'amount' => null,
                    'entity_name' => $i->uid,
                    'portal' => $i->portal->name ?? null,
                    'updated_by' => $i->updated_by ?? 'System'
                ]);
            }
        }

        if (empty($type) || $type === 'Dispatch') {
            foreach ($filteredDispatches as $d) {
                $activities->push([
                    'id' => 'D-' . $d->id,
                    'date' => $d->created_at->toDateString(),
                    'type' => 'Dispatch',
                    'product_name' => $d->product->product_name ?? 'N/A',
                    'product_id_code' => $d->product->product_id ?? '',
                    'sku' => $d->product->sku ?? '',
                    'quantity' => abs($d->quantity),
                    'amount' => null,
                    'entity_name' => $d->uid,
                    'portal' => $d->portal->name ?? null,
                    'updated_by' => $d->updated_by ?? 'System'
                ]);
            }
        }

        // Apply Collection Sorting
        $isDesc = strtolower($sortDir) === 'desc';
        if ($sortBy === 'date') {
            $activities = $activities->sortBy('date', SORT_REGULAR, $isDesc);
        } elseif ($sortBy === 'type') {
            $activities = $activities->sortBy('type', SORT_REGULAR, $isDesc);
        } elseif ($sortBy === 'product_name') {
            $activities = $activities->sortBy('product_name', SORT_REGULAR, $isDesc);
        } elseif ($sortBy === 'quantity') {
            $activities = $activities->sortBy('quantity', SORT_REGULAR, $isDesc);
        } elseif ($sortBy === 'amount') {
            $activities = $activities->sortBy('amount', SORT_REGULAR, $isDesc);
        } elseif ($sortBy === 'entity_name') {
            $activities = $activities->sortBy('entity_name', SORT_REGULAR, $isDesc);
        }

        // Paginate results manually
        $page = $request->input('page', 1);
        $perPage = 15;
        $sliced = $activities->slice(($page - 1) * $perPage, $perPage)->values();
        $paginatedActivities = new LengthAwarePaginator(
            $sliced,
            $activities->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // 5. Chart Data Calculations
        // Generate daily datasets for Line Chart (Sales Qty vs Purchases Qty)
        $chartData = [];
        $tempDate = Carbon::parse($startDate);
        while ($tempDate->lte($end)) {
            $dateStr = $tempDate->toDateString();
            $label = $tempDate->format('M d');
            
            // Sum dispatched & purchases quantities on this day
            $sQty = $filteredDispatches->filter(function($d) use ($dateStr) {
                return $d->created_at->toDateString() === $dateStr;
            })->sum(function($d) {
                return abs($d->quantity);
            });
            $pQty = $filteredPurchases->where('date', $dateStr)->sum('quantity');

            $chartData[$dateStr] = [
                'label' => $label,
                'sales' => $sQty,
                'purchases' => $pQty
            ];
            $tempDate->addDay();
        }

        $chartLabels = array_column($chartData, 'label');
        $chartSales = array_column($chartData, 'sales');
        $chartPurchases = array_column($chartData, 'purchases');

        // Portal Breakdown for Pie Chart (Sales Quantity per Portal)
        $portalData = [];
        $groupedDispatches = $filteredDispatches->groupBy(function($d) {
            return $d->portal->name ?? 'Unknown';
        });
        foreach ($groupedDispatches as $portalName => $group) {
            $portalData[] = [
                'portal' => $portalName,
                'qty' => $group->sum(function($d) { return abs($d->quantity); })
            ];
        }

        $portalLabels = array_column($portalData, 'portal');
        $portalValues = array_column($portalData, 'qty');

        return view('analytics.index', compact(
            'products',
            'brands',
            'portals',
            'vendors',
            'startDate',
            'endDate',
            'productId',
            'brandId',
            'portalId',
            'vendorId',
            'search',
            'type',
            'sortBy',
            'sortDir',
            'totalPurchasesCost',
            'totalUnitsPurchased',
            'totalSalesCount',
            'totalUnitsSold',
            'totalSalesValue',
            'totalUnitsAvailable',
            'totalUnitsInwarded',
            'totalUnitsDispatched',
            'totalUnitsDamaged',
            'damagedPurchases',
            'totalRTGSold',
            'totalInwardRTG',
            'stockBreakdown',
            'paginatedActivities',
            'chartLabels',
            'chartSales',
            'chartPurchases',
            'portalLabels',
            'portalValues'
        ));
    }
}
