<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    /**
     * Live Omnisearch endpoint returning JSON grouped by category.
     */
    public function live(Request $request)
    {
        $q = trim((string) $request->input('q', ''));

        if (mb_strlen($q) < 1) {
            return response()->json([
                'query' => '',
                'total' => 0,
                'bills' => [],
                'products' => [],
                'purchases' => [],
                'sales' => [],
                'barcodes' => [],
                'users' => [],
            ]);
        }

        $user = Auth::user();

        // 1. Bills & Invoices
        $bills = [];
        if (!$user || $user->hasPermission('bills.view') || $user->hasPermission('purchases.view') || $user->hasPermission('sales.view')) {
            $lowerQ = strtolower($q);
            $bills = Bill::where(function ($query) use ($q, $lowerQ) {
                $query->where('invoice_number', 'like', "%{$q}%")
                      ->orWhere('party_name', 'like', "%{$q}%")
                      ->orWhere('party_gstin', 'like', "%{$q}%")
                      ->orWhere('payment_mode', 'like', "%{$q}%")
                      ->orWhere('notes', 'like', "%{$q}%")
                      ->orWhereRaw('LOWER(CAST(line_items AS CHAR)) LIKE ?', ["%{$lowerQ}%"]);
            })
            ->orderBy('invoice_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get()
            ->map(function ($bill) {
                return [
                    'id' => $bill->id,
                    'title' => 'Invoice #' . $bill->invoice_number,
                    'subtitle' => $bill->party_name . ($bill->party_gstin ? " • GSTIN: {$bill->party_gstin}" : ''),
                    'meta' => $bill->formatted_total_amount . ' • ' . ($bill->invoice_date ? $bill->invoice_date->format('d M Y') : 'N/A'),
                    'badge' => $bill->bill_type === 'purchase' ? 'Purchase Bill' : 'Sales Bill',
                    'badge_type' => $bill->bill_type,
                    'url' => route('bills.show', $bill),
                ];
            });
        }

        // 2. Products
        $products = [];
        if (!$user || $user->hasPermission('products.view')) {
            $products = Product::where(function ($query) use ($q) {
                $query->where('product_name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%")
                      ->orWhere('product_id', 'like', "%{$q}%")
                      ->orWhere('fsn', 'like', "%{$q}%")
                      ->orWhere('asin', 'like', "%{$q}%");
            })
            ->with('brand')
            ->latest('id')
            ->limit(6)
            ->get()
            ->map(function ($prod) {
                return [
                    'id' => $prod->id,
                    'title' => $prod->product_name,
                    'subtitle' => 'SKU: ' . ($prod->sku ?: 'N/A') . ' • ID: ' . $prod->product_id,
                    'meta' => 'Brand: ' . ($prod->brand->name ?? 'General') . ($prod->asin ? " • ASIN: {$prod->asin}" : ''),
                    'badge' => 'Product',
                    'badge_type' => 'product',
                    'url' => route('products.index', ['search' => $prod->sku ?: $prod->product_id]),
                ];
            });
        }

        // 3. Purchases
        $purchases = [];
        if (!$user || $user->hasPermission('purchases.view')) {
            $purchases = Purchase::with(['product'])
                ->where(function ($query) use ($q) {
                    $query->where('vendor_id', 'like', "%{$q}%")
                          ->orWhere('status', 'like', "%{$q}%")
                          ->orWhereHas('product', function ($sub) use ($q) {
                              $sub->where('product_name', 'like', "%{$q}%")
                                  ->orWhere('sku', 'like', "%{$q}%");
                          });
                })
                ->latest('date')
                ->limit(6)
                ->get()
                ->map(function ($po) {
                    return [
                        'id' => $po->id,
                        'title' => 'Purchase from ' . ($po->vendor_id ?: 'Vendor'),
                        'subtitle' => ($po->product->product_name ?? 'Product') . ' (Qty: ' . $po->quantity . ')',
                        'meta' => '₹' . number_format((float) $po->amount, 2) . ' • ' . ($po->date ? \Carbon\Carbon::parse($po->date)->format('d M Y') : 'N/A'),
                        'badge' => 'Purchase',
                        'badge_type' => 'purchase',
                        'url' => route('purchases.index', ['search' => $po->vendor_id]),
                    ];
                });
        }

        // 4. Sales
        $sales = [];
        if (!$user || $user->hasPermission('sales.view')) {
            $sales = Sale::with(['product'])
                ->where(function ($query) use ($q) {
                    $query->where('portal_id', 'like', "%{$q}%")
                          ->orWhereHas('product', function ($sub) use ($q) {
                              $sub->where('product_name', 'like', "%{$q}%")
                                  ->orWhere('sku', 'like', "%{$q}%");
                          });
                })
                ->latest('order_date')
                ->limit(6)
                ->get()
                ->map(function ($sale) {
                    return [
                        'id' => $sale->id,
                        'title' => 'Sale to ' . ($sale->portal_id ?: 'Customer'),
                        'subtitle' => ($sale->product->product_name ?? 'Product') . ' (Qty: ' . $sale->quantity . ')',
                        'meta' => 'Date: ' . ($sale->order_date ? \Carbon\Carbon::parse($sale->order_date)->format('d M Y') : 'N/A'),
                        'badge' => 'Sale',
                        'badge_type' => 'sale',
                        'url' => route('sales.index', ['search' => $sale->portal_id]),
                    ];
                });
        }

        // 5. Barcodes (Inward & Dispatch Item Codes)
        $barcodes = [];
        if (!$user || $user->hasPermission('inward_item_codes.view') || $user->hasPermission('dispatch_item_codes.view')) {
            $inwardCodes = InwardItemCode::where('uid', 'like', "%{$q}%")
                ->with('product')
                ->latest('id')
                ->limit(3)
                ->get()
                ->map(function ($code) {
                    return [
                        'id' => $code->id,
                        'title' => $code->uid,
                        'subtitle' => 'Inward • ' . ($code->product->product_name ?? 'Product Code'),
                        'meta' => 'Scanned ' . $code->created_at->format('d M Y'),
                        'badge' => 'Inward Barcode',
                        'badge_type' => 'inward',
                        'url' => route('inward-item-codes.index', ['search' => $code->uid]),
                    ];
                });

            $dispatchCodes = DispatchItemCode::where('uid', 'like', "%{$q}%")
                ->with('product')
                ->latest('id')
                ->limit(3)
                ->get()
                ->map(function ($code) {
                    return [
                        'id' => $code->id,
                        'title' => $code->uid,
                        'subtitle' => 'Dispatch • ' . ($code->product->product_name ?? 'Product Code'),
                        'meta' => 'Dispatched ' . $code->created_at->format('d M Y'),
                        'badge' => 'Dispatch Barcode',
                        'badge_type' => 'dispatch',
                        'url' => route('dispatch-item-codes.index', ['search' => $code->uid]),
                    ];
                });

            $barcodes = $inwardCodes->merge($dispatchCodes)->values();
        }

        // 6. Users (Admin Only)
        $users = [];
        if ($user && ($user->role === 'admin' || $user->hasPermission('users.view'))) {
            $users = User::where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('email', 'like', "%{$q}%")
                      ->orWhere('role', 'like', "%{$q}%");
            })
            ->latest('id')
            ->limit(4)
            ->get()
            ->map(function ($u) {
                return [
                    'id' => $u->id,
                    'title' => $u->name,
                    'subtitle' => $u->email,
                    'meta' => 'Role: ' . ucfirst($u->role),
                    'badge' => 'User',
                    'badge_type' => 'user',
                    'url' => route('users.index', ['search' => $u->name]),
                ];
            });
        }

        $totalCount = count($bills) + count($products) + count($purchases) + count($sales) + count($barcodes) + count($users);

        return response()->json([
            'query' => $q,
            'total' => $totalCount,
            'bills' => $bills,
            'products' => $products,
            'purchases' => $purchases,
            'sales' => $sales,
            'barcodes' => $barcodes,
            'users' => $users,
        ]);
    }

    /**
     * Dedicated Full Global Search Results Page.
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $tab = $request->input('tab', 'all');

        $user = Auth::user();

        // Query collections
        $bills = collect();
        if (!$user || $user->hasPermission('bills.view') || $user->hasPermission('purchases.view') || $user->hasPermission('sales.view')) {
            $lowerQ = strtolower($q);
            $bills = Bill::where(function ($query) use ($q, $lowerQ) {
                if (!empty($q)) {
                    $query->where('invoice_number', 'like', "%{$q}%")
                          ->orWhere('party_name', 'like', "%{$q}%")
                          ->orWhere('party_gstin', 'like', "%{$q}%")
                          ->orWhere('payment_mode', 'like', "%{$q}%")
                          ->orWhere('notes', 'like', "%{$q}%")
                          ->orWhereRaw('LOWER(CAST(line_items AS CHAR)) LIKE ?', ["%{$lowerQ}%"]);
                }
            })
            ->orderBy('invoice_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15, ['*'], 'bills_page');
        }

        $products = collect();
        if (!$user || $user->hasPermission('products.view')) {
            $products = Product::where(function ($query) use ($q) {
                if (!empty($q)) {
                    $query->where('product_name', 'like', "%{$q}%")
                          ->orWhere('sku', 'like', "%{$q}%")
                          ->orWhere('product_id', 'like', "%{$q}%")
                          ->orWhere('fsn', 'like', "%{$q}%")
                          ->orWhere('asin', 'like', "%{$q}%");
                }
            })
            ->with('brand')
            ->latest('id')
            ->paginate(15, ['*'], 'products_page');
        }

        $purchases = collect();
        if (!$user || $user->hasPermission('purchases.view')) {
            $purchases = Purchase::with(['product'])
                ->where(function ($query) use ($q) {
                    if (!empty($q)) {
                        $query->where('vendor_id', 'like', "%{$q}%")
                              ->orWhere('status', 'like', "%{$q}%")
                              ->orWhereHas('product', function ($sub) use ($q) {
                                  $sub->where('product_name', 'like', "%{$q}%")
                                      ->orWhere('sku', 'like', "%{$q}%");
                              });
                    }
                })
                ->latest('date')
                ->paginate(15, ['*'], 'purchases_page');
        }

        $sales = collect();
        if (!$user || $user->hasPermission('sales.view')) {
            $sales = Sale::with(['product'])
                ->where(function ($query) use ($q) {
                    if (!empty($q)) {
                        $query->where('portal_id', 'like', "%{$q}%")
                              ->orWhereHas('product', function ($sub) use ($q) {
                                  $sub->where('product_name', 'like', "%{$q}%")
                                      ->orWhere('sku', 'like', "%{$q}%");
                              });
                    }
                })
                ->latest('order_date')
                ->paginate(15, ['*'], 'sales_page');
        }

        $inwardCodes = collect();
        if (!$user || $user->hasPermission('inward_item_codes.view')) {
            $inwardCodes = InwardItemCode::where(function ($query) use ($q) {
                if (!empty($q)) {
                    $query->where('uid', 'like', "%{$q}%");
                }
            })
            ->with('product')
            ->latest('id')
            ->paginate(15, ['*'], 'inward_page');
        }

        $dispatchCodes = collect();
        if (!$user || $user->hasPermission('dispatch_item_codes.view')) {
            $dispatchCodes = DispatchItemCode::where(function ($query) use ($q) {
                if (!empty($q)) {
                    $query->where('uid', 'like', "%{$q}%");
                }
            })
            ->with('product')
            ->latest('id')
            ->paginate(15, ['*'], 'dispatch_page');
        }

        $users = collect();
        if ($user && ($user->role === 'admin' || $user->hasPermission('users.view'))) {
            $users = User::where(function ($query) use ($q) {
                if (!empty($q)) {
                    $query->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%")
                          ->orWhere('role', 'like', "%{$q}%");
                }
            })
            ->latest('id')
            ->paginate(15, ['*'], 'users_page');
        }

        $counts = [
            'bills' => $bills instanceof \Illuminate\Pagination\LengthAwarePaginator ? $bills->total() : 0,
            'products' => $products instanceof \Illuminate\Pagination\LengthAwarePaginator ? $products->total() : 0,
            'purchases' => $purchases instanceof \Illuminate\Pagination\LengthAwarePaginator ? $purchases->total() : 0,
            'sales' => $sales instanceof \Illuminate\Pagination\LengthAwarePaginator ? $sales->total() : 0,
            'inward' => $inwardCodes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $inwardCodes->total() : 0,
            'dispatch' => $dispatchCodes instanceof \Illuminate\Pagination\LengthAwarePaginator ? $dispatchCodes->total() : 0,
            'users' => $users instanceof \Illuminate\Pagination\LengthAwarePaginator ? $users->total() : 0,
        ];

        $counts['total'] = array_sum($counts);

        return view('search.index', compact(
            'q',
            'tab',
            'counts',
            'bills',
            'products',
            'purchases',
            'sales',
            'inwardCodes',
            'dispatchCodes',
            'users'
        ));
    }
}
