<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected ?string $apiKey;
    protected string $model = 'gemini-2.5-flash';

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Generate response from prompt and history.
     */
    public function generateResponse(string $prompt, string $systemInstruction = '', array $history = []): string
    {
        if (empty($this->apiKey)) {
            return $this->getMockResponse($prompt);
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

            // Structure chat history and context
            $contents = [];
            
            // Format history
            foreach ($history as $msg) {
                // Ignore system messages from history, Gemini API expects role: user or model
                if ($msg['role'] === 'system') {
                    continue;
                }
                
                $contents[] = [
                    'role' => $msg['role'] === 'assistant' ? 'model' : 'user',
                    'parts' => [['text' => $msg['content']]],
                ];
            }

            // Append current prompt
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $prompt]],
            ];

            $payload = [
                'contents' => $contents,
            ];

            if (!empty($systemInstruction)) {
                $payload['systemInstruction'] = [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ];
            }

            // Simple generation parameters
            $payload['generationConfig'] = [
                'maxOutputTokens' => 800,
                'temperature' => 0.2,
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(30)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Extract output text
                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->status() === 429) {
                return "I apologize, but the Gemini API rate limit or quota was exceeded (Resource Exhausted). Please wait a moment and retry your question.";
            }

            return "I apologize, but I encountered an error communicating with the AI service. Please verify your Gemini API key in the `.env` file.";

        } catch (\Exception $e) {
            Log::error('Gemini API exception', ['message' => $e->getMessage()]);
            return "I apologize, but an unexpected error occurred: " . $e->getMessage();
        }
    }

    /**
     * A high-fidelity local mock simulator when no API key is specified.
     */
    protected function getMockResponse(string $prompt): string
    {
        $promptLower = strtolower($prompt);

        if (str_contains($promptLower, 'low stock') || str_contains($promptLower, 'stock count') || str_contains($promptLower, 'available') || str_contains($promptLower, 'reorder')) {
            return "📋 **Low Stock & Availability Alert (Demo Mode)**\n\nBased on your database snapshot:\n- **Kenstar Smart Fan**: 3 units remaining (Reorder threshold is 5).\n- **Zigma Smart Bulb**: 4 units remaining.\n- **Indivolt Inverter**: 0 units left (Out of Stock).\n\n*Configure your `GEMINI_API_KEY` in the `.env` file to chat with live database data!*";
        }

        if (str_contains($promptLower, 'dispatch') || str_contains($promptLower, 'recent scan') || str_contains($promptLower, 'inward') || str_contains($promptLower, 'activity')) {
            return "🔄 **Recent Activity Scan Summary (Demo Mode)**\n\nIn the last 24 hours:\n- **Inward Scans**: 142 units successfully registered.\n- **Dispatch Scans**: 94 units scanned and shipped.\n- **Fulfillment Goal**: 84.2% completed.\n\n*Configure your `GEMINI_API_KEY` in the `.env` file to query active scan logs!*";
        }

        return "👋 **Hello! I am your IMS AI Copilot.**\n\nI can help you analyze your inventory statistics, look up low stock levels, track dispatches, and audit your Excel/CSV spreadsheets.\n\n⚠️ **API Key Required**: Currently running in *Demo Mode*. To unlock real-time database queries and advanced spreadsheet auditing, please add your Google Gemini API key to your `.env` file:\n```env\nGEMINI_API_KEY=your_gemini_api_key_here\n```\n\n**Try asking me**: \n- *\"Which products are low in stock?\"*\n- *\"Show recent inward activity\"*";
    }

    /**
     * Scan and extract structured invoice details from a scanned document (PDF or image).
     */
    public function scanInvoiceDocument(string $filePath, string $mimeType = 'application/pdf', ?string $preferredType = null): array
    {
        $resolvedType = in_array($preferredType, ['purchase', 'sale']) ? $preferredType : 'purchase';

        $defaultResponse = [
            'success' => false,
            'bill_type' => $resolvedType,
            'invoice_number' => '',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => '',
            'party_gstin' => '',
            'party_address' => '',
            'party_phone' => '',
            'seller' => [
                'name' => '',
                'gstin' => '',
                'address' => '',
                'phone' => '',
                'is_our_company' => false,
            ],
            'buyer' => [
                'name' => '',
                'gstin' => '',
                'address' => '',
                'phone' => '',
                'is_our_company' => false,
            ],
            'subtotal' => 0.00,
            'tax_amount' => 0.00,
            'total_amount' => 0.00,
            'status' => 'Paid',
            'payment_mode' => 'Bank Transfer',
            'items' => [],
            'notes' => '',
            'message' => 'Default data structure',
        ];

        if (empty($this->apiKey) || !file_exists($filePath)) {
            $defaultResponse['message'] = 'No Gemini API key configured or file not found. Manual entry enabled.';
            return $defaultResponse;
        }

        try {
            $fileData = file_get_contents($filePath);
            if (empty($fileData)) {
                return $defaultResponse;
            }

            // Normalise mime type
            if (str_contains($mimeType, 'pdf')) {
                $cleanMime = 'application/pdf';
            } elseif (str_contains($mimeType, 'png')) {
                $cleanMime = 'image/png';
            } elseif (str_contains($mimeType, 'webp')) {
                $cleanMime = 'image/webp';
            } else {
                $cleanMime = 'image/jpeg';
            }

            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}";

            $prompt = <<<PROMPT
You are an expert Indian GST Invoice and Accounting Document Parser AI.
CRITICAL CONTEXT ABOUT OUR SYSTEM:
- Our company is: "INDIVOLT" / "INDIVOLT APPLIANCES & ELECTRICALS" (GSTIN: 06AALFI9222R1ZO, Gurugram, Haryana).
- In every Indian Tax Invoice, there are two distinct parties:
  1. SELLER / SUPPLIER: The entity issuing the invoice at the top header (with their GSTIN, address, bank details).
  2. BUYER / RECIPIENT: The "Billed to" or "Consignee / Shipped to" party receiving the goods.

HOW TO DETERMINE "bill_type":
- "purchase" (Incoming Purchase Bill): When an external manufacturer/vendor/supplier (e.g. Aay Pee Industries, Kenstar, raw material or component supplier) bills TO Indivolt. The Seller is the Vendor, and Buyer is Indivolt.
- "sale" (Outgoing Sales Bill): When Indivolt issues a tax invoice TO an external customer, retail client, dealer, or e-commerce platform (e.g. Flipkart, Amazon, JioMart). The Seller is Indivolt, and Buyer is Customer.

Extract all details into valid JSON with this exact schema:
{
  "seller": {
    "name": "string (Supplier / Vendor issuing the bill at top header)",
    "gstin": "string or null",
    "address": "string or null",
    "phone": "string or null"
  },
  "buyer": {
    "name": "string (Recipient / Billed to / Consignee / Customer)",
    "gstin": "string or null",
    "address": "string or null",
    "phone": "string or null"
  },
  "bill_type": "purchase" or "sale",
  "invoice_number": "string",
  "invoice_date": "YYYY-MM-DD",
  "subtotal": 0.00,
  "tax_amount": 0.00,
  "total_amount": 0.00,
  "status": "Paid" or "Pending",
  "payment_mode": "string or null",
  "items": [
    {
      "item_name": "string",
      "hsn": "string or null",
      "quantity": 0.00,
      "rate": 0.00,
      "tax_rate": 0,
      "amount": 0.00
    }
  ],
  "notes": "string or null"
}
Extract accurately. If any field is not visible in the document, use null or 0. Return ONLY valid JSON.
PROMPT;

            $payload = [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [
                            [
                                'inline_data' => [
                                    'mime_type' => $cleanMime,
                                    'data' => base64_encode($fileData)
                                ]
                            ],
                            [
                                'text' => $prompt
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'responseMimeType' => 'application/json',
                    'temperature' => 0.1,
                    'maxOutputTokens' => 4096,
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->timeout(60)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                $text = trim($text);
                if (str_starts_with($text, '```json')) {
                    $text = substr($text, 7);
                } elseif (str_starts_with($text, '```')) {
                    $text = substr($text, 3);
                }
                if (str_ends_with($text, '```')) {
                    $text = substr($text, 0, -3);
                }
                $text = trim($text);

                $parsed = json_decode($text, true);
                if (is_array($parsed)) {
                    // Resolve Vendor vs Customer and assign party fields deterministically
                    $resolved = $this->resolvePartiesAndType($parsed, $preferredType);

                    $resolved['success'] = true;
                    $resolved['message'] = 'Invoice successfully scanned and extracted by AI.';
                    return array_merge($defaultResponse, $resolved);
                }
            }

            Log::warning('Gemini invoice document scan non-200', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            $defaultResponse['message'] = 'AI scan could not parse automatically. You can fill in the details manually.';
            return $defaultResponse;

        } catch (\Exception $e) {
            Log::error('Gemini invoice scan exception', ['message' => $e->getMessage()]);
            $defaultResponse['message'] = 'Scan error: ' . $e->getMessage();
            return $defaultResponse;
        }
    }

    /**
     * Check whether a party name or GSTIN belongs to our host company (Indivolt).
     */
    public function isOurCompany(?string $name, ?string $gstin): bool
    {
        $nameLower = strtolower(trim($name ?? ''));
        $gstinClean = strtoupper(str_replace(' ', '', $gstin ?? ''));

        if (!empty($gstinClean) && str_contains($gstinClean, '06AALFI9222R1ZO')) {
            return true;
        }

        if (!empty($nameLower) && str_contains($nameLower, 'indivolt')) {
            return true;
        }

        return false;
    }

    /**
     * Accurately differentiate Vendor (Seller) vs Customer (Buyer) and determine bill_type.
     */
    public function resolvePartiesAndType(array $data, ?string $preferredType = null): array
    {
        $seller = $data['seller'] ?? [];
        $buyer = $data['buyer'] ?? [];

        $sellerName = trim($seller['name'] ?? '');
        $sellerGstin = trim($seller['gstin'] ?? '');
        $sellerAddress = trim($seller['address'] ?? '');
        $sellerPhone = trim($seller['phone'] ?? '');

        $buyerName = trim($buyer['name'] ?? '');
        $buyerGstin = trim($buyer['gstin'] ?? '');
        $buyerAddress = trim($buyer['address'] ?? '');
        $buyerPhone = trim($buyer['phone'] ?? '');

        $sellerIsOurCompany = $this->isOurCompany($sellerName, $sellerGstin);
        $buyerIsOurCompany = $this->isOurCompany($buyerName, $buyerGstin);

        // Determine bill type and external party
        if ($sellerIsOurCompany && !$buyerIsOurCompany) {
            // We are the seller issuing the bill -> Outgoing SALE to customer
            $billType = 'sale';
            $partyName = $buyerName;
            $partyGstin = $buyerGstin;
            $partyAddress = $buyerAddress;
            $partyPhone = $buyerPhone;
            $partyRole = 'customer';
        } elseif ($buyerIsOurCompany && !$sellerIsOurCompany) {
            // We are the recipient / billed-to party -> Incoming PURCHASE from vendor
            $billType = 'purchase';
            $partyName = $sellerName;
            $partyGstin = $sellerGstin;
            $partyAddress = $sellerAddress;
            $partyPhone = $sellerPhone;
            $partyRole = 'vendor';
        } else {
            // Neither or both matched "Indivolt"
            // Respect preferredType if valid, otherwise fallback to AI detected type
            $billType = in_array($preferredType, ['purchase', 'sale'])
                ? $preferredType
                : ($data['bill_type'] ?? 'purchase');

            if ($billType === 'purchase') {
                // In purchase, the external party is the vendor / supplier (seller)
                $partyName = !empty($sellerName) ? $sellerName : ($data['party_name'] ?? $buyerName);
                $partyGstin = !empty($sellerGstin) ? $sellerGstin : ($data['party_gstin'] ?? $buyerGstin);
                $partyAddress = !empty($sellerAddress) ? $sellerAddress : ($data['party_address'] ?? $buyerAddress);
                $partyPhone = !empty($sellerPhone) ? $sellerPhone : ($data['party_phone'] ?? $buyerPhone);
                $partyRole = 'vendor';
            } else {
                // In sales, the external party is the customer / client / portal (buyer)
                $partyName = !empty($buyerName) ? $buyerName : ($data['party_name'] ?? $sellerName);
                $partyGstin = !empty($buyerGstin) ? $buyerGstin : ($data['party_gstin'] ?? $sellerGstin);
                $partyAddress = !empty($buyerAddress) ? $buyerAddress : ($data['party_address'] ?? $sellerAddress);
                $partyPhone = !empty($buyerPhone) ? $buyerPhone : ($data['party_phone'] ?? $sellerPhone);
                $partyRole = 'customer';
            }
        }

        // CRITICAL SAFEGUARD: Never let Indivolt be assigned as party_name if the other side has a valid name!
        if ($this->isOurCompany($partyName, $partyGstin)) {
            if ($billType === 'purchase' && !empty($sellerName) && !$this->isOurCompany($sellerName, $sellerGstin)) {
                $partyName = $sellerName;
                $partyGstin = $sellerGstin;
                $partyAddress = $sellerAddress;
                $partyPhone = $sellerPhone;
            } elseif ($billType === 'sale' && !empty($buyerName) && !$this->isOurCompany($buyerName, $buyerGstin)) {
                $partyName = $buyerName;
                $partyGstin = $buyerGstin;
                $partyAddress = $buyerAddress;
                $partyPhone = $buyerPhone;
            }
        }

        $data['bill_type'] = $billType;
        $data['party_name'] = $partyName;
        $data['party_gstin'] = $partyGstin ?: null;
        $data['party_address'] = $partyAddress ?: null;
        $data['party_phone'] = $partyPhone ?: null;
        $data['party_role'] = $partyRole;

        $data['seller'] = [
            'name' => $sellerName,
            'gstin' => $sellerGstin,
            'address' => $sellerAddress,
            'phone' => $sellerPhone,
            'is_our_company' => $sellerIsOurCompany,
        ];

        $data['buyer'] = [
            'name' => $buyerName,
            'gstin' => $buyerGstin,
            'address' => $buyerAddress,
            'phone' => $buyerPhone,
            'is_our_company' => $buyerIsOurCompany,
        ];

        return $data;
    }
}

