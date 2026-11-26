<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller implements HasMiddleware
{
    protected GeminiService $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('permission:bills.view|bills.create|bills.delete|purchases.view|sales.view', only: ['index', 'show', 'download']),
            new Middleware('permission:bills.create|purchases.create|sales.create', only: ['create', 'store', 'scanAi', 'edit', 'update']),
            new Middleware('permission:bills.delete|purchases.create|sales.create', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of bills with type filter (sales or purchases).
     */
    public function index(Request $request)
    {
        $type = $request->query('type'); // 'sale', 'purchase', or null
        $search = $request->query('search');
        $status = $request->query('status');
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $buyer = $request->query('buyer');
        $seller = $request->query('seller');

        $query = Bill::query();

        if (!empty($type) && in_array($type, ['sale', 'purchase'])) {
            $query->where('bill_type', $type);
        }

        if (!empty($buyer)) {
            $query->where('buyer_name', $buyer);
        }

        if (!empty($seller)) {
            $query->where(function ($q) use ($seller) {
                $q->where('seller_name', $seller)
                  ->orWhere('party_name', $seller);
            });
        }

        if (!empty($search)) {
            $lowerSearch = strtolower($search);
            $query->where(function ($q) use ($search, $lowerSearch) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('party_name', 'like', "%{$search}%")
                  ->orWhere('party_gstin', 'like', "%{$search}%")
                  ->orWhere('seller_name', 'like', "%{$search}%")
                  ->orWhere('seller_gstin', 'like', "%{$search}%")
                  ->orWhere('buyer_name', 'like', "%{$search}%")
                  ->orWhere('buyer_gstin', 'like', "%{$search}%")
                  ->orWhere('payment_mode', 'like', "%{$search}%")
                  ->orWhere('file_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%")
                  ->orWhereRaw('LOWER(CAST(line_items AS CHAR)) LIKE ?', ["%{$lowerSearch}%"]);
            });
        }

        if (!empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($startDate)) {
            $query->whereDate('invoice_date', '>=', $startDate);
        }

        if (!empty($endDate)) {
            $query->whereDate('invoice_date', '<=', $endDate);
        }

        // Multi-Step Company Drill-down Explorer Aggregation
        // For Sales: Step 1 = Sales FROM (seller_name), Step 2 = Sold TO / Customer (buyer_name)
        // For Purchases / Default: Step 1 = Purchased TO (buyer_name), Step 2 = Supplier / Vendor (seller_name)
        $isSaleExplorer = ($type === 'sale');

        if ($isSaleExplorer) {
            // Level 1: Selling Entities (Sales FROM)
            $primaryCompanies = Bill::where('bill_type', 'sale')
                ->whereNotNull('seller_name')
                ->where('seller_name', '!=', '')
                ->select(
                    'seller_name',
                    'seller_name as name',
                    'seller_gstin',
                    'seller_gstin as gstin',
                    \Illuminate\Support\Facades\DB::raw('count(*) as count'),
                    \Illuminate\Support\Facades\DB::raw('sum(total_amount) as total')
                )
                ->groupBy('seller_name', 'seller_gstin')
                ->orderByDesc('total')
                ->get();

            // Level 2: Customers (Sold TO) under selected seller
            $secondaryCompanies = collect();
            if (!empty($seller)) {
                $secondaryCompanies = Bill::where('bill_type', 'sale')
                    ->where(function ($q) use ($seller) {
                        $q->where('seller_name', $seller)
                          ->orWhere('party_name', $seller);
                    })
                    ->whereNotNull('buyer_name')
                    ->where('buyer_name', '!=', '')
                    ->select(
                        'buyer_name',
                        'buyer_name as name',
                        'buyer_gstin',
                        'buyer_gstin as gstin',
                        \Illuminate\Support\Facades\DB::raw('count(*) as count'),
                        \Illuminate\Support\Facades\DB::raw('sum(total_amount) as total')
                    )
                    ->groupBy('buyer_name', 'buyer_gstin')
                    ->orderByDesc('total')
                    ->get();
            }
        } else {
            // Level 1: Buyer Companies (Purchased TO)
            $primaryCompanies = Bill::where('bill_type', 'purchase')
                ->whereNotNull('buyer_name')
                ->where('buyer_name', '!=', '')
                ->select(
                    'buyer_name',
                    'buyer_name as name',
                    'buyer_gstin',
                    'buyer_gstin as gstin',
                    \Illuminate\Support\Facades\DB::raw('count(*) as count'),
                    \Illuminate\Support\Facades\DB::raw('sum(total_amount) as total')
                )
                ->groupBy('buyer_name', 'buyer_gstin')
                ->orderByDesc('total')
                ->get();

            // Level 2: Suppliers / Vendors under selected buyer
            $secondaryCompanies = collect();
            if (!empty($buyer)) {
                $secondaryCompanies = Bill::where('bill_type', 'purchase')
                    ->where('buyer_name', $buyer)
                    ->whereNotNull('seller_name')
                    ->where('seller_name', '!=', '')
                    ->select(
                        'seller_name',
                        'seller_name as name',
                        'seller_gstin',
                        'seller_gstin as gstin',
                        \Illuminate\Support\Facades\DB::raw('count(*) as count'),
                        \Illuminate\Support\Facades\DB::raw('sum(total_amount) as total')
                    )
                    ->groupBy('seller_name', 'seller_gstin')
                    ->orderByDesc('total')
                    ->get();
            }
        }

        // Maintain aliases for backward compatibility
        $buyerCompanies = $isSaleExplorer ? $secondaryCompanies : $primaryCompanies;
        $sellerCompanies = $isSaleExplorer ? $primaryCompanies : $secondaryCompanies;

        // Summary Statistics (calculated across all bills regardless of current pagination)
        $totalCount = Bill::count();
        $purchaseCount = Bill::where('bill_type', 'purchase')->count();
        $purchaseTotal = (float) Bill::where('bill_type', 'purchase')->sum('total_amount');
        $salesCount = Bill::where('bill_type', 'sale')->count();
        $salesTotal = (float) Bill::where('bill_type', 'sale')->sum('total_amount');

        // Current filtered totals
        $filteredCount = (clone $query)->count();
        $filteredTotal = (float) (clone $query)->sum('total_amount');

        $bills = $query->orderBy('invoice_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('bills.index', compact(
            'bills',
            'type',
            'search',
            'status',
            'startDate',
            'endDate',
            'buyer',
            'seller',
            'isSaleExplorer',
            'primaryCompanies',
            'secondaryCompanies',
            'buyerCompanies',
            'sellerCompanies',
            'filteredCount',
            'filteredTotal',
            'totalCount',
            'purchaseCount',
            'purchaseTotal',
            'salesCount',
            'salesTotal'
        ));
    }

    /**
     * Show the form for creating a new bill with scanned document upload.
     */
    public function create(Request $request)
    {
        $defaultType = $request->query('type', 'purchase');
        if (!in_array($defaultType, ['purchase', 'sale'])) {
            $defaultType = 'purchase';
        }

        return view('bills.create', compact('defaultType'));
    }

    /**
     * Scan uploaded invoice PDF or image via Gemini AI and return extracted JSON fields.
     */
    public function scanAi(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,webp|max:25600', // max 25MB
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();
        $mime = $file->getMimeType();
        $preferredType = $request->input('preferred_type');

        try {
            $extracted = $this->gemini->scanInvoiceDocument($path, $mime, $preferredType);

            // Infer from original file name if invoice number was not detected
            if (empty($extracted['invoice_number'])) {
                $rawBase = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // Clean potential filename to readable invoice candidate
                $extracted['invoice_number'] = preg_replace('/[^A-Za-z0-9\-_]/', '', $rawBase);
            }

            return response()->json([
                'success' => $extracted['success'] ?? false,
                'data' => $extracted,
                'message' => $extracted['message'] ?? 'Document scanned successfully.',
            ]);
        } catch (\Exception $e) {
            Log::error('AI bill scan controller error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze document with AI: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created bill and its scanned document in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bill_type' => 'required|in:sale,purchase',
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'party_name' => 'required|string|max:255',
            'party_gstin' => 'nullable|string|max:50',
            'party_address' => 'nullable|string',
            'party_phone' => 'nullable|string|max:50',
            'seller_name' => 'nullable|string|max:255',
            'seller_gstin' => 'nullable|string|max:50',
            'seller_address' => 'nullable|string',
            'seller_phone' => 'nullable|string|max:50',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_gstin' => 'nullable|string|max:50',
            'buyer_address' => 'nullable|string',
            'buyer_phone' => 'nullable|string|max:50',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'payment_mode' => 'nullable|string|max:100',
            'file' => 'required|file|mimes:pdf,png,jpg,jpeg,webp|max:25600',
            'notes' => 'nullable|string',
            'line_items' => 'nullable|string', // JSON string from frontend
        ]);

        $file = $request->file('file');
        $billType = $validated['bill_type'];
        $folder = 'bills/' . $billType . '/' . date('Y/m');

        // Store file in public storage disk
        $storedPath = $file->store($folder, 'public');

        // Parse line items if provided as JSON string
        $lineItems = null;
        if (!empty($request->input('line_items'))) {
            $decoded = json_decode($request->input('line_items'), true);
            if (is_array($decoded)) {
                $lineItems = $decoded;
            }
        }

        // Store raw extracted data if sent
        $rawExtracted = null;
        if (!empty($request->input('raw_extracted_data'))) {
            $decodedRaw = json_decode($request->input('raw_extracted_data'), true);
            if (is_array($decodedRaw)) {
                $rawExtracted = $decodedRaw;
            }
        }

        $rawSeller = $rawExtracted['seller'] ?? [];
        $rawBuyer = $rawExtracted['buyer'] ?? [];

        // Determine FROM (Seller) details
        $sellerName = $validated['seller_name'] ?? ($rawSeller['name'] ?? null);
        $sellerGstin = $validated['seller_gstin'] ?? ($rawSeller['gstin'] ?? null);
        $sellerAddress = $validated['seller_address'] ?? ($rawSeller['address'] ?? null);
        $sellerPhone = $validated['seller_phone'] ?? ($rawSeller['phone'] ?? null);

        // Determine TO (Buyer) details
        $buyerName = $validated['buyer_name'] ?? ($rawBuyer['name'] ?? null);
        $buyerGstin = $validated['buyer_gstin'] ?? ($rawBuyer['gstin'] ?? null);
        $buyerAddress = $validated['buyer_address'] ?? ($rawBuyer['address'] ?? null);
        $buyerPhone = $validated['buyer_phone'] ?? ($rawBuyer['phone'] ?? null);

        // Fallbacks if not set
        if (empty($sellerName)) {
            $sellerName = ($billType === 'purchase') ? trim($validated['party_name']) : 'INDIVOLT APPLIANCES & ELECTRICALS';
        }
        if (empty($sellerGstin) && $billType === 'purchase') {
            $sellerGstin = $validated['party_gstin'] ?? null;
        } elseif (empty($sellerGstin) && $billType === 'sale') {
            $sellerGstin = '06AALFI9222R1ZO';
        }

        if (empty($buyerName)) {
            $buyerName = ($billType === 'sale') ? trim($validated['party_name']) : 'INDIVOLT APPLIANCES & ELECTRICALS';
        }
        if (empty($buyerGstin) && $billType === 'sale') {
            $buyerGstin = $validated['party_gstin'] ?? null;
        } elseif (empty($buyerGstin) && $billType === 'purchase') {
            $buyerGstin = '06AALFI9222R1ZO';
        }

        $bill = Bill::create([
            'bill_type' => $billType,
            'invoice_number' => trim($validated['invoice_number']),
            'invoice_date' => $validated['invoice_date'],
            'party_name' => trim($validated['party_name']),
            'party_gstin' => !empty($validated['party_gstin']) ? trim($validated['party_gstin']) : null,
            'party_address' => !empty($validated['party_address']) ? trim($validated['party_address']) : null,
            'party_phone' => !empty($validated['party_phone']) ? trim($validated['party_phone']) : null,
            'seller_name' => $sellerName,
            'seller_gstin' => $sellerGstin,
            'seller_address' => $sellerAddress,
            'seller_phone' => $sellerPhone,
            'buyer_name' => $buyerName,
            'buyer_gstin' => $buyerGstin,
            'buyer_address' => $buyerAddress,
            'buyer_phone' => $buyerPhone,
            'subtotal' => $validated['subtotal'] ?? 0.00,
            'tax_amount' => $validated['tax_amount'] ?? 0.00,
            'total_amount' => $validated['total_amount'],
            'status' => $validated['status'],
            'payment_mode' => $validated['payment_mode'] ?? 'Bank Transfer',
            'file_path' => $storedPath,
            'file_name' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'line_items' => $lineItems,
            'raw_extracted_data' => $rawExtracted,
            'notes' => $validated['notes'] ?? null,
            'uploaded_by' => Auth::user()->name ?? 'System',
        ]);

        return redirect()->route('bills.show', $bill)
            ->with('success', "Invoice #{$bill->invoice_number} saved and document scanned successfully!");
    }

    /**
     * Display the specified bill with signature SPLIT-VIEW (Left: PDF, Right: Details).
     */
    public function show(Bill $bill)
    {
        // Navigation: Previous and Next bills for seamless browsing
        $prevBill = Bill::where('bill_type', $bill->bill_type)
            ->where('id', '<', $bill->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextBill = Bill::where('bill_type', $bill->bill_type)
            ->where('id', '>', $bill->id)
            ->orderBy('id', 'asc')
            ->first();

        return view('bills.show', compact('bill', 'prevBill', 'nextBill'));
    }

    /**
     * Show the form for editing the bill.
     */
    public function edit(Bill $bill)
    {
        return view('bills.edit', compact('bill'));
    }

    /**
     * Update the specified bill in storage.
     */
    public function update(Request $request, Bill $bill)
    {
        $validated = $request->validate([
            'bill_type' => 'required|in:sale,purchase',
            'invoice_number' => 'required|string|max:255',
            'invoice_date' => 'required|date',
            'party_name' => 'required|string|max:255',
            'party_gstin' => 'nullable|string|max:50',
            'party_address' => 'nullable|string',
            'party_phone' => 'nullable|string|max:50',
            'seller_name' => 'nullable|string|max:255',
            'seller_gstin' => 'nullable|string|max:50',
            'seller_address' => 'nullable|string',
            'seller_phone' => 'nullable|string|max:50',
            'buyer_name' => 'nullable|string|max:255',
            'buyer_gstin' => 'nullable|string|max:50',
            'buyer_address' => 'nullable|string',
            'buyer_phone' => 'nullable|string|max:50',
            'subtotal' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'status' => 'required|string|max:50',
            'payment_mode' => 'nullable|string|max:100',
            'file' => 'nullable|file|mimes:pdf,png,jpg,jpeg,webp|max:25600',
            'notes' => 'nullable|string',
            'line_items' => 'nullable|string',
        ]);

        // If new file uploaded, replace old file
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $folder = 'bills/' . $validated['bill_type'] . '/' . date('Y/m');
            
            // Delete old file if exists
            if (!empty($bill->file_path) && Storage::disk('public')->exists($bill->file_path)) {
                Storage::disk('public')->delete($bill->file_path);
            }

            $storedPath = $file->store($folder, 'public');
            $bill->file_path = $storedPath;
            $bill->file_name = $file->getClientOriginalName();
            $bill->file_size = $file->getSize();
            $bill->mime_type = $file->getMimeType();
        }

        // Parse line items if provided as JSON string
        if ($request->has('line_items')) {
            $decoded = json_decode($request->input('line_items'), true);
            if (is_array($decoded)) {
                $bill->line_items = $decoded;
            }
        }

        $bill->bill_type = $validated['bill_type'];
        $bill->invoice_number = trim($validated['invoice_number']);
        $bill->invoice_date = $validated['invoice_date'];
        $bill->party_name = trim($validated['party_name']);
        $bill->party_gstin = !empty($validated['party_gstin']) ? trim($validated['party_gstin']) : null;
        $bill->party_address = !empty($validated['party_address']) ? trim($validated['party_address']) : null;
        $bill->party_phone = !empty($validated['party_phone']) ? trim($validated['party_phone']) : null;

        // Update seller & buyer fields
        if ($request->has('seller_name')) {
            $bill->seller_name = $request->input('seller_name') ?: null;
        }
        if ($request->has('seller_gstin')) {
            $bill->seller_gstin = $request->input('seller_gstin') ?: null;
        }
        if ($request->has('seller_address')) {
            $bill->seller_address = $request->input('seller_address') ?: null;
        }
        if ($request->has('seller_phone')) {
            $bill->seller_phone = $request->input('seller_phone') ?: null;
        }

        if ($request->has('buyer_name')) {
            $bill->buyer_name = $request->input('buyer_name') ?: null;
        }
        if ($request->has('buyer_gstin')) {
            $bill->buyer_gstin = $request->input('buyer_gstin') ?: null;
        }
        if ($request->has('buyer_address')) {
            $bill->buyer_address = $request->input('buyer_address') ?: null;
        }
        if ($request->has('buyer_phone')) {
            $bill->buyer_phone = $request->input('buyer_phone') ?: null;
        }

        $bill->subtotal = $validated['subtotal'] ?? 0.00;
        $bill->tax_amount = $validated['tax_amount'] ?? 0.00;
        $bill->total_amount = $validated['total_amount'];
        $bill->status = $validated['status'];
        $bill->payment_mode = $validated['payment_mode'] ?? 'Bank Transfer';
        $bill->notes = $validated['notes'] ?? null;
        $bill->updated_by = Auth::user()->name ?? 'System';
        $bill->save();

        return redirect()->route('bills.show', $bill)
            ->with('success', "Invoice #{$bill->invoice_number} updated successfully!");
    }

    /**
     * Remove the specified bill and its stored document.
     */
    public function destroy(Bill $bill)
    {
        $invoiceNumber = $bill->invoice_number;
        $type = $bill->bill_type;

        if (!empty($bill->file_path) && Storage::disk('public')->exists($bill->file_path)) {
            Storage::disk('public')->delete($bill->file_path);
        }

        $bill->delete();

        return redirect()->route('bills.index', ['type' => $type])
            ->with('success', "Invoice #{$invoiceNumber} and document deleted successfully.");
    }

    /**
     * Download the original scanned document.
     */
    public function download(Bill $bill)
    {
        if (empty($bill->file_path) || !Storage::disk('public')->exists($bill->file_path)) {
            return redirect()->back()->with('error', 'The document file could not be found.');
        }

        return Storage::disk('public')->download($bill->file_path, $bill->file_name ?? "Invoice-{$bill->invoice_number}.pdf");
    }
}
