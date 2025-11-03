<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Modules\Attributes\Entities\Category;
use Modules\Product\Entities\Product;
use Stancl\Tenancy\Database\Models\Domain;
use Tests\TestCase;

class TenantApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $tenant;
    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant
        $this->tenant = \App\Models\Tenant::create([
            'id' => 'test-tenant',
            'data' => [],
        ]);

        // Create domain
        Domain::create([
            'domain' => 'test-tenant',
            'tenant_id' => $this->tenant->id,
        ]);

        // Initialize tenancy
        tenancy()->initialize($this->tenant);

        // Create admin in tenant context
        $this->admin = Admin::factory()->create([
            'email' => 'admin@tenant.com',
            'password' => bcrypt('password'),
        ]);

        // Create token with tenant context
        $this->token = $this->admin->createToken('api-token', [
            'tenant_id' => $this->tenant->id,
        ])->plainTextToken;
    }

    protected function tearDown(): void
    {
        tenancy()->end();
        parent::tearDown();
    }

    /** @test */
    public function it_can_login_tenant_admin()
    {
        $response = $this->postJson('http://test-tenant.local/api/tenant/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'admin',
                    'tenant',
                    'token',
                ],
            ])
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_can_get_current_tenant_admin()
    {
        $response = $this->getJson('http://test-tenant.local/api/tenant/v1/auth/me', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'admin',
                    'tenant',
                ],
            ]);
    }

    /** @test */
    public function it_can_get_tenant_dashboard()
    {
        $response = $this->getJson('http://test-tenant.local/api/tenant/v1/dashboard', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);
    }

    /** @test */
    public function it_can_list_products()
    {
        Product::factory()->count(5)->create();

        $response = $this->getJson('http://test-tenant.local/api/tenant/v1/products', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta',
            ]);
    }

    /** @test */
    public function it_can_list_orders()
    {
        ProductOrder::factory()->count(3)->create();

        $response = $this->getJson('http://test-tenant.local/api/tenant/v1/orders', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta',
            ]);
    }

    /** @test */
    public function it_can_update_order_status()
    {
        $order = ProductOrder::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->postJson(
            "http://test-tenant.local/api/tenant/v1/orders/{$order->id}/update-status",
            [
                'status' => 'complete',
                'payment_status' => 'success',
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertEquals('complete', $order->fresh()->status);
    }

    /** @test */
    public function it_can_list_customers()
    {
        User::factory()->count(5)->create();

        $response = $this->getJson('http://test-tenant.local/api/tenant/v1/customers', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta',
            ]);
    }

    /** @test */
    public function it_can_get_customer_orders()
    {
        $customer = User::factory()->create();
        ProductOrder::factory()->count(3)->create(['user_id' => $customer->id]);

        $response = $this->getJson(
            "http://test-tenant.local/api/tenant/v1/customers/{$customer->id}/orders",
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta',
            ]);
    }

    /** @test */
    public function it_can_list_categories()
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('http://test-tenant.local/api/tenant/v1/categories', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
                'meta',
            ]);
    }

    /** @test */
    public function it_can_create_category()
    {
        $response = $this->postJson(
            'http://test-tenant.local/api/tenant/v1/categories',
            [
                'name' => 'Electronics',
                'slug' => 'electronics',
                'description' => 'Electronic products',
            ],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Electronics',
        ]);
    }

    /** @test */
    public function it_validates_category_creation_data()
    {
        $response = $this->postJson(
            'http://test-tenant.local/api/tenant/v1/categories',
            [],
            [
                'Authorization' => 'Bearer ' . $this->token,
            ]
        );

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    /** @test */
    public function it_requires_tenant_context()
    {
        // Try accessing without tenant domain
        $response = $this->getJson('/api/tenant/v1/products', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(403);
    }
}

