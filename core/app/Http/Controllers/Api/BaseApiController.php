<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UsesApiCache;

abstract class BaseApiController extends Controller
{
    use UsesApiCache;

    /**
     * Clear cache by pattern (legacy method for backward compatibility)
     * 
     * @deprecated Use clearCacheTags() instead for better performance
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

