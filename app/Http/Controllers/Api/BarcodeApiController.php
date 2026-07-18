<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InwardItemCode;
use App\Models\DispatchItemCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarcodeApiController extends Controller
{
    /**
     * Scan and automatically dispatch a serial code as Sold.
     */
    public function scanDispatch(Request $request)
    {
        $request->validate([
            'scan_uid' => 'required|string|max:255',
            'portal_vendor_id' => 'required|exists:portal_vendors,id',
        ]);

        $uid = trim($request->input('scan_uid'));
        $portalVendorId = $request->input('portal_vendor_id');

        // Find available InwardItemCode
        $inwardItem = InwardItemCode::with('product')->where('uid', $uid)->first();

        if (!$inwardItem) {
            return response()->json([
                'success' => false,
                'message' => "Serial Code '{$uid}' does not exist in Inward Stock."
            ], 404);
        }

        if ($inwardItem->status === 'Sold') {
            return response()->json([
                'success' => false,
                'message' => "Serial Code '{$uid}' has already been sold/dispatched."
            ], 422);
        }

        // Find if there is an active dispatch for this UID (not cancelled)
        $activeDispatch = DispatchItemCode::where('uid', $uid)
            ->where(function ($q) {
                $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
            })
            ->first();

        if ($activeDispatch) {
            return response()->json([
                'success' => false,
                'message' => "Serial Code '{$uid}' is already dispatched."
            ], 422);
        }

        // Run in transaction to update status in Inward and insert in Dispatch
        try {
            DB::transaction(function () use ($inwardItem, $uid, $portalVendorId) {
                // Update Inward status
                $inwardItem->update([
                    'status' => 'Sold',
                    'mark' => null,
                    'portal_vendor_id' => $portalVendorId,
                ]);

                // Create Dispatch record
                DispatchItemCode::create([
                    'product_id' => $inwardItem->product_id,
                    'uid' => $uid,
                    'quantity' => -1,
                    'status' => 'Sold',
                    'portal_vendor_id' => $portalVendorId,
                    'updated_by' => Auth::user()->name ?? 'System',
                ]);
            });

            // Fire WebSocket event for live update (wrapped in try-catch to prevent broadcast failures from failing the API request)
            $operator = Auth::user()->name ?? 'System';
            try {
                event(new \App\Events\BarcodeDispatched($inwardItem, $uid, $operator));
            } catch (\Exception $broadcastException) {
                // Log the failure, but do not interrupt the successful API response
                \Illuminate\Support\Facades\Log::warning('BarcodeDispatched broadcast failed: ' . $broadcastException->getMessage());
            }

            $portalVendor = \App\Models\PortalVendor::find($portalVendorId);
            $portalName = $portalVendor ? $portalVendor->name : 'N/A';

            return response()->json([
                'success' => true,
                'message' => "Successfully dispatched Serial Code '{$uid}' as Sold.",
                'data' => [
                    'uid' => $uid,
                    'product_id' => $inwardItem->product_id,
                    'product_name' => $inwardItem->product->product_name ?? 'N/A',
                    'portal_name' => $portalName,
                    'updated_by' => $operator,
                    'dispatched_at' => now()->toDateTimeString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the dispatch: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Scan and cancel dispatch of a serial code.
     */
    public function scanCancel(Request $request)
    {
        $request->validate([
            'scan_uid' => 'required|string|max:255',
        ]);

        $uid = trim($request->input('scan_uid'));

        // Find the active Dispatch record
        $dispatchItem = DispatchItemCode::with('product')
            ->where('uid', $uid)
            ->where(function ($q) {
                $q->whereNull('mark')->orWhere('mark', '!=', 'cancelled');
            })
            ->first();

        if (!$dispatchItem) {
            return response()->json([
                'success' => false,
                'message' => "Serial Code '{$uid}' is not currently actively dispatched."
            ], 404);
        }

        try {
            DB::transaction(function () use ($dispatchItem, $uid) {
                // Update dispatch record mark to 'cancelled' and clear portal
                $dispatchItem->update([
                    'mark' => 'cancelled',
                    'portal_vendor_id' => null,
                ]);

                // Find matching InwardItemCode and set back to 'Good Inventory' and clear portal and mark as active (null)
                $inwardItem = InwardItemCode::where('uid', $uid)->first();
                if ($inwardItem) {
                    $inwardItem->update([
                        'status' => 'Good Inventory',
                        'portal_vendor_id' => null,
                        'mark' => null,
                    ]);
                }
            });

            $operator = Auth::user()->name ?? 'System';

            return response()->json([
                'success' => true,
                'message' => "Successfully cancelled dispatch for Serial Code '{$uid}'. It has been returned to Good Inventory.",
                'data' => [
                    'uid' => $uid,
                    'product_id' => $dispatchItem->product_id,
                    'product_name' => $dispatchItem->product->product_name ?? 'N/A',
                    'updated_by' => $operator,
                    'dispatched_at' => now()->toDateTimeString(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the cancellation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Retrieve the list of active sales portal vendors.
     */
    public function getPortalVendors()
    {
        try {
            $vendors = \App\Models\PortalVendor::select('id', 'name', 'type')->get();
            return response()->json([
                'success' => true,
                'data' => $vendors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load portals: ' . $e->getMessage()
            ], 500);
        }
    }
}
