<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Services\CsvExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Export sales to Excel/CSV.
     */
    public function export(Request $request)
    {
        $search = $request->input('search');

        $sales = Sale::with('product')
            ->when($search, function ($query, $search) {
                $query->where('portal_id', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($q) use ($search) {
                        $q->where('product_name', 'like', "%{$search}%")
                            ->orWhere('product_id', 'like', "%{$search}%")
                            ->orWhere('sku', 'like', "%{$search}%");
                    });
            })
            ->latest()
            ->get();

        $headers = ['Order Date', 'Portal ID', 'Product ID', 'SKU', 'Product Name', 'Quantity', 'Updated By', 'Created At'];
        $data = [];

        foreach ($sales as $sale) {
            $data[] = [
                $sale->order_date,
                $sale->portal_id,
                $sale->product->product_id ?? '',
                $sale->product->sku ?? '',
                $sale->product->product_name ?? '',
                $sale->quantity,
                $sale->updated_by ?? 'System',
                $sale->created_at ? $sale->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'sales_export_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Import sales from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:4096',
        ]);

        try {
            $records = CsvExcelService::import(
                $request->file('file')->getRealPath(),
                ['Order Date', 'Portal ID', 'Quantity']
            );

            $imported = 0;
            $errors = [];
            $user = Auth::user()->name ?? 'System';

            DB::beginTransaction();

            foreach ($records as $index => $record) {
                $rowNumber = $index + 2;

                $orderDate = $record['Order Date'] ?? '';
                $portalId = $record['Portal ID'] ?? '';
                $quantity = intval($record['Quantity'] ?? 0);
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

                if (empty($orderDate)) {
                    $errors[] = "Row {$rowNumber}: Order Date is required.";
                    continue;
                }

                if ($quantity <= 0) {
                    $errors[] = "Row {$rowNumber}: Quantity must be greater than 0.";
                    continue;
                }

                Sale::create([
                    'portal_id' => $portalId,
                    'product_id' => $product->id,
                    'order_date' => $orderDate,
                    'quantity' => $quantity,
                    'updated_by' => $user,
                ]);

                $imported++;
            }

            if (!empty($errors)) {
                DB::rollBack();
                return redirect()->route('sales.index')
                    ->with('error', 'Import failed due to validation errors. First few: ' . implode(' | ', array_slice($errors, 0, 5)));
            }

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', "Sales orders import completed successfully. Imported {$imported} records.");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('sales.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
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
