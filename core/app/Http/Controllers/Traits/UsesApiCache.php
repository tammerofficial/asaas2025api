<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait UsesApiCache
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
                try {
                    $redisStore->get('test_connection');
                    return $redisStore;
                } catch (\Exception $e) {
                    // Redis connection failed, fallback
                }
            }
        } catch (\Exception $e) {
            // Redis not available, fallback to default
        }
        
        // Fallback to default cache driver
        return Cache::store(config('cache.default', 'file'));
    }

    /**
     * Generate cache key for endpoint
     */
    protected function getCacheKey(string $endpoint, array $params = []): string
    {
        $paramsHash = '';
        if (!empty($params)) {
            // Sort params to ensure consistent keys
            ksort($params);
            $paramsHash = ':' . md5(json_encode($params));
        }
        
        return "api:v1:{$endpoint}{$paramsHash}";
    }

    /**
     * Cache helper method with tags support
     */
    protected function remember(string $key, int $seconds, callable $callback, array $tags = [])
    {
        $cache = $this->cacheStore();
        
        try {
            // Use tagged cache if tags provided and Redis is available
            if (!empty($tags) && $cache->getStore() instanceof \Illuminate\Cache\RedisStore) {
                // Remove 'tag:' prefix if present
                $cleanTags = array_map(function($tag) {
                    return str_replace('tag:', '', $tag);
                }, $tags);
                
                return Cache::tags($cleanTags)->remember($key, $seconds, $callback);
            }
            
            // Fallback to regular cache
            return $cache->remember($key, $seconds, $callback);
        } catch (\Exception $e) {
            // If cache fails, execute callback directly
            Log::warning('Cache operation failed, executing callback directly', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return $callback();
        }
    }

    /**
     * Clear cache by tags
     */
    protected function clearCacheTags(array $tags): void
    {
        try {
            $cache = $this->cacheStore();
            
            // Use tagged cache if Redis is available
            if (!empty($tags) && $cache->getStore() instanceof \Illuminate\Cache\RedisStore) {
                // Remove 'tag:' prefix if present
                $cleanTags = array_map(function($tag) {
                    return str_replace('tag:', '', $tag);
                }, $tags);
                
                Cache::tags($cleanTags)->flush();
                return;
            }
            
            // Fallback: clear all cache (not ideal but works)
            Log::warning('Cache tags not available, clearing all cache', ['tags' => $tags]);
            Cache::flush();
        } catch (\Exception $e) {
            Log::warning('Failed to clear cache tags', [
                'tags' => $tags,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get TTL for stats endpoints
     */
    protected function getStatsTtl(): int
    {
        return 120; // 2 minutes
    }

    /**
     * Get TTL for lists endpoints
     */
    protected function getListsTtl(): int
    {
        return 600; // 10 minutes
    }

    /**
     * Get TTL for details endpoints
     */
    protected function getDetailsTtl(): int
    {
        return 1800; // 30 minutes
    }

    /**
     * Get TTL for chart data (longer because of calculations)
     */
    protected function getChartDataTtl(): int
    {
        return 180; // 3 minutes
    }

    /**
     * Get TTL for recent orders (shorter because changes frequently)
     */
    protected function getRecentOrdersTtl(): int
    {
        return 60; // 1 minute
    }

    /**
     * Get TTL for orders/payments (shorter because changes frequently)
     */
    protected function getOrdersTtl(): int
    {
        return 300; // 5 minutes
    }

    /**
     * Get TTL for support tickets (shorter because changes frequently)
     */
    protected function getSupportTicketsTtl(): int
    {
        return 300; // 5 minutes
    }

    /**
     * Get TTL for single order details (may change)
     */
    protected function getOrderDetailsTtl(): int
    {
        return 900; // 15 minutes
    }

    /**
     * Get TTL for support ticket details (may change)
     */
    protected function getSupportTicketDetailsTtl(): int
    {
        return 900; // 15 minutes
    }
}

