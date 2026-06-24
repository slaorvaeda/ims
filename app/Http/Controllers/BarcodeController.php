<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    /**
     * Display the barcode generator index page.
     */
    public function index()
    {
        $inwardUids = \App\Models\InwardItemCode::with('product')->get();
        $dispatchUids = \App\Models\DispatchItemCode::with('product')->get();
        
        $uidSkuMap = [];
        foreach ($inwardUids as $item) {
            if ($item->product) {
                $uidSkuMap[strtolower($item->uid)] = $item->product->sku ?? $item->product->product_name ?? '';
            }
        }
        foreach ($dispatchUids as $item) {
            if ($item->product) {
                $uidSkuMap[strtolower($item->uid)] = $item->product->sku ?? $item->product->product_name ?? '';
            }
        }
        
        return view('barcodes.index', compact('uidSkuMap'));
    }
}
