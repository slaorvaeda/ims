<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\PortalVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores and configuration form options.
     */
    public function index()
    {
        $stores = Store::with('portalVendor')->orderBy('store_name')->get();
        // Load only vendors of type "Portal"
        $portals = PortalVendor::where('type', 'Portal')->orderBy('name')->get();

        return view('stores.index', compact('stores', 'portals'));
    }

    /**
     * Store a newly created store configuration.
     */
    public function store(Request $request)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'portal_vendor_id' => 'required|exists:portal_vendors,id',
            'status' => 'required|in:active,inactive',
            'enabled_apis' => 'nullable|array',
        ]);

        $portal = PortalVendor::findOrFail($request->input('portal_vendor_id'));
        $portalName = strtoupper($portal->name);

        $credentials = [];
        if (str_contains($portalName, 'AMAZON')) {
            $request->validate([
                'amazon_client_id' => 'required|string',
                'amazon_client_secret' => 'required|string',
                'amazon_refresh_token' => 'required|string',
                'amazon_region' => 'required|string|in:na,eu,fe',
            ]);
            $credentials = [
                'client_id' => $request->input('amazon_client_id'),
                'client_secret' => $request->input('amazon_client_secret'),
                'refresh_token' => $request->input('amazon_refresh_token'),
                'region' => $request->input('amazon_region'),
            ];
        } elseif (str_contains($portalName, 'FLIPKART')) {
            $request->validate([
                'flipkart_app_id' => 'required|string',
                'flipkart_app_secret' => 'required|string',
                'flipkart_username' => 'required|string',
                'flipkart_password' => 'required|string',
            ]);
            $credentials = [
                'app_id' => $request->input('flipkart_app_id'),
                'app_secret' => $request->input('flipkart_app_secret'),
                'username' => $request->input('flipkart_username'),
                'password' => $request->input('flipkart_password'),
            ];
        } else {
            // Generic/Custom Portal API keys
            $credentials = $request->input('generic_credentials', []);
        }

        Store::create([
            'store_name' => $request->input('store_name'),
            'portal_vendor_id' => $request->input('portal_vendor_id'),
            'status' => $request->input('status'),
            'credentials' => $credentials,
            'enabled_apis' => $request->input('enabled_apis', []),
        ]);

        return redirect()->route('stores.index')
            ->with('success', 'Store configuration created successfully.');
    }

    /**
     * Update the specified store configuration.
     */
    public function update(Request $request, Store $store)
    {
        $request->validate([
            'store_name' => 'required|string|max:255',
            'portal_vendor_id' => 'required|exists:portal_vendors,id',
            'status' => 'required|in:active,inactive',
            'enabled_apis' => 'nullable|array',
        ]);

        $portal = PortalVendor::findOrFail($request->input('portal_vendor_id'));
        $portalName = strtoupper($portal->name);

        $credentials = [];
        if (str_contains($portalName, 'AMAZON')) {
            $request->validate([
                'amazon_client_id' => 'required|string',
                'amazon_client_secret' => 'required|string',
                'amazon_refresh_token' => 'required|string',
                'amazon_region' => 'required|string|in:na,eu,fe',
            ]);
            $credentials = [
                'client_id' => $request->input('amazon_client_id'),
                'client_secret' => $request->input('amazon_client_secret'),
                'refresh_token' => $request->input('amazon_refresh_token'),
                'region' => $request->input('amazon_region'),
            ];
        } elseif (str_contains($portalName, 'FLIPKART')) {
            $request->validate([
                'flipkart_app_id' => 'required|string',
                'flipkart_app_secret' => 'required|string',
                'flipkart_username' => 'required|string',
                'flipkart_password' => 'required|string',
            ]);
            $credentials = [
                'app_id' => $request->input('flipkart_app_id'),
                'app_secret' => $request->input('flipkart_app_secret'),
                'username' => $request->input('flipkart_username'),
                'password' => $request->input('flipkart_password'),
            ];
        } else {
            $credentials = $request->input('generic_credentials', []);
        }

        $store->update([
            'store_name' => $request->input('store_name'),
            'portal_vendor_id' => $request->input('portal_vendor_id'),
            'status' => $request->input('status'),
            'credentials' => $credentials,
            'enabled_apis' => $request->input('enabled_apis', []),
        ]);

        return redirect()->route('stores.index')
            ->with('success', 'Store configuration updated successfully.');
    }

    /**
     * Remove the specified store configuration.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store configuration deleted successfully.');
    }
}
