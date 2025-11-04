<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Tenant Cache Isolation Test
 * 
 * CRITICAL: These tests ensure that cache data is completely isolated
 * between tenants to prevent data leakage.
 */
class TenantCacheIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure Redis is available
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('Redis extension is not loaded');
        }

        // Clear all Redis data before tests
        try {
            Redis::connection('cache')->flushdb();
        } catch (\Exception $e) {
            $this->markTestSkipped('Redis is not available: ' . $e->getMessage());
        }
    }

    /** @test */
    public function it_isolates_cache_between_tenants()
    {
        // Create two tenants
        $tenant1 = $this->createTenant('tenant1');
        $tenant2 = $this->createTenant('tenant2');

        // Store data for tenant 1
        tenancy()->initialize($tenant1);
        Cache::put('test_key', 'tenant1_value', 3600);
        $tenant1Value = Cache::get('test_key');
        tenancy()->end();

        // Store data for tenant 2
        tenancy()->initialize($tenant2);
        Cache::put('test_key', 'tenant2_value', 3600);
        $tenant2Value = Cache::get('test_key');
        tenancy()->end();

        // Verify tenant 1 data
        tenancy()->initialize($tenant1);
        $verifyTenant1 = Cache::get('test_key');
        tenancy()->end();

        // Verify tenant 2 data
        tenancy()->initialize($tenant2);
        $verifyTenant2 = Cache::get('test_key');
        tenancy()->end();

        // Assertions
        $this->assertEquals('tenant1_value', $tenant1Value);
        $this->assertEquals('tenant2_value', $tenant2Value);
        $this->assertEquals('tenant1_value', $verifyTenant1);
        $this->assertEquals('tenant2_value', $verifyTenant2);
        $this->assertNotEquals($verifyTenant1, $verifyTenant2);
    }

    /** @test */
    public function it_uses_different_cache_prefixes_for_different_tenants()
    {
        $tenant1 = $this->createTenant('tenant1');
        $tenant2 = $this->createTenant('tenant2');

        // Get prefix for tenant 1
        tenancy()->initialize($tenant1);
        $prefix1 = config('cache.prefix');
        tenancy()->end();

        // Get prefix for tenant 2
        tenancy()->initialize($tenant2);
        $prefix2 = config('cache.prefix');
        tenancy()->end();

        // Get prefix for central
        $prefixCentral = config('cache.prefix');

        // Assertions
        $this->assertStringContainsString('tenant_', $prefix1);
        $this->assertStringContainsString('tenant_', $prefix2);
        $this->assertNotEquals($prefix1, $prefix2);
        $this->assertEquals('central', $prefixCentral);
    }

    /** @test */
    public function it_can_flush_tenant_cache_without_affecting_others()
    {
        $tenant1 = $this->createTenant('tenant1');
        $tenant2 = $this->createTenant('tenant2');

        // Store data for both tenants
        tenancy()->initialize($tenant1);
        Cache::put('key1', 'value1', 3600);
        Cache::put('key2', 'value2', 3600);
        tenancy()->end();

        tenancy()->initialize($tenant2);
        Cache::put('key1', 'value1_t2', 3600);
        Cache::put('key2', 'value2_t2', 3600);
        tenancy()->end();

        // Flush tenant 1 cache
        tenancy()->initialize($tenant1);
        app(TenantCacheServiceProvider::class)::flushTenantCache($tenant1->id);
        tenancy()->end();

        // Verify tenant 1 cache is empty
        tenancy()->initialize($tenant1);
        $this->assertNull(Cache::get('key1'));
        $this->assertNull(Cache::get('key2'));
        tenancy()->end();

        // Verify tenant 2 cache is intact
        tenancy()->initialize($tenant2);
        $this->assertEquals('value1_t2', Cache::get('key1'));
        $this->assertEquals('value2_t2', Cache::get('key2'));
        tenancy()->end();
    }

    /** @test */
    public function it_supports_cache_tags_with_tenant_isolation()
    {
        if (!config('cache-tenancy.enable_tags', true)) {
            $this->markTestSkipped('Cache tags are not enabled');
        }

        $tenant1 = $this->createTenant('tenant1');
        $tenant2 = $this->createTenant('tenant2');

        // Store tagged data for tenant 1
        tenancy()->initialize($tenant1);
        Cache::tags(['products'])->put('product_1', 'Product 1 Tenant 1', 3600);
        Cache::tags(['orders'])->put('order_1', 'Order 1 Tenant 1', 3600);
        tenancy()->end();

        // Store tagged data for tenant 2
        tenancy()->initialize($tenant2);
        Cache::tags(['products'])->put('product_1', 'Product 1 Tenant 2', 3600);
        Cache::tags(['orders'])->put('order_1', 'Order 1 Tenant 2', 3600);
        tenancy()->end();

        // Flush products tag for tenant 1
        tenancy()->initialize($tenant1);
        Cache::tags(['products'])->flush();
        tenancy()->end();

        // Verify tenant 1 products are flushed but orders remain
        tenancy()->initialize($tenant1);
        $this->assertNull(Cache::tags(['products'])->get('product_1'));
        $this->assertEquals('Order 1 Tenant 1', Cache::tags(['orders'])->get('order_1'));
        tenancy()->end();

        // Verify tenant 2 products are intact
        tenancy()->initialize($tenant2);
        $this->assertEquals('Product 1 Tenant 2', Cache::tags(['products'])->get('product_1'));
        $this->assertEquals('Order 1 Tenant 2', Cache::tags(['orders'])->get('order_1'));
        tenancy()->end();
    }

    /** @test */
    public function it_prevents_cache_key_collisions_between_tenants()
    {
        $tenant1 = $this->createTenant('tenant1');
        $tenant2 = $this->createTenant('tenant2');

        $collisionCount = 0;

        // Try 100 random keys
        for ($i = 0; $i < 100; $i++) {
            $key = 'key_' . $i;

            tenancy()->initialize($tenant1);
            Cache::put($key, 'tenant1_' . $i, 3600);
            tenancy()->end();

            tenancy()->initialize($tenant2);
            Cache::put($key, 'tenant2_' . $i, 3600);
            $value = Cache::get($key);
            tenancy()->end();

            // Check if tenant2 got tenant1's value (collision)
            if ($value === 'tenant1_' . $i) {
                $collisionCount++;
            }
        }

        // Assert zero collisions
        $this->assertEquals(0, $collisionCount, "Found {$collisionCount} cache key collisions between tenants!");
    }

    /** @test */
    public function it_uses_tenant_cache_macros()
    {
        $tenant = $this->createTenant('tenant1');

        tenancy()->initialize($tenant);

        // Test tenantTags macro
        Cache::tenantTags(['custom'])->put('test_key', 'test_value', 3600);
        $value = Cache::tenantTags(['custom'])->get('test_key');

        $this->assertEquals('test_value', $value);

        // Test tenantResource macro
        Cache::tenantResource('products')->put('product_123', 'Product Data', 3600);
        $product = Cache::tenantResource('products')->get('product_123');

        $this->assertEquals('Product Data', $product);

        tenancy()->end();
    }

    /** @test */
    public function it_handles_cache_ttl_correctly()
    {
        $tenant = $this->createTenant('tenant1');

        tenancy()->initialize($tenant);

        // Store with 1 second TTL
        Cache::put('short_lived', 'value', 1);
        $this->assertEquals('value', Cache::get('short_lived'));

        // Wait 2 seconds
        sleep(2);

        // Should be null after expiration
        $this->assertNull(Cache::get('short_lived'));

        tenancy()->end();
    }

    /** @test */
    public function it_can_get_cache_statistics()
    {
        $tenant = $this->createTenant('tenant1');

        tenancy()->initialize($tenant);

        // Generate some cache activity
        Cache::put('key1', 'value1', 3600);
        Cache::put('key2', 'value2', 3600);
        Cache::get('key1'); // hit
        Cache::get('key2'); // hit
        Cache::get('nonexistent'); // miss

        $stats = app(TenantCacheServiceProvider::class)::getCacheStats();

        $this->assertArrayHasKey('keyspace_hits', $stats);
        $this->assertArrayHasKey('keyspace_misses', $stats);
        $this->assertArrayHasKey('hit_rate', $stats);
        $this->assertArrayHasKey('memory_used', $stats);

        tenancy()->end();
    }

    /**
     * Helper: Create a test tenant
     */
    protected function createTenant(string $id): object
    {
        return (object) [
            'id' => $id,
            'domain' => $id . '.test.com',
        ];
    }
}

