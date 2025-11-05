<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\PageBuilder;
use App\Jobs\SavePageBuilderJob;
use App\Jobs\DeletePageBuilderJob;
use App\Providers\TenantCacheServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * PageBuilder Cache and Queue Tests
 * 
 * Tests for PageBuilder Redis caching and Queue job functionality
 */
class PageBuilderCacheTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure Redis is available
        if (!extension_loaded('redis')) {
            $this->markTestSkipped('Redis extension is not loaded');
        }

        // Clear cache before tests
        try {
            Cache::store('redis')->flush();
        } catch (\Exception $e) {
            $this->markTestSkipped('Redis is not available: ' . $e->getMessage());
        }
    }

    /** @test */
    public function it_caches_pagebuilder_widget_settings_in_redis()
    {
        $widget = PageBuilder::create([
            'addon_name' => 'TestWidget',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Test Title', 'content' => 'Test Content']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        // Get cached settings
        $cached = PageBuilder::getCachedSettings($widget->id);

        $this->assertNotNull($cached);
        $this->assertEquals('Test Title', $cached['title']);
        $this->assertEquals('Test Content', $cached['content']);

        // Verify it's in Redis
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
            "pagebuilder_widget_{$widget->id}"
        );
        $redisValue = Cache::store('redis')->get($cacheKey);
        
        $this->assertNotNull($redisValue);
        $this->assertEquals('Test Title', $redisValue['title']);
    }

    /** @test */
    public function it_falls_back_to_database_when_cache_misses()
    {
        $widget = PageBuilder::create([
            'addon_name' => 'TestWidget',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Database Title']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        // Clear cache
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
            "pagebuilder_widget_{$widget->id}"
        );
        Cache::store('redis')->forget($cacheKey);
        Cache::forget('widget_settings_cache' . $widget->id);

        // Get settings (should fallback to database)
        $settings = PageBuilder::getCachedSettings($widget->id);

        $this->assertNotNull($settings);
        $this->assertEquals('Database Title', $settings['title']);

        // Should also cache it for next time
        $cached = Cache::store('redis')->get($cacheKey);
        $this->assertNotNull($cached);
    }

    /** @test */
    public function it_isolates_cache_between_tenants()
    {
        $tenant1 = $this->createTenant('tenant1');
        $tenant2 = $this->createTenant('tenant2');

        // Create widget for tenant 1
        tenancy()->initialize($tenant1);
        $widget1 = PageBuilder::create([
            'addon_name' => 'Widget1',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Tenant 1 Widget']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        $cacheKey1 = TenantCacheServiceProvider::getTenantCacheKey(
            "pagebuilder_widget_{$widget1->id}"
        );
        $settings1 = PageBuilder::getCachedSettings($widget1->id);
        tenancy()->end();

        // Create widget for tenant 2
        tenancy()->initialize($tenant2);
        $widget2 = PageBuilder::create([
            'addon_name' => 'Widget2',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Tenant 2 Widget']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        $cacheKey2 = TenantCacheServiceProvider::getTenantCacheKey(
            "pagebuilder_widget_{$widget2->id}"
        );
        $settings2 = PageBuilder::getCachedSettings($widget2->id);
        tenancy()->end();

        // Verify cache keys are different
        $this->assertNotEquals($cacheKey1, $cacheKey2);
        $this->assertStringContainsString('tenant_1', $cacheKey1);
        $this->assertStringContainsString('tenant_2', $cacheKey2);

        // Verify settings are isolated
        $this->assertEquals('Tenant 1 Widget', $settings1['title']);
        $this->assertEquals('Tenant 2 Widget', $settings2['title']);
    }

    /** @test */
    public function it_dispatches_save_job_to_queue()
    {
        Queue::fake();

        $widget = PageBuilder::create([
            'addon_name' => 'TestWidget',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Test']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        $job_data = [
            'title' => 'Updated Title',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_name' => 'TestWidget',
            'addon_namespace' => 'Test\\Namespace',
        ];

        SavePageBuilderJob::dispatch($widget->id, $job_data, null);

        Queue::assertPushed(SavePageBuilderJob::class, function ($job) use ($widget) {
            return $job->widget_id === $widget->id;
        });
    }

    /** @test */
    public function it_dispatches_delete_job_to_queue()
    {
        Queue::fake();

        $widget = PageBuilder::create([
            'addon_name' => 'TestWidget',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Test']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        DeletePageBuilderJob::dispatch($widget->id, null, null, 'content');

        Queue::assertPushed(DeletePageBuilderJob::class, function ($job) use ($widget) {
            return $job->widget_id === $widget->id;
        });
    }

    /** @test */
    public function it_clears_cache_when_widget_is_deleted()
    {
        $widget = PageBuilder::create([
            'addon_name' => 'TestWidget',
            'addon_type' => 'common',
            'addon_location' => 'content',
            'addon_settings' => json_encode(['title' => 'Test']),
            'addon_namespace' => 'Test\\Namespace',
        ]);

        // Cache the widget
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
            "pagebuilder_widget_{$widget->id}"
        );
        Cache::store('redis')->put($cacheKey, ['title' => 'Test'], 3600);

        // Verify it's cached
        $this->assertNotNull(Cache::store('redis')->get($cacheKey));

        // Delete widget
        $widget->delete();

        // Cache should be cleared
        $this->assertNull(Cache::store('redis')->get($cacheKey));
    }

    /**
     * Helper: Create a test tenant
     */
    protected function createTenant(string $id): object
    {
        return (object) [
            'id' => (int) filter_var($id, FILTER_SANITIZE_NUMBER_INT) ?: 1,
            'domain' => $id . '.test.com',
        ];
    }
}

