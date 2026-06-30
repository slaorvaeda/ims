<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CsvExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Export products to Excel/CSV.
     */
    public function export(Request $request)
    {
        $search = $request->input('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('product_id', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->latest()
            ->get();

        $headers = ['Product ID', 'Product Name', 'SKU', 'FSN', 'ASIN', 'Updated By', 'Created At'];
        $data = [];

        foreach ($products as $product) {
            $data[] = [
                $product->product_id,
                $product->product_name,
                $product->sku,
                $product->fsn,
                $product->asin,
                $product->updated_by ?? 'System',
                $product->created_at ? $product->created_at->toDateTimeString() : '',
            ];
        }

        return CsvExcelService::export($headers, $data, 'products_export_' . now()->format('Ymd_His') . '.csv');
    }

    /**
     * Import products from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:4096',
        ]);

        try {
            $records = CsvExcelService::import(
                $request->file('file')->getRealPath(),
                ['Product ID', 'Product Name', 'SKU']
            );

            $imported = 0;
            $updated = 0;
            $user = Auth::user()->name ?? 'System';

            foreach ($records as $record) {
                $productId = $record['Product ID'] ?? '';
                $productName = $record['Product Name'] ?? '';
                $sku = $record['SKU'] ?? '';

                if (empty($productId) || empty($sku)) {
                    continue;
                }

                // Upsert logic based on SKU or Product ID
                $product = Product::where('sku', $sku)
                    ->orWhere('product_id', $productId)
                    ->first();

                if ($product) {
                    $product->update([
                        'product_id' => $productId,
                        'product_name' => !empty($productName) ? $productName : $product->product_name,
                        'sku' => $sku,
                        'fsn' => $record['FSN'] ?? $product->fsn,
                        'asin' => $record['ASIN'] ?? $product->asin,
                        'updated_by' => $user,
                    ]);
                    $updated++;
                } else {
                    if (empty($productName)) {
                        continue; // Product Name is required for creation
                    }
                    Product::create([
                        'product_id' => $productId,
                        'product_name' => $productName,
                        'sku' => $sku,
                        'fsn' => $record['FSN'] ?? null,
                        'asin' => $record['ASIN'] ?? null,
                        'updated_by' => $user,
                    ]);
                    $imported++;
                }
            }

            return redirect()->route('products.index')
                ->with('success', "Products import completed. Imported: {$imported}, Updated: {$updated}.");

        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $products = Product::query()
            ->when($search, function ($query, $search) {
                $query->where('product_id', 'like', "%{$search}%")
                    ->orWhere('product_name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15);

        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string|unique:products,product_id|max:255',
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:255',
            'fsn' => 'nullable|string|max:255',
            'asin' => 'nullable|string|max:255',
        ]);

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'product_id' => 'required|string|max:255|unique:products,product_id,' . $product->id,
            'product_name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'fsn' => 'nullable|string|max:255',
            'asin' => 'nullable|string|max:255',
        ]);

        $validated['updated_by'] = Auth::user()->name ?? 'System';

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
