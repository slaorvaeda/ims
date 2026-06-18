<?php

namespace App\Http\Controllers;

use App\Models\InwardItemCode;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InwardItemCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $inwardItemCodes = InwardItemCode::with('product')
            ->when($search, function ($query, $search) {
                $query->where('uid', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%");
                    });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(15);

        return view('inward_item_codes.index', compact('inwardItemCodes', 'search', 'status'));
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
        ]);

        $uid = trim($request->input('scan_uid'));

        // Find available InwardItemCode
        $inwardItem = InwardItemCode::where('uid', $uid)->first();

        if (!$inwardItem) {
            return back()->with('error', "Serial Code '{$uid}' does not exist in Inward Stock.");
        }

        if ($inwardItem->status === 'Sold') {
            return back()->with('error', "Serial Code '{$uid}' has already been sold/dispatched.");
        }

        // Run in transaction to update status in Inward and insert in Dispatch
        DB::transaction(function () use ($inwardItem, $uid) {
            // Update Inward status
            $inwardItem->update([
                'status' => 'Sold',
            ]);

            // Create Dispatch record
            \App\Models\DispatchItemCode::create([
                'product_id' => $inwardItem->product_id,
                'uid' => $uid,
                'quantity' => -1,
                'status' => 'Sold',
                'updated_by' => Auth::user()->name ?? 'System',
            ]);
        });

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
