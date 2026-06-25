<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\InwardItemCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $purchases = Purchase::with('product')
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

        return view('purchases.create', compact('products', 'nextUids', 'globalNextUid'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'date' => 'required|date',
            'vendor_id' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'start_uid' => 'required|string|max:255',
            'status' => 'required|string|max:255',
        ]);

        $validated['amount'] = $validated['quantity'] * $validated['price'];
        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $startUid = $request->input('start_uid');
        $quantity = (int)$validated['quantity'];
        $status = $request->input('status', 'Good Inventory');

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

        // Create purchase and corresponding inward records inside a transaction
        DB::transaction(function () use ($validated, $uids, $status) {
            Purchase::create($validated);

            $updatedBy = Auth::user()->name ?? 'System';
            foreach ($uids as $uid) {
                InwardItemCode::create([
                    'product_id' => $validated['product_id'],
                    'uid' => $uid,
                    'quantity' => 1,
                    'status' => $status,
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
        return view('purchases.edit', compact('purchase', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'date' => 'required|date',
            'vendor_id' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['amount'] = $validated['quantity'] * $validated['price'];
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
