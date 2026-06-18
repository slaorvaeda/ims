<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $sales = Sale::with('product')
            ->when($search, function ($query, $search) {
                $query->where('portal_id', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->paginate(15);

        return view('sales.index', compact('sales', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'portal_id' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'order_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
        ]);

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        Sale::create($validated);

        return redirect()->route('sales.index')
            ->with('success', 'Sale order recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        return view('sales.show', compact('sale'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'portal_id' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
            'order_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
        ]);

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $sale->update($validated);

        return redirect()->route('sales.index')
            ->with('success', 'Sale order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale order deleted successfully.');
    }
}
