<?php

namespace App\Http\Controllers;

use App\Models\DispatchItemCode;
use App\Models\InwardItemCode;
use App\Models\Product;
use App\Services\CsvExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DispatchItemCodeController extends Controller
{
    /**
     * Export dispatch item codes to Excel/CSV.
     */
    public function export(Request $request)
    {
        $search = $request->input('search');

        $dispatchItemCodes = DispatchItemCode::with(['product', 'portal'])
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
            ->latest()
            ->get();

        $headers = ['UID', 'Product ID', 'SKU', 'Product Name', 'Quantity', 'Status', 'Portal', 'Updated By', 'Created At'];
        $data = [];

        foreach ($dispatchItemCodes as $item) {
            $data[] = [
                $item->uid,
                $item->product->product_id ?? '',
                $item->product->sku ?? '',
                $item->product->product_name ?? '',
                $item->quantity,
                $item->status,
                $item->portal->name ?? '',
                $item->updated_by ?? 'System',
                $item->created_at ? $item->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'dispatch_item_codes_export_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Import dispatch item codes from Excel/CSV.
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
                $quantity = intval($record['Quantity'] ?? -1);
                $status = $record['Status'] ?? 'Sold';

                if (empty($uid)) {
                    $errors[] = "Row {$rowNumber}: UID is required.";
                    continue;
                }

                // Check for duplicate dispatch in DB
                if (DispatchItemCode::where('uid', $uid)->exists()) {
                    $errors[] = "Row {$rowNumber}: UID '{$uid}' is already dispatched.";
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

                DispatchItemCode::create([
                    'product_id' => $product->id,
                    'uid' => $uid,
                    'quantity' => $quantity,
                    'status' => $status,
                    'updated_by' => $user,
                ]);

                $imported++;
            }

            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->route('dispatch-item-codes.index')
                    ->with('error', 'Import failed due to validation errors. First few: ' . implode(' | ', array_slice($errors, 0, 5)));
            }

            DB::commit();

            return redirect()->route('dispatch-item-codes.index')
                ->with('success', "Dispatch item codes import completed successfully. Imported {$imported} records.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('dispatch-item-codes.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dispatchItemCodes = DispatchItemCode::with(['product', 'portal'])
            ->when($search, function ($query, $search) {
                $query->where(function ($sub) use ($search) {
                    $sub->where('uid', 'like', "%{$search}%")
                        ->orWhereHas('product', function ($q) use ($search) {
                            $q->where('product_name', 'like', "%{$search}%")
                                ->orWhere('product_id', 'like', "%{$search}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('dispatch_item_codes.index', compact('dispatchItemCodes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        
        // Fetch inward UIDs that have not been actively dispatched yet (ignoring cancelled)
        $dispatchedUids = DispatchItemCode::pluck('uid')->toArray();
        $availableInwardItems = InwardItemCode::whereNotIn('uid', $dispatchedUids)->get();

        return view('dispatch_item_codes.create', compact('products', 'availableInwardItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'uid' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $validated['quantity'] = -1;
        $validated['updated_by'] = Auth::user()->name ?? 'System';

        DB::transaction(function () use ($validated) {
            DispatchItemCode::create($validated);

            // Update Inward status to match dispatch status
            $inwardItem = InwardItemCode::where('uid', $validated['uid'])->first();
            if ($inwardItem) {
                $inwardItem->update([
                    'status' => $validated['status'],
                ]);
            }
        });

        return redirect()->route('dispatch-item-codes.index')
            ->with('success', 'Item dispatched successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DispatchItemCode $dispatchItemCode)
    {
        return view('dispatch_item_codes.show', compact('dispatchItemCode'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DispatchItemCode $dispatchItemCode)
    {
        $products = Product::all();
        
        // Fetch inward UIDs that have not been active (excluding this one and ignoring cancelled)
        $dispatchedUids = DispatchItemCode::where('id', '!=', $dispatchItemCode->id)->pluck('uid')->toArray();
        $availableInwardItems = InwardItemCode::whereNotIn('uid', $dispatchedUids)->get();

        return view('dispatch_item_codes.edit', compact('dispatchItemCode', 'products', 'availableInwardItems'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DispatchItemCode $dispatchItemCode)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'uid' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $validated['quantity'] = -1;
        $validated['updated_by'] = Auth::user()->name ?? 'System';

        DB::transaction(function () use ($dispatchItemCode, $validated) {
            $oldUid = $dispatchItemCode->uid;
            $newUid = $validated['uid'];

            $dispatchItemCode->update($validated);

            // If UID changed, reset old UID status to 'Good Inventory'
            if ($oldUid !== $newUid) {
                $oldInward = InwardItemCode::where('uid', $oldUid)->first();
                if ($oldInward) {
                    $oldInward->update([
                        'status' => 'Good Inventory',
                    ]);
                }
            }

            // Update new UID status to match dispatch status
            $newInward = InwardItemCode::where('uid', $newUid)->first();
            if ($newInward) {
                $newInward->update([
                    'status' => $validated['status'],
                ]);
            }
        });

        return redirect()->route('dispatch-item-codes.index')
            ->with('success', 'Dispatch record updated successfully.');
    }

    /**
     * Scan and cancel dispatch of a serial code.
     */
    public function scanCancel(Request $request)
    {
        $request->validate([
            'scan_uid' => 'required|string|max:255',
        ]);

        $uid = trim($request->input('scan_uid'));

        // Find the active Dispatch record
        $dispatchItem = DispatchItemCode::where('uid', $uid)->first();

        if (!$dispatchItem) {
            return back()->with('error', "Serial Code '{$uid}' is not currently dispatched.");
        }

        // Run in transaction to delete dispatch record and update Inward status to 'Good Inventory' or 'RTG' and mark as cancelled
        DB::transaction(function () use ($dispatchItem, $uid) {
            // Find matching InwardItemCode and set back to appropriate status and mark as cancelled
            $inwardItem = InwardItemCode::where('uid', $uid)->first();
            if ($inwardItem) {
                $newStatus = ($inwardItem->mark === 'Returned' || $inwardItem->status === 'RTG') ? 'RTG' : 'Good Inventory';
                $inwardItem->update([
                    'status' => $newStatus,
                    'portal_vendor_id' => null,
                    'mark' => 'cancelled',
                ]);
            }

            // Delete the dispatch record completely
            $dispatchItem->delete();
        });

        return back()->with('success', "Successfully cancelled dispatch for Serial Code '{$uid}'. It has been returned to Good Inventory.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DispatchItemCode $dispatchItemCode)
    {
        $uid = $dispatchItemCode->uid;

        DB::transaction(function () use ($dispatchItemCode, $uid) {
            // Find matching InwardItemCode and set back to appropriate status and mark as cancelled
            $inwardItem = InwardItemCode::where('uid', $uid)->first();
            if ($inwardItem) {
                $newStatus = ($inwardItem->mark === 'Returned' || $inwardItem->status === 'RTG') ? 'RTG' : 'Good Inventory';
                $inwardItem->update([
                    'status' => $newStatus,
                    'portal_vendor_id' => null,
                    'mark' => 'cancelled',
                ]);
            }

            // Delete the dispatch record completely
            $dispatchItemCode->delete();
        });

        return redirect()->route('dispatch-item-codes.index')
            ->with('success', 'Dispatch record cancelled and item returned to Good Inventory successfully.');
    }
}
