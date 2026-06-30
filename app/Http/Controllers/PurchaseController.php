<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\InwardItemCode;
use App\Models\Brand;
use App\Services\CsvExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Export purchases to Excel/CSV.
     */
    public function export(Request $request)
    {
        $search = $request->input('search');

        $purchases = Purchase::with('product')
            ->when($search, function ($query, $search) {
                $query->where('vendor_id', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->get();

        $headers = ['Date', 'Product ID', 'SKU', 'Product Name', 'Vendor ID', 'Quantity', 'Price', 'Amount', 'Status', 'Mark', 'Updated By', 'Created At'];
        $data = [];

        foreach ($purchases as $purchase) {
            $data[] = [
                $purchase->date,
                $purchase->product->product_id ?? '',
                $purchase->product->sku ?? '',
                $purchase->product->product_name ?? '',
                $purchase->vendor_id,
                $purchase->quantity,
                $purchase->price,
                $purchase->amount,
                $purchase->status,
                $purchase->mark,
                $purchase->updated_by ?? 'System',
                $purchase->created_at ? $purchase->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'purchases_export_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Import purchases from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:4096',
        ]);

        try {
            $records = CsvExcelService::import(
                $request->file('file')->getRealPath(),
                ['Date', 'Vendor ID', 'Quantity', 'Price']
            );

            $imported = 0;
            $errors = [];
            $user = Auth::user()->name ?? 'System';

            DB::beginTransaction();

            foreach ($records as $index => $record) {
                $rowNumber = $index + 2;

                $date = $record['Date'] ?? '';
                $vendorId = $record['Vendor ID'] ?? '';
                $quantity = intval($record['Quantity'] ?? 0);
                $price = floatval($record['Price'] ?? 0);
                $productIdCode = $record['Product ID'] ?? '';
                $sku = $record['SKU'] ?? '';

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

                if (empty($date)) {
                    $errors[] = "Row {$rowNumber}: Date is required.";
                    continue;
                }

                if ($quantity <= 0) {
                    $errors[] = "Row {$rowNumber}: Quantity must be greater than 0.";
                    continue;
                }

                if ($price <= 0) {
                    $errors[] = "Row {$rowNumber}: Price must be greater than 0.";
                    continue;
                }

                $amount = floatval($record['Amount'] ?? ($price * $quantity));
                $status = $record['Status'] ?? 'Good Inventory';
                $mark = $record['Mark'] ?? (($status === 'Damaged' || $status === 'Returned') ? $status : null);

                Purchase::create([
                    'product_id' => $product->id,
                    'date' => $date,
                    'vendor_id' => $vendorId,
                    'quantity' => $quantity,
                    'price' => $price,
                    'amount' => $amount,
                    'status' => $status,
                    'mark' => $mark,
                    'updated_by' => $user,
                ]);

                $imported++;
            }

            if (!empty($errors)) {
                DB::rollBack();
                // Render with html-safe formatting since error messages might contain linebreaks
                return redirect()->route('purchases.index')
                    ->with('error', 'Import failed due to validation errors. First few: ' . implode(' | ', array_slice($errors, 0, 5)));
            }

            DB::commit();

            return redirect()->route('purchases.index')
                ->with('success', "Purchases import completed successfully. Imported {$imported} records.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('purchases.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $purchases = Purchase::with(['product', 'brand'])
            ->when($search, function ($query, $search) {
                $query->where('vendor_id', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15);

        return view('purchases.index', compact('purchases', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        $brands = Brand::orderBy('name')->get();
        
        $nextUids = [];
        $lastGlobalItem = InwardItemCode::orderBy('id', 'desc')->first();
        $globalNextUid = 'Zig0001';
        if ($lastGlobalItem) {
            $lastUid = $lastGlobalItem->uid;
            if (preg_match('/^(.*?)(\d+)$/', $lastUid, $matches)) {
                $prefix = $matches[1];
                $numberStr = $matches[2];
                $nextNum = (int)$numberStr + 1;
                $padLength = strlen($numberStr);
                $globalNextUid = $prefix . str_pad((string)$nextNum, $padLength, '0', STR_PAD_LEFT);
            } else {
                $globalNextUid = $lastUid . '0001';
            }
        }

        foreach ($products as $product) {
            $lastItem = InwardItemCode::where('product_id', $product->id)->orderBy('id', 'desc')->first();
            if ($lastItem) {
                $lastUid = $lastItem->uid;
                if (preg_match('/^(.*?)(\d+)$/', $lastUid, $matches)) {
                    $prefix = $matches[1];
                    $numberStr = $matches[2];
                    $nextNum = (int)$numberStr + 1;
                    $padLength = strlen($numberStr);
                    $nextUids[$product->id] = $prefix . str_pad((string)$nextNum, $padLength, '0', STR_PAD_LEFT);
                } else {
                    $nextUids[$product->id] = $lastUid . '0001';
                }
            } else {
                $nextUids[$product->id] = $globalNextUid;
            }
        }

        return view('purchases.create', compact('products', 'brands', 'nextUids', 'globalNextUid'));
    }

    /**
     * Get the next UID for the selected brand's subtitle.
     */
    public function getNextUid(Request $request)
    {
        $brandId = $request->query('brand_id');
        $brand = Brand::find($brandId);
        $subtitle = $brand ? $brand->sub : 'Zig';
        
        $nextUid = $this->getNextUidForSubtitle($subtitle);
        
        return response()->json(['next_uid' => $nextUid]);
    }

    /**
     * Helper to find the next available UID for a given subtitle.
     */
    protected function getNextUidForSubtitle($subtitle)
    {
        if (empty($subtitle)) {
            $subtitle = 'Zig';
        }
        
        $lastItem = InwardItemCode::where('uid', 'like', $subtitle . '%')
            ->orderByRaw('LENGTH(uid) DESC')
            ->orderBy('uid', 'desc')
            ->first();
            
        if ($lastItem) {
            $lastUid = $lastItem->uid;
            if (preg_match('/^(.*?)(\d+)$/', $lastUid, $matches)) {
                $prefix = $matches[1];
                $numberStr = $matches[2];
                $nextNum = (int)$numberStr + 1;
                $padLength = strlen($numberStr);
                return $prefix . str_pad((string)$nextNum, $padLength, '0', STR_PAD_LEFT);
            } else {
                return $lastUid . '0001';
            }
        }
        
        return $subtitle . '0001';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'brand_id' => 'required|exists:brands,id',
            'date' => 'required|date',
            'vendor_id' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_uid' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $validated['amount'] = $validated['quantity'] * $validated['price'];
        $validated['brand_id'] = $request->input('brand_id');
        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $startUid = $request->input('start_uid');
        $quantity = (int)$validated['quantity'];
        $status = $request->input('status', 'Good Inventory');
        $validated['status'] = $status;
        $validated['mark'] = ($status === 'Damaged' || $status === 'Returned') ? $status : null;

        if ($status === 'Damaged') {
            // Damaged products: store purchase, do not generate codes, do not create inward codes
            DB::transaction(function () use ($validated) {
                Purchase::create($validated);
            });

            return redirect()->route('purchases.index')
                ->with('success', 'Purchase record logged for damaged inventory successfully. No inward codes were generated.');
        }

        // For Good Inventory or Returned:
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

        // Validate all generated UIDs are unique in the inward_item_codes table
        $duplicates = InwardItemCode::whereIn('uid', $uids)->pluck('uid')->toArray();
        if (!empty($duplicates)) {
            return back()->withErrors([
                'start_uid' => 'The following generated serial codes already exist in the database: ' . implode(', ', $duplicates)
            ])->withInput();
        }

        // Map status and mark for InwardItemCode
        $inwardStatus = $status;
        $inwardMark = null;

        if ($status === 'Returned') {
            $inwardStatus = 'RTG';
            $inwardMark = 'Returned';
        }

        // Create purchase and corresponding inward records inside a transaction
        DB::transaction(function () use ($validated, $uids, $inwardStatus, $inwardMark) {
            Purchase::create($validated);

            $updatedBy = Auth::user()->name ?? 'System';
            foreach ($uids as $uid) {
                InwardItemCode::create([
                    'product_id' => $validated['product_id'],
                    'uid' => $uid,
                    'quantity' => 1,
                    'status' => $inwardStatus,
                    'mark' => $inwardMark,
                    'updated_by' => $updatedBy,
                ]);
            }
        });

        $product = Product::find($validated['product_id']);
        $productName = $product ? $product->product_name : 'Product';

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase record logged and corresponding ' . $quantity . ' inward serial codes successfully created.')
            ->with('new_purchase_uids', $uids)
            ->with('new_purchase_product_name', $productName);
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase)
    {
        return view('purchases.show', compact('purchase'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        $products = Product::all();
        $brands = Brand::orderBy('name')->get();
        return view('purchases.edit', compact('purchase', 'products', 'brands'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'brand_id' => 'required|exists:brands,id',
            'date' => 'required|date',
            'vendor_id' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['amount'] = $validated['quantity'] * $validated['price'];
        $validated['brand_id'] = $request->input('brand_id');
        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $purchase->update($validated);

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();

        return redirect()->route('purchases.index')
            ->with('success', 'Purchase record deleted successfully.');
    }
}
