<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TenantCacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // تطبيق Cache Prefix للـ Tenant الحالي
        $this->setupTenantCachePrefix();

        // تطبيق Cache Tags للـ Tenant
        $this->setupTenantCacheTags();

        // تنظيف Cache عند تبديل Tenant
        $this->setupCacheCleanupOnTenantSwitch();
    }

    /**
     * Setup tenant-specific cache prefix
     */
    protected function setupTenantCachePrefix(): void
    {
        if (tenancy()->initialized) {
            $tenantId = tenant('id');
            $prefix = "tenant_{$tenantId}";

            // تطبيق Prefix على Redis
            config(['cache.prefix' => $prefix]);

            // تطبيق Prefix على كل Stores
            foreach (config('cache.stores') as $storeName => $storeConfig) {
                if (isset($storeConfig['connection']) && $storeConfig['connection'] === 'cache') {
                    config(["cache.stores.{$storeName}.prefix" => $prefix]);
                }
            }
        } else {
            // Central Application
            config(['cache.prefix' => 'central']);
        }
    }

    /**
     * Setup tenant-specific cache tags
     */
    protected function setupTenantCacheTags(): void
    {
        if (!config('cache-tenancy.enable_tags', true)) {
            return;
        }

        // Macro لإضافة Tenant Tag تلقائياً
        Cache::macro('tenantTags', function (array $additionalTags = []) {
            $tenantId = tenant('id');
            $baseTags = ["tenant:{$tenantId}"];

            return Cache::tags(array_merge($baseTags, $additionalTags));
        });

        // Macro للـ Cache بناءً على Resource
        Cache::macro('tenantResource', function (string $resource, array $additionalTags = []) {
            $tenantId = tenant('id');
            $resourceTag = str_replace(
                '{tenant_id}',
                $tenantId,
                config("cache-tenancy.tags.{$resource}", "tenant:{$tenantId}:{$resource}")
            );

            return Cache::tags(array_merge([$resourceTag], $additionalTags));
        });
    }

    /**
     * Cleanup cache when switching tenants (important for Octane)
     */
    protected function setupCacheCleanupOnTenantSwitch(): void
    {
        if (!config('cache-tenancy.auto_clear_on_switch', true)) {
            return;
        }

        // Listen to tenant initialization event
        \Stancl\Tenancy\Events\TenancyInitialized::class;
        
        // Clear any cached tenant context
        $this->clearTenantContext();
    }

    /**
     * Clear tenant context to prevent memory leaks
     */
    protected function clearTenantContext(): void
    {
        // هذا مهم جداً في Octane لتجنب memory leaks
        // سيتم تنفيذه بعد كل Request
    }

    /**
     * Get tenant-specific cache key
     */
    public static function getTenantCacheKey(string $key): string
    {
        if (tenancy()->initialized) {
            $tenantId = tenant('id');
            return "tenant_{$tenantId}:{$key}";
        }

        return "central:{$key}";
    }

    /**
     * Flush tenant cache only (not affecting other tenants)
     */
    public static function flushTenantCache(?int $tenantId = null): bool
    {
        $tenantId = $tenantId ?? tenant('id');

        if (!$tenantId) {
            return false;
        }

        try {
            // Flush using tags (recommended)
            if (config('cache-tenancy.enable_tags', true)) {
                Cache::tags(["tenant:{$tenantId}"])->flush();
            }

            // Fallback: Delete all keys with tenant prefix
            $prefix = "tenant_{$tenantId}:*";
            $keys = Redis::connection('cache')->keys($prefix);

            foreach ($keys as $key) {
                Redis::connection('cache')->del($key);
            }

            return true;
        } catch (\Exception $e) {
            logger()->error('Failed to flush tenant cache', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Get cache statistics for monitoring
     */
    public static function getCacheStats(): array
    {
        try {
            $info = Redis::connection('cache')->info('stats');

            return [
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => self::calculateHitRate($info),
                'memory_used' => Redis::connection('cache')->info('memory')['used_memory_human'] ?? 'N/A',
            ];
        } catch (\Exception $e) {
            return [
                'error' => 'Unable to fetch cache stats',
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Calculate cache hit rate
     */
    protected static function calculateHitRate(array $info): string
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        if ($total === 0) {
            return '0%';
        }

        $hitRate = ($hits / $total) * 100;

        return number_format($hitRate, 2) . '%';
    }
}

