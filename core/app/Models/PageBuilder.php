<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PageBuilder extends Model
{
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
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        
        // Clear cache based on location
        if ($pageBuilder->addon_location === 'header') {
            Cache::forget('pagebuilder_header_exists_' . $tenant_id);
            Cache::forget('pagebuilder_header_content_' . $tenant_id);
        } elseif ($pageBuilder->addon_location === 'footer') {
            Cache::forget('pagebuilder_footer_exists_' . $tenant_id);
            Cache::forget('pagebuilder_footer_content_' . $tenant_id);
        }

        // Also clear page-specific cache if exists
        if (!empty($pageBuilder->addon_page_id)) {
            Cache::forget('page_id-' . $pageBuilder->addon_page_id);
        }
    }
}
