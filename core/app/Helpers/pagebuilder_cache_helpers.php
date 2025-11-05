<?php

if (!function_exists('pagebuilder_cache_remember')) {
    /**
     * Cache PageBuilder widget settings with fallback to database
     *
     * @param int $widget_id
     * @param callable|null $callback Optional callback to get data if not cached
     * @param int|null $ttl Cache TTL in seconds (default: 3600)
     * @return array|null
     */
    function pagebuilder_cache_remember(int $widget_id, ?callable $callback = null, ?int $ttl = null): ?array
    {
        try {
            $ttl = $ttl ?? config('cache-tenancy.ttl.default', 3600);

            // Try to get from Redis cache first
            if (class_exists(\App\Providers\TenantCacheServiceProvider::class)) {
                $cacheKey = \App\Providers\TenantCacheServiceProvider::getTenantCacheKey(
                    "pagebuilder_widget_{$widget_id}"
                );

                $cached = \Illuminate\Support\Facades\Cache::store('redis')->get($cacheKey);
                if ($cached !== null) {
                    return $cached;
                }
            }

            // Fallback to legacy cache format
            $legacyCache = \Illuminate\Support\Facades\Cache::get('widget_settings_cache' . $widget_id);
            if ($legacyCache !== null) {
                return $legacyCache;
            }

            // If callback provided, use it to get data
            if ($callback) {
                $data = $callback();
                if ($data !== null) {
                    // Cache the result
                    if (class_exists(\App\Providers\TenantCacheServiceProvider::class)) {
                        $cacheKey = \App\Providers\TenantCacheServiceProvider::getTenantCacheKey(
                            "pagebuilder_widget_{$widget_id}"
                        );
                        \Illuminate\Support\Facades\Cache::store('redis')->put($cacheKey, $data, $ttl);
                    }
                    \Illuminate\Support\Facades\Cache::put('widget_settings_cache' . $widget_id, $data, $ttl);
                    return $data;
                }
            }

            // Fallback to database
            $widget = \App\Models\PageBuilder::find($widget_id);
            if ($widget && $widget->addon_settings) {
                $settings = json_decode($widget->addon_settings, true);

                // Cache the result
                if (class_exists(\App\Providers\TenantCacheServiceProvider::class)) {
                    $cacheKey = \App\Providers\TenantCacheServiceProvider::getTenantCacheKey(
                        "pagebuilder_widget_{$widget_id}"
                    );
                    \Illuminate\Support\Facades\Cache::store('redis')->put($cacheKey, $settings, $ttl);
                }
                \Illuminate\Support\Facades\Cache::put('widget_settings_cache' . $widget_id, $settings, $ttl);

                return $settings;
            }

            return null;
        } catch (\Exception $e) {
            \Log::warning('Failed to cache PageBuilder widget, falling back to database', [
                'widget_id' => $widget_id,
                'error' => $e->getMessage(),
            ]);

            // Fallback to database
            $widget = \App\Models\PageBuilder::find($widget_id);
            if ($widget && $widget->addon_settings) {
                return json_decode($widget->addon_settings, true);
            }

            return null;
        }
    }
}

if (!function_exists('pagebuilder_cache_forget_pattern')) {
    /**
     * Clear PageBuilder cache by pattern
     *
     * @param string $pattern Cache key pattern (e.g., 'pagebuilder_widget_*')
     * @param int|null $tenant_id Optional tenant ID for isolation
     * @return int Number of keys deleted
     */
    function pagebuilder_cache_forget_pattern(string $pattern, ?int $tenant_id = null): int
    {
        try {
            $count = 0;

            // If using Redis, use SCAN for pattern matching
            if (config('cache.default') === 'redis' || config('cache.stores.redis')) {
                try {
                    $redis = \Illuminate\Support\Facades\Cache::store('redis')->getStore()->connection();

                    // Build pattern with tenant prefix if provided
                    if ($tenant_id && class_exists(\App\Providers\TenantCacheServiceProvider::class)) {
                        $prefix = "tenant_{$tenant_id}:";
                        $pattern = $prefix . $pattern;
                    }

                    // Use SCAN instead of KEYS for better performance
                    $cursor = 0;
                    $keys = [];

                    do {
                        $result = $redis->scan($cursor, ['match' => $pattern, 'count' => 100]);
                        $cursor = $result[0];
                        $keys = array_merge($keys, $result[1]);
                    } while ($cursor != 0);

                    if (!empty($keys)) {
                        $count = count($keys);
                        $redis->del($keys);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to clear cache by pattern using Redis', [
                        'pattern' => $pattern,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return $count;
        } catch (\Exception $e) {
            \Log::warning('Failed to clear cache by pattern', [
                'pattern' => $pattern,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }
}

if (!function_exists('pagebuilder_cache_warm')) {
    /**
     * Pre-cache PageBuilder widgets for better performance
     *
     * @param array|null $widget_ids Optional array of widget IDs to warm. If null, warms all widgets for current tenant
     * @param int|null $ttl Cache TTL in seconds
     * @return int Number of widgets cached
     */
    function pagebuilder_cache_warm(?array $widget_ids = null, ?int $ttl = null): int
    {
        try {
            $ttl = $ttl ?? config('cache-tenancy.ttl.default', 3600);
            $count = 0;

            // If widget IDs not provided, get all widgets for current tenant
            if ($widget_ids === null) {
                $tenant_id = !is_null(tenant()) ? tenant()->id : null;

                if ($tenant_id) {
                    $widgets = \App\Models\PageBuilder::where('addon_location', '!=', null)
                        ->get(['id', 'addon_settings']);
                } else {
                    $widgets = \App\Models\PageBuilder::all(['id', 'addon_settings']);
                }

                $widget_ids = $widgets->pluck('id')->toArray();
            }

            // Cache each widget
            foreach ($widget_ids as $widget_id) {
                try {
                    $widget = \App\Models\PageBuilder::find($widget_id);
                    if ($widget && $widget->addon_settings) {
                        $settings = json_decode($widget->addon_settings, true);

                        if ($settings) {
                            // Cache using TenantCacheServiceProvider
                            if (class_exists(\App\Providers\TenantCacheServiceProvider::class)) {
                                $cacheKey = \App\Providers\TenantCacheServiceProvider::getTenantCacheKey(
                                    "pagebuilder_widget_{$widget_id}"
                                );
                                \Illuminate\Support\Facades\Cache::store('redis')->put(
                                    $cacheKey,
                                    $settings,
                                    $ttl
                                );
                            }

                            // Also cache in legacy format
                            \Illuminate\Support\Facades\Cache::put(
                                'widget_settings_cache' . $widget_id,
                                $settings,
                                $ttl
                            );

                            $count++;
                        }
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to warm cache for widget', [
                        'widget_id' => $widget_id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }

            return $count;
        } catch (\Exception $e) {
            \Log::error('Failed to warm PageBuilder cache', [
                'error' => $e->getMessage(),
            ]);

            return 0;
        }
    }
}

