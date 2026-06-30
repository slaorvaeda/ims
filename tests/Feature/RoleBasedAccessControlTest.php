<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleBasedAccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_user_management(): void
    {
        $response = $this->get('/users');

        $response->assertRedirect('/login');
    }

    public function test_operator_cannot_access_user_management(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this
            ->actingAs($operator)
            ->get('/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_user_management(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->get('/users');

        $response->assertOk();
    }

    public function test_admin_can_create_user_with_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this
            ->actingAs($admin)
            ->post('/users', [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'status' => 'Active',
                'role' => 'operator',
            ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'role' => 'operator',
        ]);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $user = User::factory()->create(['role' => 'operator']);

        $response = $this
            ->actingAs($admin)
            ->put("/users/{$user->id}", [
                'name' => 'Updated Name',
                'email' => $user->email,
                'status' => 'Active',
                'role' => 'admin',
            ]);

        $response->assertRedirect('/users');
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => 'admin',
        ]);
    }

    public function test_operator_cannot_create_or_update_users(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);
        
        // Try to create
        $responseCreate = $this
            ->actingAs($operator)
            ->post('/users', [
                'name' => 'New User',
                'email' => 'newuser@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'status' => 'Active',
                'role' => 'operator',
            ]);
        $responseCreate->assertStatus(403);

        // Try to update
        $anotherUser = User::factory()->create(['role' => 'operator']);
        $responseUpdate = $this
            ->actingAs($operator)
            ->put("/users/{$anotherUser->id}", [
                'name' => 'Updated Name',
                'email' => $anotherUser->email,
                'status' => 'Active',
                'role' => 'admin',
            ]);
        $responseUpdate->assertStatus(403);
    }

    public function test_operator_permissions_can_be_assigned_and_restricted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create operator with only 'products' and 'sales' permissions
        $response = $this
            ->actingAs($admin)
            ->post('/users', [
                'name' => 'Operator User',
                'email' => 'operator_perm@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'status' => 'Active',
                'role' => 'operator',
                'permissions' => ['products', 'sales'],
            ]);

        $response->assertRedirect('/users');
        
        $operator = User::where('email', 'operator_perm@example.com')->first();
        $this->assertNotNull($operator);
        $this->assertTrue($operator->hasPermission('products'));
        $this->assertTrue($operator->hasPermission('sales'));
        $this->assertFalse($operator->hasPermission('purchases'));
        $this->assertFalse($operator->hasPermission('barcodes'));

        // Test accessing products (authorized)
        $responseProducts = $this
            ->actingAs($operator)
            ->get('/products');
        $responseProducts->assertOk();

        // Test accessing purchases (unauthorized)
        $responsePurchases = $this
            ->actingAs($operator)
            ->get('/purchases');
        $responsePurchases->assertStatus(403);

        // Test accessing barcodes (unauthorized)
        $responseBarcodes = $this
            ->actingAs($operator)
            ->get('/barcodes');
        $responseBarcodes->assertStatus(403);
    }

    public function test_admin_automatically_has_all_permissions_even_if_permissions_list_is_empty(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'permissions' => []
        ]);

        $this->assertTrue($admin->hasPermission('products'));
        $this->assertTrue($admin->hasPermission('purchases'));
        $this->assertTrue($admin->hasPermission('barcodes'));

        $responseProducts = $this
            ->actingAs($admin)
            ->get('/products');
        $responseProducts->assertOk();
    }
}
