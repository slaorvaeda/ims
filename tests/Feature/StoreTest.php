<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Store;
use App\Models\PortalVendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected PortalVendor $amazonPortal;
    protected PortalVendor $flipkartPortal;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->amazonPortal = PortalVendor::create([
            'name' => 'AMAZON',
            'type' => 'Portal',
        ]);

        $this->flipkartPortal = PortalVendor::create([
            'name' => 'FLIPKART',
            'type' => 'Portal',
        ]);
    }

    /**
     * Test guests cannot access stores configuration.
     */
    public function test_guests_cannot_access_stores_config()
    {
        $response = $this->get('/stores');
        $response->assertRedirect('/login');
    }

    /**
     * Test authenticated users can view stores listing page.
     */
    public function test_authenticated_users_can_view_stores_listing()
    {
        $store = Store::create([
            'store_name' => 'My Amazon US',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'active',
            'credentials' => [
                'client_id' => 'client123',
                'client_secret' => 'secret123',
                'refresh_token' => 'refresh123',
                'region' => 'na',
            ],
            'enabled_apis' => ['orders', 'inventory'],
        ]);

        $response = $this->actingAs($this->user)->get('/stores');
        $response->assertStatus(200);
        $response->assertSee('My Amazon US');
        $response->assertSee('Amazon SP-API');
    }

    /**
     * Test storing an Amazon store configuration encrypts the credentials.
     */
    public function test_storing_amazon_store_encrypts_credentials()
    {
        $response = $this->actingAs($this->user)->post('/stores', [
            'store_name' => 'My Amazon EU',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'active',
            'amazon_client_id' => 'clientIdX',
            'amazon_client_secret' => 'clientSecretY',
            'amazon_refresh_token' => 'refreshTokenZ',
            'amazon_region' => 'eu',
            'enabled_apis' => ['orders', 'pricing'],
        ]);

        $response->assertRedirect('/stores');

        $this->assertDatabaseHas('stores', [
            'store_name' => 'My Amazon EU',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'active',
        ]);

        $store = Store::where('store_name', 'My Amazon EU')->firstOrFail();
        
        // Assert cast behaves correctly
        $this->assertEquals('clientIdX', $store->credentials['client_id']);
        $this->assertEquals('clientSecretY', $store->credentials['client_secret']);
        $this->assertEquals('refreshTokenZ', $store->credentials['refresh_token']);
        $this->assertEquals('eu', $store->credentials['region']);

        // Assert enabled_apis is cast correctly
        $this->assertEquals(['orders', 'pricing'], $store->enabled_apis);

        // Verify that raw database storage is encrypted
        $rawStore = \Illuminate\Support\Facades\DB::table('stores')
            ->where('store_name', 'My Amazon EU')
            ->first();
        
        // The raw string in database should not match plain text
        $this->assertStringNotContainsString('clientIdX', $rawStore->credentials);
        $this->assertStringNotContainsString('clientSecretY', $rawStore->credentials);
    }

    /**
     * Test storing a Flipkart store configuration works.
     */
    public function test_storing_flipkart_store_success()
    {
        $response = $this->actingAs($this->user)->post('/stores', [
            'store_name' => 'My Flipkart Shop',
            'portal_vendor_id' => $this->flipkartPortal->id,
            'status' => 'inactive',
            'flipkart_app_id' => 'appIdA',
            'flipkart_app_secret' => 'appSecretB',
            'flipkart_username' => 'userC',
            'flipkart_password' => 'passD',
            'enabled_apis' => ['inventory'],
        ]);

        $response->assertRedirect('/stores');

        $store = Store::where('store_name', 'My Flipkart Shop')->firstOrFail();
        $this->assertEquals('appIdA', $store->credentials['app_id']);
        $this->assertEquals('appSecretB', $store->credentials['app_secret']);
        $this->assertEquals('userC', $store->credentials['username']);
        $this->assertEquals('passD', $store->credentials['password']);
        $this->assertEquals('inactive', $store->status);
    }

    /**
     * Test invalid portal inputs fail validation.
     */
    public function test_invalid_region_validation_fails()
    {
        $response = $this->actingAs($this->user)->post('/stores', [
            'store_name' => 'Invalid Amazon Store',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'active',
            'amazon_client_id' => 'clientIdX',
            'amazon_client_secret' => 'clientSecretY',
            'amazon_refresh_token' => 'refreshTokenZ',
            'amazon_region' => 'invalid-region',
        ]);

        $response->assertSessionHasErrors(['amazon_region']);
    }

    /**
     * Test updating store credentials.
     */
    public function test_updating_store_credentials_success()
    {
        $store = Store::create([
            'store_name' => 'Old Amazon Store',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'active',
            'credentials' => [
                'client_id' => 'old_id',
                'client_secret' => 'old_secret',
                'refresh_token' => 'old_token',
                'region' => 'na',
            ],
            'enabled_apis' => ['orders'],
        ]);

        $response = $this->actingAs($this->user)->put("/stores/{$store->id}", [
            'store_name' => 'Updated Amazon Store',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'inactive',
            'amazon_client_id' => 'new_id',
            'amazon_client_secret' => 'new_secret',
            'amazon_refresh_token' => 'new_token',
            'amazon_region' => 'fe',
            'enabled_apis' => ['orders', 'inventory', 'pricing'],
        ]);

        $response->assertRedirect('/stores');

        $store->refresh();
        $this->assertEquals('Updated Amazon Store', $store->store_name);
        $this->assertEquals('new_id', $store->credentials['client_id']);
        $this->assertEquals('fe', $store->credentials['region']);
        $this->assertEquals('inactive', $store->status);
        $this->assertEquals(['orders', 'inventory', 'pricing'], $store->enabled_apis);
    }

    /**
     * Test deleting store configurations.
     */
    public function test_deleting_store_configuration_success()
    {
        $store = Store::create([
            'store_name' => 'To Be Deleted',
            'portal_vendor_id' => $this->amazonPortal->id,
            'status' => 'active',
            'credentials' => [
                'client_id' => 'del_id',
                'client_secret' => 'del_secret',
                'refresh_token' => 'del_token',
                'region' => 'na',
            ],
        ]);

        $response = $this->actingAs($this->user)->delete("/stores/{$store->id}");
        $response->assertRedirect('/stores');

        $this->assertDatabaseMissing('stores', [
            'id' => $store->id,
        ]);
    }
}
