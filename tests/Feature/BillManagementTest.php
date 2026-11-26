<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BillManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'permissions' => ['*'],
        ]);

        Storage::fake('public');
    }

    public function test_authenticated_user_can_view_bills_index(): void
    {
        $response = $this->actingAs($this->admin)->get(route('bills.index'));
        $response->assertStatus(200);
        $response->assertSee('Bills & Scanned Invoices');
        $response->assertSee('Upload Scanned Bill');
    }

    public function test_bills_can_be_filtered_by_sales_and_purchases(): void
    {
        $purchaseBill = Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'PO-TEST-1001',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Syntro Vendor Ltd',
            'total_amount' => 12500.00,
            'file_path' => 'bills/purchase/test-po.pdf',
            'file_name' => 'test-po.pdf',
        ]);

        $saleBill = Bill::create([
            'bill_type' => 'sale',
            'invoice_number' => 'INV-SALE-2002',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Flipkart Commerce',
            'total_amount' => 45000.00,
            'file_path' => 'bills/sale/test-sale.pdf',
            'file_name' => 'test-sale.pdf',
        ]);

        // Filter purchases
        $responsePurchase = $this->actingAs($this->admin)->get(route('bills.index', ['type' => 'purchase']));
        $responsePurchase->assertStatus(200);
        $responsePurchase->assertSee('PO-TEST-1001');
        $responsePurchase->assertDontSee('INV-SALE-2002');

        // Filter sales
        $responseSale = $this->actingAs($this->admin)->get(route('bills.index', ['type' => 'sale']));
        $responseSale->assertStatus(200);
        $responseSale->assertSee('INV-SALE-2002');
        $responseSale->assertDontSee('PO-TEST-1001');
    }

    public function test_user_can_upload_and_store_a_scanned_bill(): void
    {
        $file = UploadedFile::fake()->create('invoice_sample.pdf', 1024, 'application/pdf');

        $payload = [
            'bill_type' => 'purchase',
            'invoice_number' => 'SAE-2026-999',
            'invoice_date' => '2026-10-21',
            'party_name' => 'Kenstar Electricals',
            'party_gstin' => '07AABCS1429B1Z',
            'subtotal' => 10000.00,
            'tax_amount' => 1800.00,
            'total_amount' => 11800.00,
            'status' => 'Paid',
            'payment_mode' => 'Bank Transfer',
            'file' => $file,
            'notes' => 'Bulk fan purchase invoice',
            'line_items' => json_encode([
                [
                    'item_name' => 'Kenstar Wall Fan 400mm',
                    'hsn' => '8414',
                    'quantity' => 10,
                    'rate' => 1000,
                    'amount' => 10000,
                ]
            ]),
        ];

        $response = $this->actingAs($this->admin)->post(route('bills.store'), $payload);
        $response->assertSessionHasNoErrors();

        $bill = Bill::where('invoice_number', 'SAE-2026-999')->first();
        $this->assertNotNull($bill);
        $this->assertEquals('purchase', $bill->bill_type);
        $this->assertEquals('Kenstar Electricals', $bill->party_name);
        $this->assertEquals(11800.00, (float) $bill->total_amount);

        // Verify file stored in fake storage
        Storage::disk('public')->assertExists($bill->file_path);

        $response->assertRedirect(route('bills.show', $bill));
    }

    public function test_clicking_invoice_opens_split_screen_with_pdf_and_details(): void
    {
        $file = UploadedFile::fake()->create('document_sample.pdf', 500, 'application/pdf');
        $storedPath = $file->store('bills/purchase/2026/10', 'public');

        $bill = Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'SPLIT-VIEW-777',
            'invoice_date' => '2026-10-21',
            'party_name' => 'Zigma Fan Appliances',
            'party_gstin' => '27AAECZ1234A1Z',
            'subtotal' => 20000.00,
            'tax_amount' => 3600.00,
            'total_amount' => 23600.00,
            'status' => 'Paid',
            'payment_mode' => 'RTGS',
            'file_path' => $storedPath,
            'file_name' => 'document_sample.pdf',
            'file_size' => 500 * 1024,
            'mime_type' => 'application/pdf',
            'line_items' => [
                ['item_name' => 'zigma Bullet Heavy Duty', 'quantity' => 20, 'rate' => 1000, 'amount' => 20000]
            ],
            'notes' => 'Fast delivery invoice'
        ]);

        $response = $this->actingAs($this->admin)->get(route('bills.show', $bill));
        $response->assertStatus(200);

        // Assert PDF embedded iframe is present
        $response->assertSee('<iframe', false);
        $response->assertSee($bill->file_url);

        // Assert structured details are present on the right side
        $response->assertSee('SPLIT-VIEW-777');
        $response->assertSee('Zigma Fan Appliances');
        $response->assertSee('27AAECZ1234A1Z');
        $response->assertSee('₹23,600.00');
        $response->assertSee('zigma Bullet Heavy Duty');
        $response->assertSee('Fast delivery invoice');
    }

    public function test_user_can_delete_bill_and_its_scanned_file(): void
    {
        $file = UploadedFile::fake()->create('delete_me.pdf', 200, 'application/pdf');
        $storedPath = $file->store('bills/purchase/2026/10', 'public');

        $bill = Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'DEL-INV-001',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Temporary Vendor',
            'total_amount' => 500.00,
            'file_path' => $storedPath,
            'file_name' => 'delete_me.pdf',
        ]);

        Storage::disk('public')->assertExists($storedPath);

        $response = $this->actingAs($this->admin)->delete(route('bills.destroy', $bill));
        $response->assertRedirect(route('bills.index', ['type' => 'purchase']));

        $this->assertDatabaseMissing('bills', ['id' => $bill->id]);
        Storage::disk('public')->assertMissing($storedPath);
    }

    public function test_user_can_update_bill_details_and_updated_by_is_recorded(): void
    {
        $bill = Bill::create([
            'bill_type' => 'sale',
            'invoice_number' => 'INV-UP-001',
            'invoice_date' => '2026-10-21',
            'party_name' => 'Original Customer',
            'total_amount' => 5000.00,
            'status' => 'Pending',
            'file_path' => 'bills/test.pdf',
            'file_name' => 'test.pdf',
        ]);

        $updateData = [
            'bill_type' => 'sale',
            'invoice_number' => 'INV-UP-001',
            'invoice_date' => '2026-10-22',
            'party_name' => 'Updated Customer Name',
            'total_amount' => 7500.00,
            'status' => 'Paid',
            'payment_mode' => 'UPI',
        ];

        $response = $this->actingAs($this->admin)->put(route('bills.update', $bill), $updateData);
        $response->assertRedirect(route('bills.show', $bill));

        $bill->refresh();
        $this->assertEquals('Updated Customer Name', $bill->party_name);
        $this->assertEquals(7500.00, (float) $bill->total_amount);
        $this->assertEquals('Paid', $bill->status);
        $this->assertEquals($this->admin->name, $bill->updated_by);
    }

    public function test_bill_accepts_decimal_quantities_in_line_items(): void
    {
        $file = UploadedFile::fake()->create('decimal_sample.pdf', 300, 'application/pdf');

        $payload = [
            'bill_type' => 'purchase',
            'invoice_number' => 'DEC-2026-001',
            'invoice_date' => '2026-10-25',
            'party_name' => 'Copper Wire Suppliers Ltd',
            'total_amount' => 15750.50,
            'status' => 'Paid',
            'file' => $file,
            'line_items' => json_encode([
                [
                    'item_name' => '0.5mm Copper Winding Wire Coil',
                    'hsn' => '8544',
                    'quantity' => 12.75, // Decimal quantity
                    'rate' => 1200.00,
                    'amount' => 15300.00,
                ],
                [
                    'item_name' => 'Insulation Sleeving (Meters)',
                    'hsn' => '8546',
                    'quantity' => 0.5, // Sub-unit decimal quantity
                    'rate' => 901.00,
                    'amount' => 450.50,
                ]
            ]),
        ];

        $response = $this->actingAs($this->admin)->post(route('bills.store'), $payload);
        $response->assertSessionHasNoErrors();

        $bill = Bill::where('invoice_number', 'DEC-2026-001')->first();
        $this->assertNotNull($bill);
        $this->assertEquals(12.75, $bill->line_items[0]['quantity']);
        $this->assertEquals(0.5, $bill->line_items[1]['quantity']);

        // Check show page displays decimal quantities
        $showResponse = $this->actingAs($this->admin)->get(route('bills.show', $bill));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('12.75');
        $showResponse->assertSee('0.5');
    }

    public function test_gemini_service_accurately_recognises_vendor_on_purchase_bill(): void
    {
        $gemini = app(\App\Services\GeminiService::class);

        // Simulated raw extraction where Aay Pee Industries is seller and Indivolt is buyer
        $rawAiData = [
            'seller' => [
                'name' => 'Aay Pee Industries',
                'gstin' => '07AHHPM9781C2Z1',
                'address' => 'Bawana Industrial Area, Delhi',
                'phone' => '9811400643',
            ],
            'buyer' => [
                'name' => 'INDIVOLT APPLIANCES & ELECTRICALS',
                'gstin' => '06AALFI9222R1ZO',
                'address' => 'Sector-43, Gurugram, Haryana',
                'phone' => null,
            ],
            'invoice_number' => '80',
            'invoice_date' => '2026-08-17',
            'subtotal' => 251500.00,
            'tax_amount' => 45270.00,
            'total_amount' => 296770.00,
        ];

        $resolved = $gemini->resolvePartiesAndType($rawAiData);

        // Bill type must be purchase
        $this->assertEquals('purchase', $resolved['bill_type']);
        // Party name must be the external Vendor (Aay Pee Industries), NOT Indivolt!
        $this->assertEquals('Aay Pee Industries', $resolved['party_name']);
        $this->assertEquals('07AHHPM9781C2Z1', $resolved['party_gstin']);
        $this->assertEquals('vendor', $resolved['party_role']);
        $this->assertFalse($resolved['seller']['is_our_company']);
        $this->assertTrue($resolved['buyer']['is_our_company']);
    }

    public function test_gemini_service_accurately_recognises_customer_on_sales_bill(): void
    {
        $gemini = app(\App\Services\GeminiService::class);

        // Simulated raw extraction where Indivolt is seller and Flipkart is buyer
        $rawAiData = [
            'seller' => [
                'name' => 'Indivolt Appliances & Electricals',
                'gstin' => '06AALFI9222R1ZO',
                'address' => 'Sector-43, Gurugram, Haryana',
                'phone' => null,
            ],
            'buyer' => [
                'name' => 'Flipkart India Private Limited',
                'gstin' => '29AAACF3294C1ZT',
                'address' => 'Bengaluru, Karnataka',
                'phone' => '080-67891234',
            ],
            'invoice_number' => 'INV-2026-0042',
            'invoice_date' => '2026-10-21',
            'total_amount' => 54000.00,
        ];

        $resolved = $gemini->resolvePartiesAndType($rawAiData);

        // Bill type must be sale
        $this->assertEquals('sale', $resolved['bill_type']);
        // Party name must be the external Customer (Flipkart), NOT Indivolt!
        $this->assertEquals('Flipkart India Private Limited', $resolved['party_name']);
        $this->assertEquals('29AAACF3294C1ZT', $resolved['party_gstin']);
        $this->assertEquals('customer', $resolved['party_role']);
        $this->assertTrue($resolved['seller']['is_our_company']);
        $this->assertFalse($resolved['buyer']['is_our_company']);
    }

    public function test_gemini_service_safeguards_against_assigning_our_company_as_party(): void
    {
        $gemini = app(\App\Services\GeminiService::class);

        // Edge case: OCR mistakenly flagged party_name as Indivolt
        $rawAiData = [
            'seller' => [
                'name' => 'Syntro Components Ltd',
                'gstin' => '07BBBBB1111B1Z1',
            ],
            'buyer' => [
                'name' => 'Indivolt Appliances',
                'gstin' => '06AALFI9222R1ZO',
            ],
            'bill_type' => 'purchase',
            'party_name' => 'Indivolt Appliances', // accidental wrong assignment by LLM
        ];

        $resolved = $gemini->resolvePartiesAndType($rawAiData);

        // The safeguard must correct party_name to Syntro Components Ltd
        $this->assertEquals('Syntro Components Ltd', $resolved['party_name']);
        $this->assertEquals('purchase', $resolved['bill_type']);
    }

    public function test_user_can_store_and_view_both_from_and_to_parties(): void
    {
        $file = UploadedFile::fake()->create('sample_bill.pdf', 300, 'application/pdf');

        $payload = [
            'bill_type' => 'purchase',
            'invoice_number' => 'SAE/26-27/752',
            'invoice_date' => '2026-10-21',
            'party_name' => 'S.A.E.GLOBAL PRIVATE LIMITED',
            'party_gstin' => '06ABLCS2636M1ZW',
            'party_phone' => '9212440123',
            'party_address' => 'Plot 250, Sonipat',
            'seller_name' => 'S.A.E.GLOBAL PRIVATE LIMITED',
            'seller_gstin' => '06ABLCS2636M1ZW',
            'seller_address' => 'Plot 250, Sonipat',
            'seller_phone' => '9212440123',
            'buyer_name' => 'INDIVOLT APPLIANCES & ELECTRICALS',
            'buyer_gstin' => '06AALFI9222R1ZO',
            'buyer_address' => 'Sector-43 Gurugram',
            'buyer_phone' => '9876543210',
            'total_amount' => 150000.00,
            'status' => 'Paid',
            'file' => $file,
        ];

        $response = $this->actingAs($this->admin)->post(route('bills.store'), $payload);
        $response->assertSessionHasNoErrors();

        $bill = Bill::where('invoice_number', 'SAE/26-27/752')->first();
        $this->assertNotNull($bill);
        $this->assertEquals('S.A.E.GLOBAL PRIVATE LIMITED', $bill->seller_name);
        $this->assertEquals('06ABLCS2636M1ZW', $bill->seller_gstin);
        $this->assertEquals('INDIVOLT APPLIANCES & ELECTRICALS', $bill->buyer_name);
        $this->assertEquals('06AALFI9222R1ZO', $bill->buyer_gstin);

        // Verify show page displays both FROM and TO
        $showResponse = $this->actingAs($this->admin)->get(route('bills.show', $bill));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('FROM: SUPPLIER / SELLER');
        $showResponse->assertSee('S.A.E.GLOBAL PRIVATE LIMITED');
        $showResponse->assertSee('TO: BILLED TO / BUYER');
        $showResponse->assertSee('INDIVOLT APPLIANCES & ELECTRICALS');
    }

    public function test_bills_can_be_filtered_by_buyer_company_and_supplier_vendor(): void
    {
        Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'TEST-BUY-01',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'S.A.E. Global',
            'seller_name' => 'S.A.E. Global',
            'buyer_name' => 'Syntro Global Marketing',
            'total_amount' => 10000.00,
            'file_path' => 'bills/purchase/test1.pdf',
            'file_name' => 'test1.pdf',
        ]);

        Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'TEST-BUY-02',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Mahadev Techno',
            'seller_name' => 'Mahadev Techno',
            'buyer_name' => 'Syntro Global Marketing',
            'total_amount' => 20000.00,
            'file_path' => 'bills/purchase/test2.pdf',
            'file_name' => 'test2.pdf',
        ]);

        Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'TEST-BUY-03',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'NXTLAB Appliances',
            'seller_name' => 'NXTLAB Appliances',
            'buyer_name' => 'Indivolt Appliances',
            'total_amount' => 30000.00,
            'file_path' => 'bills/purchase/test3.pdf',
            'file_name' => 'test3.pdf',
        ]);

        // 1. Unfiltered purchases: Buyer cards should be shown, all 3 invoices visible
        $response = $this->actingAs($this->admin)->get(route('bills.index', ['type' => 'purchase']));
        $response->assertStatus(200);
        $response->assertSee('Syntro Global Marketing');
        $response->assertSee('Indivolt Appliances');
        $response->assertSee('TEST-BUY-01');
        $response->assertSee('TEST-BUY-02');
        $response->assertSee('TEST-BUY-03');

        // 2. Filter by Buyer (Syntro Global Marketing): Supplier cards shown, only TEST-BUY-01 and 02 visible
        $buyerResponse = $this->actingAs($this->admin)->get(route('bills.index', [
            'type' => 'purchase',
            'buyer' => 'Syntro Global Marketing',
        ]));
        $buyerResponse->assertStatus(200);
        $buyerResponse->assertSee('S.A.E. Global');
        $buyerResponse->assertSee('Mahadev Techno');
        $buyerResponse->assertSee('TEST-BUY-01');
        $buyerResponse->assertSee('TEST-BUY-02');
        $buyerResponse->assertDontSee('TEST-BUY-03');

        // 3. Drill down by both Buyer and Supplier (S.A.E. Global): only TEST-BUY-01 visible
        $drilldownResponse = $this->actingAs($this->admin)->get(route('bills.index', [
            'type' => 'purchase',
            'buyer' => 'Syntro Global Marketing',
            'seller' => 'S.A.E. Global',
        ]));
        $drilldownResponse->assertStatus(200);
        $drilldownResponse->assertSee('TEST-BUY-01');
        $drilldownResponse->assertDontSee('TEST-BUY-02');
        $drilldownResponse->assertDontSee('TEST-BUY-03');
    }

    public function test_sales_bills_can_be_filtered_by_sales_from_and_sold_to_customer(): void
    {
        Bill::create([
            'bill_type' => 'sale',
            'invoice_number' => 'TEST-SALE-01',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Flatron Plus Industries',
            'seller_name' => 'Syntro Global Marketing',
            'buyer_name' => 'Flatron Plus Industries',
            'total_amount' => 50000.00,
            'file_path' => 'bills/sale/test_sale1.pdf',
            'file_name' => 'test_sale1.pdf',
        ]);

        Bill::create([
            'bill_type' => 'sale',
            'invoice_number' => 'TEST-SALE-02',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Reliance Retail',
            'seller_name' => 'Syntro Global Marketing',
            'buyer_name' => 'Reliance Retail',
            'total_amount' => 75000.00,
            'file_path' => 'bills/sale/test_sale2.pdf',
            'file_name' => 'test_sale2.pdf',
        ]);

        Bill::create([
            'bill_type' => 'sale',
            'invoice_number' => 'TEST-SALE-03',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Croma Digital',
            'seller_name' => 'Indivolt Appliances',
            'buyer_name' => 'Croma Digital',
            'total_amount' => 90000.00,
            'file_path' => 'bills/sale/test_sale3.pdf',
            'file_name' => 'test_sale3.pdf',
        ]);

        // 1. Unfiltered sales: Selling Entity cards shown, all 3 invoices visible
        $response = $this->actingAs($this->admin)->get(route('bills.index', ['type' => 'sale']));
        $response->assertStatus(200);
        $response->assertSee('Sales Company Explorer');
        $response->assertSee('Step 1: Select Selling Entity (Sales FROM)');
        $response->assertSee('Syntro Global Marketing');
        $response->assertSee('Indivolt Appliances');
        $response->assertSee('TEST-SALE-01');
        $response->assertSee('TEST-SALE-02');
        $response->assertSee('TEST-SALE-03');

        // 2. Filter by Sales FROM (Syntro Global Marketing): Customer cards shown, only TEST-SALE-01 and 02 visible
        $sellerResponse = $this->actingAs($this->admin)->get(route('bills.index', [
            'type' => 'sale',
            'seller' => 'Syntro Global Marketing',
        ]));
        $sellerResponse->assertStatus(200);
        $sellerResponse->assertSee('Step 2: Customers / Buyers purchasing from');
        $sellerResponse->assertSee('Flatron Plus Industries');
        $sellerResponse->assertSee('Reliance Retail');
        $sellerResponse->assertSee('TEST-SALE-01');
        $sellerResponse->assertSee('TEST-SALE-02');
        $sellerResponse->assertDontSee('TEST-SALE-03');

        // 3. Drill down by both Sales FROM and Customer Sold TO (Flatron Plus Industries): only TEST-SALE-01 visible
        $drilldownResponse = $this->actingAs($this->admin)->get(route('bills.index', [
            'type' => 'sale',
            'seller' => 'Syntro Global Marketing',
            'buyer' => 'Flatron Plus Industries',
        ]));
        $drilldownResponse->assertStatus(200);
        $drilldownResponse->assertSee('TEST-SALE-01');
        $drilldownResponse->assertDontSee('TEST-SALE-02');
        $drilldownResponse->assertDontSee('TEST-SALE-03');
    }

    public function test_bill_line_items_can_include_discount_percentage(): void
    {
        $bill = Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'SAE/26-27/1507',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'S.A.E. GLOBAL PRIVATE LIMITED',
            'seller_name' => 'S.A.E. GLOBAL PRIVATE LIMITED',
            'buyer_name' => 'SYNTRO GLOBAL MARKETING PRIVATE LIMITED',
            'subtotal' => 360150.00,
            'tax_amount' => 64828.00,
            'total_amount' => 424978.00,
            'status' => 'Paid',
            'file_path' => 'bills/purchase/test_disc.pdf',
            'file_name' => 'test_disc.pdf',
            'line_items' => [
                [
                    'item_name' => 'PEDESTAL FAN 16" SYNTRO AEROMAX CHERRY',
                    'hsn' => '841451',
                    'quantity' => 250,
                    'rate' => 1470,
                    'discount_percent' => 2.0,
                    'amount' => 360150.00,
                    'tax_rate' => 18,
                ]
            ],
        ]);

        // Edit page should see Disc %
        $editResponse = $this->actingAs($this->admin)->get(route('bills.edit', $bill));
        $editResponse->assertStatus(200);
        $editResponse->assertSee('Disc %');

        // Show page should display 2% discount badge
        $showResponse = $this->actingAs($this->admin)->get(route('bills.show', $bill));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('Disc %');
        $showResponse->assertSee('2%');
        $showResponse->assertSee('360,150.00');

        // Updating with new line items and discount
        $updatePayload = [
            'bill_type' => 'purchase',
            'invoice_number' => 'SAE/26-27/1507',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'S.A.E. GLOBAL PRIVATE LIMITED',
            'seller_name' => 'S.A.E. GLOBAL PRIVATE LIMITED',
            'buyer_name' => 'SYNTRO GLOBAL MARKETING PRIVATE LIMITED',
            'subtotal' => 360150.00,
            'tax_amount' => 64828.00,
            'total_amount' => 424978.00,
            'status' => 'Paid',
            'line_items' => json_encode([
                [
                    'item_name' => 'PEDESTAL FAN 16" SYNTRO AEROMAX CHERRY',
                    'hsn' => '841451',
                    'quantity' => 250,
                    'rate' => 1470,
                    'discount_percent' => 2.0,
                    'amount' => 360150.00,
                ]
            ]),
        ];

        $updateResponse = $this->actingAs($this->admin)->put(route('bills.update', $bill), $updatePayload);
        $updateResponse->assertSessionHasNoErrors();

        $freshBill = $bill->fresh();
        $this->assertEquals(2.0, $freshBill->line_items[0]['discount_percent']);
        $this->assertEquals(360150.00, $freshBill->line_items[0]['amount']);
    }
}

