<?php

namespace App\Models;

use App\Providers\TenantCacheServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageBuilder extends Model
{
    // Stancl Tenancy automatically switches the default connection to tenant DB
    // No need to specify connection - it's handled by DatabaseTenancyBootstrapper

    protected $table = 'page_builders';
    protected $fillable = [
      'addon_name',
      'addon_type',
      'addon_location',
      'addon_order',
      'addon_page_id',
      'addon_page_type',
      'addon_settings',
      'addon_namespace',
    ];

    /**
     * Clear cache when PageBuilder is saved, updated, or deleted
     */
    protected static function boot()
    {
        parent::boot();

        // Clear cache when saved (created or updated)
        static::saved(function ($pageBuilder) {
            static::clearPageBuilderCache($pageBuilder);
        });

        // Clear cache when deleted
        static::deleted(function ($pageBuilder) {
            static::clearPageBuilderCache($pageBuilder);
        });
    }

    /**
     * Clear PageBuilder cache for header/footer locations
     */
    protected static function clearPageBuilderCache($pageBuilder)
    {
        $tenant_id = !is_null(tenant()) ? tenant()->id : null;
        
        try {
            // Clear widget-specific cache using TenantCacheServiceProvider
            if (class_exists(TenantCacheServiceProvider::class)) {
                $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                    "pagebuilder_widget_{$pageBuilder->id}"
                );
                Cache::store('redis')->forget($cacheKey);
            }

            // Clear legacy cache format
            Cache::forget('widget_settings_cache' . $pageBuilder->id);
            
            // Clear cache based on location
            if ($tenant_id) {
                if ($pageBuilder->addon_location === 'header') {
                    Cache::forget('pagebuilder_header_exists_' . $tenant_id);
                    Cache::forget('pagebuilder_header_content_' . $tenant_id);
                } elseif ($pageBuilder->addon_location === 'footer') {
                    Cache::forget('pagebuilder_footer_exists_' . $tenant_id);
                    Cache::forget('pagebuilder_footer_content_' . $tenant_id);
                }
            }

            // Also clear page-specific cache if exists
            if (!empty($pageBuilder->addon_page_id)) {
                Cache::forget('page_id-' . $pageBuilder->addon_page_id);
            }
        } catch (\Exception $e) {
            // Log error but don't fail
            \Log::warning('Failed to clear PageBuilder cache', [
                'widget_id' => $pageBuilder->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Get widget settings with Redis cache fallback to database
     */
    public static function getCachedSettings(int $widget_id): ?array
    {
        try {
            // Try to get from Redis cache first
            if (class_exists(TenantCacheServiceProvider::class)) {
                $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                    "pagebuilder_widget_{$widget_id}"
                );

                $cached = Cache::store('redis')->get($cacheKey);
                if ($cached !== null) {
                    return $cached;
                }
            }

            // Fallback to legacy cache format
            $legacyCache = Cache::get('widget_settings_cache' . $widget_id);
            if ($legacyCache !== null) {
                return $legacyCache;
            }

            // Fallback to database
            $widget = static::find($widget_id);
            if ($widget && $widget->addon_settings) {
                $settings = json_decode($widget->addon_settings, true);
                
                // Cache the result for next time
                if (class_exists(TenantCacheServiceProvider::class)) {
                    $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                        "pagebuilder_widget_{$widget_id}"
                    );
                    Cache::store('redis')->put(
                        $cacheKey,
                        $settings,
                        config('cache-tenancy.ttl.default', 3600)
                    );
                }
                
                return $settings;
            }

            return null;
        } catch (\Exception $e) {
            // If Redis fails, fallback to database
            \Log::warning('Failed to get cached PageBuilder settings, falling back to database', [
                'widget_id' => $widget_id,
                'error' => $e->getMessage(),
            ]);

            $widget = static::find($widget_id);
            if ($widget && $widget->addon_settings) {
                return json_decode($widget->addon_settings, true);
            }

            return null;
        }
    }
}
