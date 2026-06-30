<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\PortalVendor;
use Illuminate\Http\Request;

class OperatorController extends Controller
{
    public function index()
    {
        $brands = Brand::orderBy('name')->get();
        $portalVendors = PortalVendor::orderBy('name')->get();
        return view('operators.index', compact('brands', 'portalVendors'));
    }

    public function storeBrand(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:brands,name|max:255',
            'sub' => 'nullable|string|max:255',
        ]);

        Brand::create([
            'name' => $request->name,
            'sub' => $request->sub,
        ]);

        return redirect()->route('operators.index')->with('success', 'Brand created successfully.');
    }

    public function updateBrand(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'sub' => 'nullable|string|max:255',
        ]);

        $brand->update([
            'name' => $request->name,
            'sub' => $request->sub,
        ]);

        return redirect()->route('operators.index')->with('success', 'Brand updated successfully.');
    }

    public function destroyBrand(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('operators.index')->with('success', 'Brand deleted successfully.');
    }

    public function storePortalVendor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:portal_vendors,name|max:255',
            'type' => 'required|string|in:Portal,Vendor',
        ]);

        PortalVendor::create([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('operators.index')->with('success', 'Portal/Vendor created successfully.');
    }

    public function updatePortalVendor(Request $request, PortalVendor $portalVendor)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:portal_vendors,name,' . $portalVendor->id,
            'type' => 'required|string|in:Portal,Vendor',
        ]);

        $portalVendor->update([
            'name' => $request->name,
            'type' => $request->type,
        ]);

        return redirect()->route('operators.index')->with('success', 'Portal/Vendor updated successfully.');
    }

    public function destroyPortalVendor(PortalVendor $portalVendor)
    {
        $portalVendor->delete();
        return redirect()->route('operators.index')->with('success', 'Portal/Vendor deleted successfully.');
    }
}
