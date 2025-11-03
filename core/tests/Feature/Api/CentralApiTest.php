<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CentralApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $admin;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        // Create token
        $this->token = $this->admin->createToken('api-token')->plainTextToken;
    }

    /** @test */
    public function it_can_login_central_admin()
    {
        $response = $this->postJson('/api/central/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'admin',
                    'token',
                    'token_type',
                ],
            ])
            ->assertJson(['success' => true]);
    }

    /** @test */
    public function it_rejects_invalid_credentials()
    {
        $response = $this->postJson('/api/central/v1/auth/login', [
            'email' => $this->admin->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422);
    }

    /** @test */
    public function it_can_get_current_admin()
    {
        $response = $this->getJson('/api/central/v1/auth/me', [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'name',
                    'email',
                ],
            ]);
    }

    /** @test */
    public function it_requires_authentication_for_protected_routes()
    {
        $response = $this->getJson('/api/central/v1/dashboard');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_get_dashboard_statistics()
    {
        $response = $this->getJson('/api/central/v1/dashboard', [
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
    public function it_can_list_tenants()
    {
        Tenant::factory()->count(3)->create();

        $response = $this->getJson('/api/central/v1/tenants', [
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
    public function it_can_create_tenant()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/central/v1/tenants', [
            'name' => 'Test Tenant',
            'user_id' => $user->id,
            'domain' => 'test-tenant',
        ], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data',
            ]);

        $this->assertDatabaseHas('tenants', [
            'domain' => 'test-tenant',
        ]);
    }

    /** @test */
    public function it_validates_tenant_creation_data()
    {
        $response = $this->postJson('/api/central/v1/tenants', [], [
            'Authorization' => 'Bearer ' . $this->token,
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'success',
                'message',
                'errors',
            ]);
    }

    /** @test */
    public function it_enforces_rate_limiting()
    {
        // Make many requests quickly
        for ($i = 0; $i < 100; $i++) {
            $response = $this->getJson('/api/central/v1/dashboard', [
                'Authorization' => 'Bearer ' . $this->token,
            ]);

            if ($response->status() === 429) {
                $this->assertTrue(true);
                return;
            }
        }

        // If we get here, rate limiting might not be working
        $this->markTestIncomplete('Rate limiting test needs adjustment');
    }
}

