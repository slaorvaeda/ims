<?php

namespace Tests\Feature;

use App\Models\Bill;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
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

    public function test_live_search_returns_empty_when_no_query(): void
    {
        $response = $this->actingAs($this->admin)->getJson(route('search.live', ['q' => '']));
        $response->assertStatus(200);
        $response->assertJson([
            'total' => 0,
            'bills' => [],
            'products' => [],
        ]);
    }

    public function test_live_search_finds_bills_by_invoice_number_and_party(): void
    {
        Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'PO-GLOBAL-999',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Aay Pee Industries',
            'total_amount' => 12500.00,
            'file_path' => 'bills/test.pdf',
            'file_name' => 'test.pdf',
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('search.live', ['q' => 'GLOBAL']));
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Invoice #PO-GLOBAL-999']);

        $responseParty = $this->actingAs($this->admin)->getJson(route('search.live', ['q' => 'Aay Pee']));
        $responseParty->assertStatus(200);
        $responseParty->assertJsonFragment(['title' => 'Invoice #PO-GLOBAL-999']);
    }

    public function test_live_search_finds_bills_by_item_description_in_line_items(): void
    {
        Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'INV-FAN-001',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Fan Supplier Ltd',
            'total_amount' => 50000.00,
            'file_path' => 'bills/fan.pdf',
            'file_name' => 'fan.pdf',
            'line_items' => [
                ['item_name' => 'BLDC High Speed Ceiling Fan 48 Inch', 'quantity' => 10, 'rate' => 5000, 'amount' => 50000]
            ],
        ]);

        // Search case-insensitive "ceiling fan"
        $response = $this->actingAs($this->admin)->getJson(route('search.live', ['q' => 'ceiling fan']));
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Invoice #INV-FAN-001']);
    }

    public function test_live_search_finds_products_by_name_or_sku(): void
    {
        Product::create([
            'product_id' => 'PID-999',
            'product_name' => 'Kenstar Turbo High Speed',
            'sku' => 'KEN-TURBO-400',
        ]);

        $response = $this->actingAs($this->admin)->getJson(route('search.live', ['q' => 'Turbo']));
        $response->assertStatus(200);
        $response->assertJsonFragment(['title' => 'Kenstar Turbo High Speed']);
    }

    public function test_search_index_page_displays_all_results_and_tabs(): void
    {
        Bill::create([
            'bill_type' => 'purchase',
            'invoice_number' => 'BILL-SEARCH-101',
            'invoice_date' => now()->format('Y-m-d'),
            'party_name' => 'Mega Vendor',
            'total_amount' => 8000.00,
            'file_path' => 'bills/b.pdf',
            'file_name' => 'b.pdf',
        ]);

        $response = $this->actingAs($this->admin)->get(route('search.index', ['q' => 'BILL-SEARCH-101']));
        $response->assertStatus(200);
        $response->assertSee('Global Omnisearch');
        $response->assertSee('BILL-SEARCH-101');
        $response->assertSee('Mega Vendor');
    }

    public function test_search_query_with_arbitrary_string_does_not_throw_sql_error(): void
    {
        $responseLive = $this->actingAs($this->admin)->getJson(route('search.live', ['q' => 'zigma']));
        $responseLive->assertStatus(200);

        $responseIndex = $this->actingAs($this->admin)->get(route('search.index', ['q' => 'zigma']));
        $responseIndex->assertStatus(200);
    }
}
