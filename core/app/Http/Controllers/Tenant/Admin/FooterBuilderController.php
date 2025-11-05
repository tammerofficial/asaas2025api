<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\DeletePageBuilderJob;
use App\Jobs\SavePageBuilderJob;
use App\Models\PageBuilder;
use App\Providers\TenantCacheServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Plugins\PageBuilder\PageBuilderSetup;

class FooterBuilderController extends Controller
{
    /**
     * Show Footer Builder page
     */
    public function index()
    {
        return view('tenant.admin.footer-builder');
    }

    /**
     * Get admin panel addon markup
     */
    public function getAddonMarkup(Request $request)
    {
        $output = PageBuilderSetup::render_widgets_by_name_for_admin([
            'name' => $request->addon_class,
            'namespace' => base64_decode($request->addon_namespace),
            'type' => 'new',
            'location' => 'footer', // Force location to footer
            'after' => false,
            'before' => false,
        ]);
        return $output;
    }

    /**
     * Store new addon content
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
        ]);

        unset($request['_token']);
        $widget_content = (array) $request->all();

        // Create widget in database (needed for ID)
        $widget_id = PageBuilder::create([
            'addon_type' => $request->addon_type,
            'addon_location' => 'footer', // Force location to footer
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => null,
            'addon_order' => $request->addon_order ?? 0,
            'addon_page_type' => null,
            'addon_settings' => json_encode($widget_content),
        ])->id;

        // Get tenant ID
        $tenant_id = !is_null(tenant()) ? tenant()->id : null;

        // Cache widget settings in Redis immediately (fast response)
        $this->cacheWidgetSettings($widget_id, $widget_content, $tenant_id);

        // Invalidate preview cache
        $this->invalidatePreviewCache('footer', null, $tenant_id);

        // Dispatch job to update database in background (if needed for consistency)
        if (config('queue.default') !== 'sync') {
            $job_data = array_merge($widget_content, [
                'addon_type' => $request->addon_type,
                'addon_location' => 'footer',
                'addon_name' => $request->addon_name,
                'addon_namespace' => base64_decode($request->addon_namespace),
                'addon_page_id' => null,
                'addon_order' => $request->addon_order ?? 0,
                'addon_page_type' => null,
            ]);

            SavePageBuilderJob::dispatch($widget_id, $job_data, $tenant_id);
        }

        $data['id'] = $widget_id;
        $data['status'] = 'ok';
        $data['cached'] = true;
        return response()->json($data);
    }

    /**
     * Update addon content
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'addon_name' => 'required',
            'addon_namespace' => 'required',
            'addon_order' => 'nullable',
        ]);

        unset($request['_token']);
        $addon_content = (array) $request->all();

        $tenant_id = !is_null(tenant()) ? tenant()->id : null;

        // Cache widget settings in Redis immediately (fast response)
        $this->cacheWidgetSettings($request->id, $addon_content, $tenant_id);

        // Invalidate preview cache
        $this->invalidatePreviewCache('footer', null, $tenant_id);

        // Prepare data for database update
        $job_data = array_merge($addon_content, [
            'addon_type' => $request->addon_type,
            'addon_location' => 'footer',
            'addon_name' => $request->addon_name,
            'addon_namespace' => base64_decode($request->addon_namespace),
            'addon_page_id' => null,
            'addon_order' => $request->addon_order ?? 0,
            'addon_page_type' => null,
        ]);

        // Update database via queue (if not sync)
        if (config('queue.default') !== 'sync') {
            SavePageBuilderJob::dispatch($request->id, $job_data, $tenant_id);
        } else {
            // If sync queue, update immediately
            PageBuilder::findOrFail($request->id)->update([
                'addon_type' => $request->addon_type,
                'addon_location' => 'footer',
                'addon_name' => $request->addon_name,
                'addon_namespace' => base64_decode($request->addon_namespace),
                'addon_order' => $request->addon_order ?? 0,
                'addon_settings' => json_encode($addon_content),
            ]);
        }

        $data['status'] = 'ok';
        $data['cached'] = true;
        return response()->json($data);
    }

    /**
     * Delete addon
     */
    public function delete(Request $request)
    {
        $widget = PageBuilder::findOrFail($request->id);

        $tenant_id = !is_null(tenant()) ? tenant()->id : null;

        // Delete from cache immediately
        $this->clearWidgetCache($request->id, $tenant_id);
        $this->invalidatePreviewCache('footer', null, $tenant_id);

        // Delete from database via queue (if not sync)
        if (config('queue.default') !== 'sync') {
            DeletePageBuilderJob::dispatch($request->id, $tenant_id, null, 'footer');
        } else {
            // If sync queue, delete immediately
            $widget->delete();
        }

        return response()->json('ok');
    }

    /**
     * Update addon order
     */
    public function updateOrder(Request $request)
    {
        // Clear cache
        $tenant_id = !is_null(tenant()) ? tenant()->id : 0;
        Cache::forget('widget_settings_cache' . $request->id);
        Cache::forget('pagebuilder_footer_exists_' . $tenant_id);
        Cache::forget('pagebuilder_footer_content_' . $tenant_id);

        PageBuilder::findOrFail($request->id)->update(['addon_order' => $request->addon_order]);
        return response()->json('ok');
    }

    /**
     * Cache widget settings in Redis
     */
    protected function cacheWidgetSettings(int $widget_id, array $settings, ?int $tenant_id): void
    {
        try {
            $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                "pagebuilder_widget_{$widget_id}"
            );

            Cache::store('redis')->put(
                $cacheKey,
                $settings,
                config('cache-tenancy.ttl.default', 3600)
            );

            Cache::forget('widget_settings_cache' . $widget_id);
            Cache::put('widget_settings_cache' . $widget_id, $settings, 3600);
        } catch (\Exception $e) {
            \Log::warning('Failed to cache FooterBuilder widget settings', [
                'widget_id' => $widget_id,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Clear widget cache
     */
    protected function clearWidgetCache(int $widget_id, ?int $tenant_id): void
    {
        try {
            $cacheKey = TenantCacheServiceProvider::getTenantCacheKey(
                "pagebuilder_widget_{$widget_id}"
            );

            Cache::store('redis')->forget($cacheKey);
            Cache::forget('widget_settings_cache' . $widget_id);
        } catch (\Exception $e) {
            \Log::warning('Failed to clear FooterBuilder widget cache', [
                'widget_id' => $widget_id,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Invalidate preview cache
     */
    protected function invalidatePreviewCache(?string $location, ?int $page_id, ?int $tenant_id): void
    {
        try {
            if ($location && $tenant_id) {
                if ($location === 'footer') {
                    Cache::forget('pagebuilder_footer_exists_' . $tenant_id);
                    Cache::forget('pagebuilder_footer_content_' . $tenant_id);
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to invalidate FooterBuilder preview cache', [
                'location' => $location,
                'tenant_id' => $tenant_id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
