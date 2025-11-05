<?php

namespace App\Jobs;

use App\Models\PageBuilder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class DeletePageBuilderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $widget_id,
        public ?int $tenant_id = null,
        public ?int $page_id = null,
        public ?string $location = null
    ) {
        // Set queue name
        $this->onQueue('pagebuilder');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Initialize tenant context if tenant_id is provided
            if ($this->tenant_id) {
                $tenant = \App\Models\Tenant::find($this->tenant_id);
                if ($tenant) {
                    tenancy()->initialize($tenant);
                }
            }

            // Find widget
            $widget = PageBuilder::find($this->widget_id);

            if (!$widget) {
                Log::warning('PageBuilder widget not found for deletion', [
                    'widget_id' => $this->widget_id,
                    'tenant_id' => $this->tenant_id,
                ]);
                return;
            }

            // Store data for cache cleanup
            $location = $this->location ?? $widget->addon_location;
            $page_id = $this->page_id ?? $widget->addon_page_id;

            // Delete widget from database
            $widget->delete();

            // Clear cache using TenantCacheServiceProvider helper
            $this->clearCache($this->tenant_id, $location, $page_id);

            Log::info('PageBuilder widget deleted from database', [
                'widget_id' => $this->widget_id,
                'tenant_id' => $this->tenant_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to delete PageBuilder widget from database', [
                'widget_id' => $this->widget_id,
                'tenant_id' => $this->tenant_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // Re-throw to trigger retry
            throw $e;
        } finally {
            // Clean up tenant context
            if ($this->tenant_id && tenancy()->initialized) {
                tenancy()->end();
            }
        }
    }

    /**
     * Clear cache after deletion
     */
    protected function clearCache(?int $tenant_id, ?string $location, ?int $page_id): void
    {
        if ($tenant_id) {
            // Clear location-specific cache
            if ($location === 'header') {
                Cache::forget('pagebuilder_header_exists_' . $tenant_id);
                Cache::forget('pagebuilder_header_content_' . $tenant_id);
            } elseif ($location === 'footer') {
                Cache::forget('pagebuilder_footer_exists_' . $tenant_id);
                Cache::forget('pagebuilder_footer_content_' . $tenant_id);
            }

            // Clear page-specific cache
            if ($page_id) {
                Cache::forget('page_id-' . $page_id);
            }

            // Clear widget settings cache
            Cache::forget('widget_settings_cache' . $this->widget_id);

            // Use TenantCacheServiceProvider if available
            if (class_exists(\App\Providers\TenantCacheServiceProvider::class)) {
                $cacheKey = \App\Providers\TenantCacheServiceProvider::getTenantCacheKey(
                    "pagebuilder_widget_{$this->widget_id}"
                );
                Cache::forget($cacheKey);
            }
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('PageBuilder delete job failed permanently', [
            'widget_id' => $this->widget_id,
            'tenant_id' => $this->tenant_id,
            'error' => $exception->getMessage(),
        ]);
    }
}

