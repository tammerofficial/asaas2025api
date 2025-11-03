<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

abstract class BaseApiController extends Controller
{
    /**
     * Get cache store (Redis or Octane if available)
     */
    protected function cacheStore()
    {
        try {
            // Try Redis first if available
            if (config('cache.stores.redis') && config('cache.default') === 'redis') {
                $redisStore = Cache::store('redis');
                // Test Redis connection
                $redisStore->get('test_connection');
                return $redisStore;
            }
        } catch (\Exception $e) {
            // Redis not available, fallback to default
        }
        
        // Fallback to default cache driver
        return Cache::store(config('cache.default', 'file'));
    }

    /**
     * Cache helper method
     */
    protected function remember(string $key, int $seconds, callable $callback)
    {
        return $this->cacheStore()->remember($key, $seconds, $callback);
    }

    /**
     * Clear cache by pattern
     */
    protected function clearCache(string $pattern): void
    {
        $cache = $this->cacheStore();
        
        // If Redis, clear by pattern
        try {
            if ($cache->getStore() instanceof \Illuminate\Cache\RedisStore) {
                $redis = $cache->getStore()->connection();
                
                // Use SCAN instead of KEYS for better performance in production
                $cursor = 0;
                $keys = [];
                
                do {
                    $result = $redis->scan($cursor, ['match' => $pattern, 'count' => 100]);
                    $cursor = $result[0];
                    $keys = array_merge($keys, $result[1]);
                } while ($cursor != 0);
                
                if (!empty($keys)) {
                    $redis->del($keys);
                }
            } else {
                // Fallback: clear specific key (remove wildcard)
                $key = str_replace('*', '', $pattern);
                $cache->forget($key);
            }
        } catch (\Exception $e) {
            // Silently fail if cache clearing fails
            \Log::warning('Failed to clear cache', ['pattern' => $pattern, 'error' => $e->getMessage()]);
        }
    }
}

