<?php

use Illuminate\Support\Facades\Cache;
use App\Providers\TenantCacheServiceProvider;

if (!function_exists('tenant_cache')) {
    /**
     * Get/Set tenant-specific cache with automatic isolation
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $ttl Time in seconds, null for default
     * @return mixed
     */
    function tenant_cache(string $key, $value = null, ?int $ttl = null)
    {
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey($key);

        if ($value === null) {
            // Get
            return Cache::get($cacheKey);
        }

        // Set
        $ttl = $ttl ?? config('cache-tenancy.ttl.default', 3600);
        Cache::put($cacheKey, $value, $ttl);

        return $value;
    }
}

if (!function_exists('tenant_cache_remember')) {
    /**
     * Get from cache or execute callback and store result
     *
     * @param string $key
     * @param \Closure $callback
     * @param int|null $ttl
     * @return mixed
     */
    function tenant_cache_remember(string $key, \Closure $callback, ?int $ttl = null)
    {
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey($key);
        $ttl = $ttl ?? config('cache-tenancy.ttl.default', 3600);

        return Cache::remember($cacheKey, $ttl, $callback);
    }
}

if (!function_exists('tenant_cache_forever')) {
    /**
     * Store in cache forever (until manually deleted)
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    function tenant_cache_forever(string $key, $value)
    {
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey($key);
        Cache::forever($cacheKey, $value);

        return $value;
    }
}

if (!function_exists('tenant_cache_forget')) {
    /**
     * Remove item from tenant cache
     *
     * @param string $key
     * @return bool
     */
    function tenant_cache_forget(string $key): bool
    {
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey($key);

        return Cache::forget($cacheKey);
    }
}

if (!function_exists('tenant_cache_flush')) {
    /**
     * Flush all cache for current tenant
     *
     * @return bool
     */
    function tenant_cache_flush(): bool
    {
        if (!tenancy()->initialized) {
            return false;
        }

        return TenantCacheServiceProvider::flushTenantCache();
    }
}

if (!function_exists('tenant_cache_tags')) {
    /**
     * Cache with tenant-specific tags
     *
     * @param array $tags
     * @return \Illuminate\Cache\TaggedCache
     */
    function tenant_cache_tags(array $tags)
    {
        if (!tenancy()->initialized) {
            return Cache::tags($tags);
        }

        $tenantId = tenant('id');
        $tenantTags = array_map(function ($tag) use ($tenantId) {
            return "tenant:{$tenantId}:{$tag}";
        }, $tags);

        return Cache::tags($tenantTags);
    }
}

if (!function_exists('cache_query')) {
    /**
     * Cache a database query result
     *
     * @param string $key
     * @param \Closure $query
     * @param int|null $ttl
     * @return mixed
     */
    function cache_query(string $key, \Closure $query, ?int $ttl = null)
    {
        $ttl = $ttl ?? config('cache-tenancy.ttl.queries', 1800);

        return tenant_cache_remember("query:{$key}", $query, $ttl);
    }
}

if (!function_exists('cache_view')) {
    /**
     * Cache a view rendering result
     *
     * @param string $key
     * @param \Closure $viewCallback
     * @param int|null $ttl
     * @return mixed
     */
    function cache_view(string $key, \Closure $viewCallback, ?int $ttl = null)
    {
        $ttl = $ttl ?? config('cache-tenancy.ttl.views', 86400);

        return tenant_cache_remember("view:{$key}", $viewCallback, $ttl);
    }
}

if (!function_exists('cache_settings')) {
    /**
     * Cache settings with longer TTL
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    function cache_settings(string $key, $value = null)
    {
        $cacheKey = TenantCacheServiceProvider::getTenantCacheKey("settings:{$key}");
        $ttl = config('cache-tenancy.ttl.settings', 43200);

        if ($value === null) {
            return Cache::get($cacheKey);
        }

        Cache::put($cacheKey, $value, $ttl);

        return $value;
    }
}

if (!function_exists('cache_invalidate_pattern')) {
    /**
     * Invalidate cache keys matching a pattern
     *
     * @param string $pattern
     * @return int Number of keys deleted
     */
    function cache_invalidate_pattern(string $pattern): int
    {
        if (!tenancy()->initialized) {
            return 0;
        }

        try {
            $tenantId = tenant('id');
            $searchPattern = "cache:tenant_{$tenantId}:*{$pattern}*";

            $redis = \Illuminate\Support\Facades\Redis::connection('cache');
            $keys = $redis->keys($searchPattern);
            $deleted = 0;

            foreach ($keys as $key) {
                if ($redis->del($key)) {
                    $deleted++;
                }
            }

            return $deleted;
        } catch (\Exception $e) {
            logger()->error('Failed to invalidate cache pattern', [
                'pattern' => $pattern,
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }
}

if (!function_exists('cache_stats')) {
    /**
     * Get cache statistics
     *
     * @return array
     */
    function cache_stats(): array
    {
        return TenantCacheServiceProvider::getCacheStats();
    }
}

if (!function_exists('cache_warm')) {
    /**
     * Warm up cache with frequently accessed data
     *
     * @return void
     */
    function cache_warm(): void
    {
        if (!config('cache-tenancy.warming.enabled', true)) {
            return;
        }

        $keys = config('cache-tenancy.warming.keys', []);

        foreach ($keys as $key) {
            try {
                // This would call specific warming methods
                // You can customize this based on your needs
                match ($key) {
                    'settings' => cache_warm_settings(),
                    'permissions' => cache_warm_permissions(),
                    'roles' => cache_warm_roles(),
                    'menus' => cache_warm_menus(),
                    default => null,
                };
            } catch (\Exception $e) {
                logger()->warning("Failed to warm cache for: {$key}", [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }
}

if (!function_exists('cache_warm_settings')) {
    /**
     * Warm up settings cache
     *
     * @return void
     */
    function cache_warm_settings(): void
    {
        // Example: Load all settings into cache
        // Adjust based on your settings structure
        if (tenancy()->initialized && function_exists('get_static_option_central')) {
            tenant_cache_remember('all_settings', function () {
                // Load your settings here
                return [];
            }, config('cache-tenancy.ttl.settings', 43200));
        }
    }
}

if (!function_exists('cache_warm_permissions')) {
    /**
     * Warm up permissions cache
     *
     * @return void
     */
    function cache_warm_permissions(): void
    {
        if (tenancy()->initialized) {
            tenant_cache_remember('all_permissions', function () {
                return \Spatie\Permission\Models\Permission::all();
            }, 3600);
        }
    }
}

if (!function_exists('cache_warm_roles')) {
    /**
     * Warm up roles cache
     *
     * @return void
     */
    function cache_warm_roles(): void
    {
        if (tenancy()->initialized) {
            tenant_cache_remember('all_roles', function () {
                return \Spatie\Permission\Models\Role::with('permissions')->get();
            }, 3600);
        }
    }
}

if (!function_exists('cache_warm_menus')) {
    /**
     * Warm up menus cache
     *
     * @return void
     */
    function cache_warm_menus(): void
    {
        if (tenancy()->initialized) {
            tenant_cache_remember('navigation_menus', function () {
                // Load your menus here
                return [];
            }, 86400);
        }
    }
}

if (!function_exists('octane_cache_clear')) {
    /**
     * Clear Octane-specific cache
     * Use this after deployments when using Octane
     *
     * @return void
     */
    function octane_cache_clear(): void
    {
        if (app()->bound('octane')) {
            // Clear Octane internal cache
            try {
                \Illuminate\Support\Facades\Artisan::call('octane:reload');
            } catch (\Exception $e) {
                logger()->error('Failed to reload Octane', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Clear regular caches
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
    }
}

