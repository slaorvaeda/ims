<?php

namespace App\Http\Controllers;

use App\Models\DispatchItemCode;
use App\Models\InwardItemCode;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispatchItemCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $dispatchItemCodes = DispatchItemCode::with('product')
            ->when($search, function ($query, $search) {
                $query->where('uid', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15);

        return view('dispatch_item_codes.index', compact('dispatchItemCodes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        
        // Fetch inward UIDs that have not been dispatched yet
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

        DispatchItemCode::create($validated);

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
        
        // Fetch inward UIDs that have not been dispatched yet (including the current one being edited)
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

        $dispatchItemCode->update($validated);

        return redirect()->route('dispatch-item-codes.index')
            ->with('success', 'Dispatch record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DispatchItemCode $dispatchItemCode)
    {
        $dispatchItemCode->delete();

        return redirect()->route('dispatch-item-codes.index')
            ->with('success', 'Dispatch record deleted successfully.');
    }
}
