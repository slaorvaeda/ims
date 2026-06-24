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
        ]);

        $uid = trim($request->input('scan_uid'));

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

        // Run in transaction to update status in Inward and insert in Dispatch
        try {
            DB::transaction(function () use ($inwardItem, $uid) {
                // Update Inward status
                $inwardItem->update([
                    'status' => 'Sold',
                ]);

                // Create Dispatch record
                DispatchItemCode::create([
                    'product_id' => $inwardItem->product_id,
                    'uid' => $uid,
                    'quantity' => -1,
                    'status' => 'Sold',
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

            return response()->json([
                'success' => true,
                'message' => "Successfully dispatched Serial Code '{$uid}' as Sold.",
                'data' => [
                    'uid' => $uid,
                    'product_id' => $inwardItem->product_id,
                    'product_name' => $inwardItem->product->product_name ?? 'N/A',
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
}
