<?php

namespace App\Http\Controllers;

use App\Models\InwardItemCode;
use App\Models\Product;
use App\Services\CsvExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InwardItemCodeController extends Controller
{
    /**
     * Export inward item codes to Excel/CSV.
     */
    public function export(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $portalId = $request->input('portal_id');
        $productId = $request->input('product_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $validSortFields = ['id', 'uid', 'status', 'created_at', 'updated_at', 'updated_by'];
        $sortBy = in_array($request->input('sort_by'), $validSortFields) ? $request->input('sort_by') : 'id';
        $sortDir = in_array(strtolower($request->input('sort_dir')), ['asc', 'desc']) ? strtolower($request->input('sort_dir')) : 'asc';

        $inwardItemCodes = InwardItemCode::with(['product', 'portal'])
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($q) use ($search) {
                            $q->where('product_name', 'like', "%{$search}%")
                                ->orWhere('product_id', 'like', "%{$search}%")
                                ->orWhere('sku', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($portalId, function ($query, $portalId) {
                $query->where('portal_vendor_id', $portalId);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($startDate || $endDate, function ($query) use ($startDate, $endDate, $status) {
                $utcStart = $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay()->utc() : null;
                $utcEnd = $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay()->utc() : null;

                if ($status === 'Sold' || $status === 'Damaged') {
                    if ($utcStart) $query->where('updated_at', '>=', $utcStart);
                    if ($utcEnd) $query->where('updated_at', '<=', $utcEnd);
                } elseif ($status === 'Good Inventory') {
                    if ($utcStart) $query->where('created_at', '>=', $utcStart);
                    if ($utcEnd) $query->where('created_at', '<=', $utcEnd);
                } else {
                    $query->where(function ($q) use ($utcStart, $utcEnd) {
                        $q->where(function ($sub) use ($utcStart, $utcEnd) {
                            if ($utcStart) $sub->where('created_at', '>=', $utcStart);
                            if ($utcEnd) $sub->where('created_at', '<=', $utcEnd);
                        })->orWhere(function ($sub) use ($utcStart, $utcEnd) {
                            if ($utcStart) $sub->where('updated_at', '>=', $utcStart);
                            if ($utcEnd) $sub->where('updated_at', '<=', $utcEnd);
                        });
                    });
                }
            })
            ->orderBy($sortBy, $sortDir)
            ->get();

        $headers = ['UID', 'Product ID', 'SKU', 'Product Name', 'Quantity', 'Status', 'Mark', 'Updated By', 'Created At'];
        $data = [];

        foreach ($inwardItemCodes as $item) {
            $data[] = [
                $item->uid,
                $item->product->product_id ?? '',
                $item->product->sku ?? '',
                $item->product->product_name ?? '',
                $item->quantity,
                $item->status,
                $item->mark,
                $item->updated_by ?? 'System',
                $item->created_at ? $item->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'inward_item_codes_export_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Import inward item codes from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:4096',
        ]);

        try {
            $records = CsvExcelService::import(
                $request->file('file')->getRealPath(),
                ['UID']
            );

            $imported = 0;
            $errors = [];
            $user = Auth::user()->name ?? 'System';

            DB::beginTransaction();

            foreach ($records as $index => $record) {
                $rowNumber = $index + 2;

                $uid = $record['UID'] ?? '';
                $productIdCode = $record['Product ID'] ?? '';
                $sku = $record['SKU'] ?? '';
                $quantity = intval($record['Quantity'] ?? 1);
                $status = $record['Status'] ?? 'In Stock';
                $mark = $record['Mark'] ?? null;

                if (empty($uid)) {
                    $errors[] = "Row {$rowNumber}: UID is required.";
                    continue;
                }

                // Check for UID uniqueness
                if (InwardItemCode::where('uid', $uid)->exists()) {
                    $errors[] = "Row {$rowNumber}: Duplicate UID '{$uid}'. It already exists.";
                    continue;
                }

                if (empty($productIdCode) && empty($sku)) {
                    $errors[] = "Row {$rowNumber}: Missing both Product ID and SKU.";
                    continue;
                }

                $product = null;
                if (!empty($sku)) {
                    $product = Product::where('sku', $sku)->first();
                }
                if (!$product && !empty($productIdCode)) {
                    $product = Product::where('product_id', $productIdCode)->first();
                }

                if (!$product) {
                    $errors[] = "Row {$rowNumber}: Product not found with Product ID '{$productIdCode}' or SKU '{$sku}'.";
                    continue;
                }

                InwardItemCode::create([
                    'product_id' => $product->id,
                    'uid' => $uid,
                    'quantity' => $quantity,
                    'status' => $status,
                    'mark' => $mark,
                    'updated_by' => $user,
                ]);

                $imported++;
            }

            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->route('inward-item-codes.index')
                    ->with('error', 'Import failed due to validation errors. First few: ' . implode(' | ', array_slice($errors, 0, 5)));
            }

            DB::commit();

            return redirect()->route('inward-item-codes.index')
                ->with('success', "Inward item codes import completed successfully. Imported {$imported} records.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('inward-item-codes.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $portalId = $request->input('portal_id');
        $productId = $request->input('product_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $validSortFields = ['id', 'uid', 'status', 'created_at', 'updated_at', 'updated_by'];
        $sortBy = in_array($request->input('sort_by'), $validSortFields) ? $request->input('sort_by') : 'id';
        $sortDir = in_array(strtolower($request->input('sort_dir')), ['asc', 'desc']) ? strtolower($request->input('sort_dir')) : 'asc';

        $inwardItemCodes = InwardItemCode::with(['product', 'portal'])
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($q) use ($search) {
                            $q->where('product_name', 'like', "%{$search}%")
                                ->orWhere('product_id', 'like', "%{$search}%");
                        });
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($portalId, function ($query, $portalId) {
                $query->where('portal_vendor_id', $portalId);
            })
            ->when($productId, function ($query, $productId) {
                $query->where('product_id', $productId);
            })
            ->when($startDate || $endDate, function ($query) use ($startDate, $endDate, $status) {
                $utcStart = $startDate ? \Carbon\Carbon::parse($startDate)->startOfDay()->utc() : null;
                $utcEnd = $endDate ? \Carbon\Carbon::parse($endDate)->endOfDay()->utc() : null;

                if ($status === 'Sold' || $status === 'Damaged') {
                    if ($utcStart) $query->where('updated_at', '>=', $utcStart);
                    if ($utcEnd) $query->where('updated_at', '<=', $utcEnd);
                } elseif ($status === 'Good Inventory') {
                    if ($utcStart) $query->where('created_at', '>=', $utcStart);
                    if ($utcEnd) $query->where('created_at', '<=', $utcEnd);
                } else {
                    $query->where(function ($q) use ($utcStart, $utcEnd) {
                        $q->where(function ($sub) use ($utcStart, $utcEnd) {
                            if ($utcStart) $sub->where('created_at', '>=', $utcStart);
                            if ($utcEnd) $sub->where('created_at', '<=', $utcEnd);
                        })->orWhere(function ($sub) use ($utcStart, $utcEnd) {
                            if ($utcStart) $sub->where('updated_at', '>=', $utcStart);
                            if ($utcEnd) $sub->where('updated_at', '<=', $utcEnd);
                        });
                    });
                }
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(15)
            ->withQueryString();

        $portals = \App\Models\PortalVendor::where('type', 'Portal')->orderBy('name')->get();
        $products = Product::orderBy('product_name')->get();

        return view('inward_item_codes.index', compact(
            'inwardItemCodes',
            'search',
            'status',
            'portals',
            'products',
            'portalId',
            'productId',
            'sortBy',
            'sortDir',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('inward_item_codes.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'start_uid' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1|max:1000',
            'status' => 'required|string|max:255',
        ]);

        $startUid = $request->input('start_uid');
        $quantity = (int) $request->input('quantity');

        // Extract prefix and numerical suffix
        if (preg_match('/^(.*?)(\d+)$/', $startUid, $matches)) {
            $prefix = $matches[1];
            $numberStr = $matches[2];
            $startNum = (int)$numberStr;
            $padLength = strlen($numberStr);
        } else {
            $prefix = $startUid;
            $startNum = 1;
            $padLength = 1;
        }

        // Generate the sequence of UIDs
        $uids = [];
        for ($i = 0; $i < $quantity; $i++) {
            $currentNum = $startNum + $i;
            $currentNumStr = str_pad((string)$currentNum, $padLength, '0', STR_PAD_LEFT);
            $uids[] = $prefix . $currentNumStr;
        }

        // Validate all generated UIDs are unique
        $duplicates = InwardItemCode::whereIn('uid', $uids)->pluck('uid')->toArray();
        if (!empty($duplicates)) {
            return back()->withErrors([
                'start_uid' => 'The following generated serial codes already exist in the database: ' . implode(', ', $duplicates)
            ])->withInput();
        }

        // Insert in transaction
        DB::transaction(function () use ($request, $uids) {
            $updatedBy = Auth::user()->name ?? 'System';
            foreach ($uids as $uid) {
                InwardItemCode::create([
                    'product_id' => $request->product_id,
                    'uid' => $uid,
                    'quantity' => 1,
                    'status' => $request->status,
                    'updated_by' => $updatedBy,
                ]);
            }
        });

        return redirect()->route('inward-item-codes.index')
            ->with('success', "Successfully registered {$quantity} inward stock items.");
    }

    /**
     * Scan and automatically dispatch a serial code as Sold.
     */
    public function scanDispatch(Request $request)
    {
        $request->validate([
            'scan_uid' => 'required|string|max:255',
            'portal_vendor_id' => 'required|exists:portal_vendors,id',
        ]);

        $uid = trim($request->input('scan_uid'));
        $portalVendorId = $request->input('portal_vendor_id');

        // Find available InwardItemCode with its product loaded
        $inwardItem = InwardItemCode::with('product')->where('uid', $uid)->first();

        if (!$inwardItem) {
            return back()->with('error', "Serial Code '{$uid}' does not exist in Inward Stock.");
        }

        if ($inwardItem->status === 'Sold') {
            return back()->with('error', "Serial Code '{$uid}' has already been sold/dispatched.");
        }

        // Run in transaction to update status in Inward and insert in Dispatch
        DB::transaction(function () use ($inwardItem, $uid, $portalVendorId) {
            // Update Inward status and portal
            $inwardItem->update([
                'status' => 'Sold',
                'portal_vendor_id' => $portalVendorId,
            ]);

            // Create Dispatch record
            \App\Models\DispatchItemCode::create([
                'product_id' => $inwardItem->product_id,
                'portal_vendor_id' => $portalVendorId,
                'uid' => $uid,
                'quantity' => -1,
                'status' => 'Sold',
                'updated_by' => Auth::user()->name ?? 'System',
            ]);
        });

        $inwardItem->load('portal');

        // Fire WebSocket event for live update (wrapped in try-catch to prevent broadcast failures from failing the web request)
        $operator = Auth::user()->name ?? 'System';
        try {
            event(new \App\Events\BarcodeDispatched($inwardItem, $uid, $operator));
        } catch (\Exception $broadcastException) {
            // Log the failure, but do not interrupt the successful response
            \Illuminate\Support\Facades\Log::warning('BarcodeDispatched web broadcast failed: ' . $broadcastException->getMessage());
        }

        // Store the dispatched portal in session to remember it for subsequent scans
        session(['last_dispatched_portal_id' => $portalVendorId]);

        return back()->with('success', "Successfully dispatched Serial Code '{$uid}' as Sold.");
    }

    /**
     * Display the specified resource.
     */
    public function show(InwardItemCode $inwardItemCode)
    {
        return view('inward_item_codes.show', compact('inwardItemCode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InwardItemCode $inwardItemCode)
    {
        $products = Product::all();
        return view('inward_item_codes.edit', compact('inwardItemCode', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InwardItemCode $inwardItemCode)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'uid' => 'required|string|max:255|unique:inward_item_codes,uid,' . $inwardItemCode->id,
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string|max:255',
        ]);

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $inwardItemCode->update($validated);

        return redirect()->route('inward-item-codes.index')
            ->with('success', 'Inward item code updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InwardItemCode $inwardItemCode)
    {
        $inwardItemCode->delete();

        return redirect()->route('inward-item-codes.index')
            ->with('success', 'Inward item code deleted successfully.');
    }
}
